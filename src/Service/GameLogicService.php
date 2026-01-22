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
        private ItemEffectService $itemEffectService,
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
        $progress->reset();
        $progress->setCurrentZoneId(GameProgress::INTRODUCTION_ZONE_ID);

        if ($progress->getEquipment()) {
            $hearts = $this->itemEffectService->calculateInitialHearts($progress->getEquipment());
            $progress->setHearts($hearts);
        }

        $this->em->flush();

        return $progress;
    }


    public function isZoneUnlocked(GameProgress $progress, Zone $zone): bool
    {
        return $progress->getPoints() >= $zone->getMinPointsToUnlock();
    }

    public function getUnlockedZones(GameProgress $progress): array
    {
        return $this->zoneRepo->findUnlockedZones($progress->getPoints());
    }

    public function canPlayerAnswerQuestion(GameProgress $progress, Question $question): bool
    {
        if ($question->isOneTimeOnly()) {
            $completion = $this->completionRepo->findOneBy([
                'gameProgress' => $progress,
                'gameEvent' => null,
            ]);

            if ($completion) {
                return false;
            }
        }

        return true;
    }

    public function canPlayerPlayEvent(GameProgress $progress, GameEvent $event): bool
    {
        if ($event->isOneTimeOnly()) {
            return !$this->completionRepo->hasCompletedEvent($progress, $event);
        }

        return true;
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
                'isGameOver' => false,
            ];
        }

        $heartsChange = 0;
        $pointsChange = 0;

        if ($isCorrect) {
            $heartsChange = $question->getRewardHearts();
            $pointsChange = $question->getRewardPoints();
        } else {
            $heartsChange = -$question->getPenaltyHearts();
            $pointsChange = -$question->getPenaltyPoints();
        }

        $this->applyHealthModifier($progress, $heartsChange);
        $this->applyPointsModifier($progress, $pointsChange);

        if ($question->isOneTimeOnly()) {
            $this->recordEventCompletion($progress, null, $question);
        }

        $this->em->flush();

        $gameOver = $progress->getHearts() <= 0;

        return [
            'success' => true,
            'message' => $isCorrect ? 'Bonne réponse!' : 'Mauvaise réponse',
            'heartsChange' => $heartsChange,
            'pointsChange' => $pointsChange,
            'currentHearts' => $progress->getHearts(),
            'currentPoints' => $progress->getPoints(),
            'isGameOver' => $gameOver,
        ];
    }

    public function processEventCompletion(GameProgress $progress, GameEvent $event): array
    {
        if (!$this->canPlayerPlayEvent($progress, $event)) {
            return [
                'success' => false,
                'message' => 'Événement déjà complété',
                'isGameOver' => false,
            ];
        }

        $heartsEarned = $event->getRewardHearts() - $event->getPenaltyHearts();
        $pointsEarned = $event->getRewardPoints() - $event->getPenaltyPoints();

        $this->applyHealthModifier($progress, $heartsEarned);
        $this->applyPointsModifier($progress, $pointsEarned);

        $this->recordEventCompletion($progress, $event, null);

        $this->em->flush();

        $gameOver = $progress->getHearts() <= 0;

        return [
            'success' => true,
            'message' => 'Événement complété!',
            'heartsEarned' => $heartsEarned,
            'pointsEarned' => $pointsEarned,
            'currentHearts' => $progress->getHearts(),
            'currentPoints' => $progress->getPoints(),
            'isGameOver' => $gameOver,
        ];
    }

    private function applyHealthModifier(GameProgress $progress, int $amount): void
    {
        $newHearts = $progress->getHearts() + $amount;
        $newHearts = max(0, min(GameProgress::MAX_HEARTS, $newHearts));
        $progress->setHearts($newHearts);
    }

    private function applyPointsModifier(GameProgress $progress, int $amount): void
    {
        $newPoints = max(0, $progress->getPoints() + $amount);
        $progress->setPoints($newPoints);
    }

    private function recordEventCompletion(
        GameProgress $progress,
        ?GameEvent $event,
        ?Question $question
    ): void {
        $completion = new PlayerEventCompletion();
        $completion->setGameProgress($progress);
        $completion->setCompletedAt(new \DateTimeImmutable());

        if ($event) {
            $completion->setGameEvent($event);
            $completion->setHeartsEarned($event->getRewardHearts());
            $completion->setPointsEarned($event->getRewardPoints());
        } elseif ($question) {
            $completion->setHeartsEarned($question->getRewardHearts());
            $completion->setPointsEarned($question->getRewardPoints());
        }

        $this->em->persist($completion);
    }
}
