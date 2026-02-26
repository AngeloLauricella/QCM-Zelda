<?php

namespace App\Service;

use App\Entity\Zone;
use App\Entity\Player;
use App\Entity\Question;
use App\Entity\GameProgress;
use App\Entity\ZoneProgress;
use App\Repository\ZoneRepository;
use App\Entity\PlayerEventCompletion;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GameProgressRepository;
use App\Repository\ZoneProgressRepository;
use App\Repository\PlayerEventCompletionRepository;

class GameLogicService
{
    public function __construct(
        private EntityManagerInterface $em,
        private GameProgressRepository $gameProgressRepo,
        private PlayerEventCompletionRepository $completionRepo,
        private ZoneRepository $zoneRepo,
        private ZoneProgressRepository $zoneProgressRepo,
        private QuestionRepository $questionRepo,
        private ZoneProgressionService $zoneProgression,
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
        // 🔥 Supprimer toutes les entrées liées au player
        $this->em->createQuery(
            'DELETE FROM App\Entity\GameProgress gp WHERE gp.player = :player'
        )->setParameter('player', $player)
            ->execute();

        $this->em->flush();

        // 🔵 Créer nouvelle partie
        $progress = new GameProgress($player);
        $progress->setHearts(GameProgress::MAX_HEARTS);
        $progress->setPoints(0);
        $progress->setGameOver(false);

        $firstZone = $this->zoneRepo->findFirstActiveZone();
        if ($firstZone) {
            $progress->setCurrentZoneId($firstZone->getId());
        }

        $this->em->persist($progress);
        $this->em->flush();

        return $progress;
    }

    public function cleanCompletedQuestions(GameProgress $progress): void
    {
        $completions = $this->completionRepo->findBy(['gameProgress' => $progress]);
        foreach ($completions as $completion) {
            $this->em->remove($completion);
        }
        $this->em->flush();
    }

    public function getUnlockedZonesWithProgress(Player $player): array
    {
        $progress = $this->getOrCreateProgress($player);
        $zones = $this->zoneRepo->findPlayableZones($progress->getPoints());

        $result = [];
        foreach ($zones as $zone) {
            $zoneProgress = $this->zoneProgression->getOrCreateZoneProgress($player, $zone);
            $result[] = ['zone' => $zone, 'progress' => $zoneProgress];
        }

        return $result;
    }

    public function getCompletedZonesWithProgress(Player $player): array
    {
        $allZones = $this->zoneRepo->findBy(['isActive' => true], ['displayOrder' => 'ASC']);
        $result = [];

        foreach ($allZones as $zone) {
            $zoneProgress = $this->zoneProgression->getOrCreateZoneProgress($player, $zone);
            if ($zoneProgress->getStatus() === ZoneProgress::STATUS_COMPLETED) {
                $result[] = ['zone' => $zone, 'progress' => $zoneProgress];
            }
        }

        return $result;
    }

    public function isZoneUnlocked(GameProgress $progress, Zone $zone): bool
    {
        if ($progress->getPoints() < $zone->getMinPointsToUnlock()) {
            return false;
        }

        $zoneProgress = $this->zoneProgressRepo->findOneBy([
            'player' => $progress->getPlayer(),
            'zone' => $zone
        ]);

        if (!$zoneProgress) {
            return false;
        }

        return $zoneProgress->isUnlocked() || $zoneProgress->isCompleted();
    }


    public function getCurrentPlayableZone(Player $player): ?Zone
    {
        $progress = $this->getOrCreateProgress($player);
        return $this->zoneRepo->find($progress->getCurrentZoneId());
    }

    public function getUnansweredQuestions(GameProgress $progress, Zone $zone): array
    {
        $allQuestions = $this->questionRepo->findBy(
            ['zone' => $zone, 'isActive' => true],
            ['displayOrder' => 'ASC', 'id' => 'ASC']
        );

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

    public function processQuestionAnswer(GameProgress $progress, Question $question, bool $isCorrect): array
    {
        if (!$this->canPlayerAnswerQuestion($progress, $question)) {
            return [
                'success' => false,
                'message' => 'Question déjà répondue',
                'isGameOver' => $progress->isGameOver(),
            ];
        }

        $heartsChange = $isCorrect ? $question->getRewardHearts() : -$question->getPenaltyHearts();
        $pointsChange = $isCorrect ? $question->getRewardPoints() : -$question->getPenaltyPoints();

        $progress->setHearts(
            max(0, min(GameProgress::MAX_HEARTS, $progress->getHearts() + $heartsChange))
        );
        $progress->setPoints(max(0, $progress->getPoints() + $pointsChange));

        $completion = new PlayerEventCompletion();
        $completion->setGameProgress($progress);
        $completion->setQuestion($question);
        $completion->setCompletedAt(new \DateTimeImmutable());
        $completion->setHeartsEarned($isCorrect ? $question->getRewardHearts() : 0);
        $completion->setPointsEarned($isCorrect ? $question->getRewardPoints() : 0);

        $this->em->persist($completion);

        $this->registerAnswer(
            $progress,
            $question->getZone(),
            $isCorrect,
            $isCorrect ? $question->getRewardPoints() : 0
        );

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

    public function unlockNextZoneIfNeeded(Player $player, Zone $completedZone): void
    {
        // Récupère toutes les zones actives triées par ordre
        $allZones = $this->zoneRepo->findBy(['isActive' => true], ['displayOrder' => 'ASC']);

        // Cherche l'index de la zone complétée
        $currentIndex = null;
        foreach ($allZones as $i => $zone) {
            if ($zone->getId() === $completedZone->getId()) {
                $currentIndex = $i;
                break;
            }
        }

        if ($currentIndex === null || !isset($allZones[$currentIndex + 1])) {
            // Pas de zone suivante
            return;
        }

        $nextZone = $allZones[$currentIndex + 1];

        // Vérifie si une progression existe déjà
        $nextProgress = $this->zoneProgressRepo->findOneBy([
            'player' => $player,
            'zone' => $nextZone
        ]);

        if (!$nextProgress) {
            // Crée la progression et débloque
            $nextProgress = new ZoneProgress($player, $nextZone, ZoneProgress::STATUS_UNLOCKED);
            $this->em->persist($nextProgress);
        } elseif ($nextProgress->isLocked()) {
            $nextProgress->unlock();
        }

        $this->em->flush();
    }

    public function registerAnswer(
        GameProgress $progress,
        Zone $zone,
        bool $isCorrect,
        int $points = 0
    ): void {

        $zoneProgress = $this->zoneProgressRepo->findOneBy([
            'player' => $progress->getPlayer(),
            'zone' => $zone
        ]);

        if (!$zoneProgress) {
            return;
        }

        $zoneProgress->incrementQuestionsAnswered();

        if ($isCorrect) {
            $zoneProgress->incrementQuestionsCorrect();
            $zoneProgress->addZoneScore($points);
        }

        $totalQuestions = count($zone->getQuestions());

        if ($zoneProgress->isFullyAnswered($totalQuestions)) {
            $zoneProgress->complete();
            $this->unlockNextZoneIfNeeded($progress->getPlayer(), $zone);
        }
    }





}
