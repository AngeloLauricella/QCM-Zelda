<?php

namespace App\Service;

use App\Entity\Player;
use App\Entity\GameProgress;
use App\Entity\Question;
use App\Entity\GameEvent;
use App\Entity\Zone;
use App\Entity\PlayerEventCompletion;
use App\Repository\GameProgressRepository;
use App\Repository\QuestionRepository;
use App\Repository\PlayerEventCompletionRepository;
use App\Repository\ZoneRepository;
use Doctrine\ORM\EntityManagerInterface;

class GameLogicService
{
    public function __construct(
        private EntityManagerInterface $em,
        private GameProgressRepository $gameProgressRepo,
        private QuestionRepository $questionRepo,
        private PlayerEventCompletionRepository $completionRepo,
        private ZoneRepository $zoneRepo,
        private ItemEffectService $itemEffectService
    ) {
    }

    public function getOrCreateProgress(Player $player): GameProgress
    {
        $progress = $this->gameProgressRepo->findOneBy(['player' => $player]);

        if (!$progress) {
            $progress = new GameProgress($player);
            $this->em->persist($progress);
            $this->em->flush();
        }

        return $progress;
    }

    public function startNewAdventure(Player $player): GameProgress
    {
        $progress = $this->getOrCreateProgress($player);
        
        // Clean up completed questions from previous adventure
        $this->cleanCompletedQuestions($progress);
        
        // Reset progress state
        $progress->reset();

        $firstActiveZone = $this->zoneRepo->findFirstActiveZone();
        if ($firstActiveZone) {
            $progress->setCurrentZoneId($firstActiveZone->getId());
        }

        if ($progress->getEquipment()) {
            $hearts = $this->itemEffectService->calculateInitialHearts($progress->getEquipment());
            $progress->setHearts($hearts);
        }

        $this->em->flush();

        return $progress;
    }

    public function cleanCompletedQuestions(GameProgress $progress): void
    {
        // Remove all PlayerEventCompletion entries for this progress
        $completions = $this->completionRepo->findBy(['gameProgress' => $progress]);
        foreach ($completions as $completion) {
            $this->em->remove($completion);
        }
    }

    public function getFirstActiveQuestion(): ?Question
    {
        return $this->questionRepo->findFirstActiveQuestion();
    }

    public function isZoneUnlocked(GameProgress $progress, Zone $zone): bool
    {
        return $progress->getPoints() >= $zone->getMinPointsToUnlock();
    }

    public function getUnlockedZones(GameProgress $progress): array
    {
        return $this->zoneRepo->findPlayableZones($progress->getPoints());
    }

    public function getUnansweredQuestions(GameProgress $progress, Zone $zone): array
    {
        // Get all active questions in zone
        $allQuestions = $this->questionRepo->findBy(
            ['zone' => $zone, 'isActive' => true],
            ['displayOrder' => 'ASC', 'id' => 'ASC']
        );

        // Filter out already answered questions
        $answered = $this->completionRepo->findBy(['gameProgress' => $progress]);
        $answeredIds = array_map(fn($c) => $c->getQuestion()?->getId(), $answered);

        return array_filter($allQuestions, fn($q) => !in_array($q->getId(), $answeredIds));
    }

    public function canPlayerAnswerQuestion(GameProgress $progress, Question $question): bool
    {
        $completion = $this->completionRepo->findOneBy([
            'gameProgress' => $progress,
            'question' => $question
        ]);

        return $completion === null;
    }

    public function processQuestionAnswer(
        GameProgress $progress,
        Question $question,
        bool $isCorrect
    ): array {
        if (!$this->canPlayerAnswerQuestion($progress, $question)) {
            return [
                'success' => false,
                'message' => 'Question déjà répondue',
                'isGameOver' => $progress->isGameOver(),
            ];
        }

        $heartsChange = $isCorrect ? $question->getRewardHearts() : -$question->getPenaltyHearts();
        $pointsChange = $isCorrect ? $question->getRewardPoints() : -$question->getPenaltyPoints();

        $this->applyHealthModifier($progress, $heartsChange);
        $this->applyPointsModifier($progress, $pointsChange);

        if ($isCorrect || $question->isOneTimeOnly()) {
            $this->recordQuestionCompletion($progress, $question);
        }

        $this->checkAndSetGameOver($progress);
        $this->em->flush();

        return [
            'success' => true,
            'message' => $isCorrect ? 'Bonne réponse!' : 'Mauvaise réponse',
            'heartsChange' => $heartsChange,
            'pointsChange' => $pointsChange,
            'currentHearts' => $progress->getHearts(),
            'currentPoints' => $progress->getPoints(),
            'isGameOver' => $progress->isGameOver(),
        ];
    }

    private function applyHealthModifier(GameProgress $progress, int $amount): void
    {
        $progress->setHearts(max(0, min(GameProgress::MAX_HEARTS, $progress->getHearts() + $amount)));
    }

    private function applyPointsModifier(GameProgress $progress, int $amount): void
    {
        $progress->setPoints(max(0, $progress->getPoints() + $amount));
    }

    private function recordQuestionCompletion(GameProgress $progress, Question $question): void
    {
        $completion = new PlayerEventCompletion();
        $completion->setGameProgress($progress);
        $completion->setQuestion($question);
        $completion->setCompletedAt(new \DateTimeImmutable());
        $completion->setHeartsEarned($question->getRewardHearts());
        $completion->setPointsEarned($question->getRewardPoints());

        $this->em->persist($completion);
    }

    private function checkAndSetGameOver(GameProgress $progress): void
    {
        if ($progress->getHearts() <= 0 && !$progress->isGameOver()) {
            $progress->setGameOver(true, 'Vous avez perdu tous vos cœurs');
        }
    }
}
