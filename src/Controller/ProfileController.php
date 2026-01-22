<?php

namespace App\Controller;

use App\Service\PlayerService;
use App\Service\ItemEffectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profile', name: 'app_profile_')]
#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    public function __construct(
        private PlayerService $playerService,
        private ItemEffectService $itemEffectService,
    ) {
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $player = $this->playerService->getOrCreatePlayerForUser($this->getUser());
        $progress = $player->getCurrentProgress();

        if (!$progress) {
            return $this->redirectToRoute('game_index');
        }

        $equipment = $progress->getEquipment();
        $equippedItems = $equipment?->getEquippedItems() ?? [];

        return $this->render('profile/index.html.twig', [
            'player' => $player,
            'progress' => $progress,
            'equippedItems' => $equippedItems,
            'pointsMultiplier' => $equipment ? $this->itemEffectService->calculatePointsMultiplier($equipment) : 1.0,
        ]);
    }
}
