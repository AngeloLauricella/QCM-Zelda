<?php

namespace App\Controller;

use App\Entity\TrophyUnlock;
use App\Service\PlayerService;
use App\Repository\TrophyRepository;
use App\Repository\GameProgressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/trophies', name: 'trophy_')]
#[IsGranted('ROLE_USER')]
class TrophyController extends AbstractController
{
    public function __construct(
        private PlayerService $playerService,
        private TrophyRepository $trophyRepo,
        private GameProgressRepository $progressRepo,
        private EntityManagerInterface $em
    ) {}

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $player = $this->playerService->getOrCreatePlayerForUser($this->getUser());

        // Récupérer le GameProgress du joueur
        $gameProgress = $this->progressRepo->findOneBy(['player' => $player]);

        $trophies = $this->trophyRepo->findBy(
            ['isVisible' => true],
            ['displayOrder' => 'ASC']
        );

        $obtainedIds = [];

        if ($gameProgress) {
            $unlocks = $this->em->getRepository(TrophyUnlock::class)
                ->findBy(['gameProgress' => $gameProgress]);

            $obtainedIds = array_map(
                fn($unlock) => $unlock->getTrophy()->getId(),
                $unlocks
            );
        }

        return $this->render('trophy/index.html.twig', [
            'trophies' => $trophies,
            'obtainedIds' => $obtainedIds,
        ]);
    }
}
