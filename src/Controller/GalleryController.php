<?php

namespace App\Controller;

use App\Service\PlayerService;
use App\Repository\TrophyRepository;
use App\Repository\PurchaseHistoryRepository;
use App\Repository\ShopItemRepository;
use App\Service\ShopService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/gallery', name: 'app_gallery_')]
class GalleryController extends AbstractController
{
    public function __construct(
        private PlayerService $playerService,
        private TrophyRepository $trophyRepo,
        private PurchaseHistoryRepository $purchaseRepo,
        private ShopItemRepository $shopRepo,
        private ShopService $shopService,
    ) {
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();
        $player = $user ? $this->playerService->getOrCreatePlayerForUser($user) : null;
        $progress = $player?->getCurrentProgress();

        $allTrophies = $this->trophyRepo->findVisibleTrophies();
        $ownedTrophies = $progress ? $this->purchaseRepo->findBy(['gameProgress' => $progress]) : [];
        $ownedTrophyIds = array_map(fn($p) => $p->getTrophy()->getId(), $ownedTrophies);

        $trophiesByType = [
            'passive' => [],
            'active' => [],
        ];

        foreach ($allTrophies as $trophy) {
            $isOwned = in_array($trophy->getId(), $ownedTrophyIds);
            $trophy->owned = $isOwned;
            
            if ($trophy->isPassive()) {
                $trophiesByType['passive'][] = $trophy;
            } else {
                $trophiesByType['active'][] = $trophy;
            }
        }

        return $this->render('gallery/index.html.twig', [
            'passiveTrophies' => $trophiesByType['passive'],
            'activeTrophies' => $trophiesByType['active'],
            'player' => $player,
            'progress' => $progress,
        ]);
    }

    #[Route('/shop', name: 'shop', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function shop(): Response
    {
        $player = $this->playerService->getOrCreatePlayerForUser($this->getUser());
        $progress = $player->getCurrentProgress();
        $message = null; 

        if (!$progress) {
            return $this->redirectToRoute('game_index');
        }

        $shopItems = $this->shopRepo->findAvailable();

        return $this->render('gallery/shop.html.twig', [
            'shopItems' => $shopItems,
            'progress' => $progress,
            'player' => $player,
            'message' => $message,
        ]);
    }

    #[Route('/shop/buy/{itemId}', name: 'shop_buy', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function buyItem(int $itemId): Response
    {
        $player = $this->playerService->getOrCreatePlayerForUser($this->getUser());
        $progress = $player->getCurrentProgress();

        if (!$progress) {
            $this->addFlash('error', 'Pas de partie active');
            return $this->redirectToRoute('gallery_shop');
        }

        $shopItem = $this->shopRepo->find($itemId);
        if (!$shopItem) {
            $this->addFlash('error', 'Article introuvable');
            return $this->redirectToRoute('gallery_shop');
        }

        $result = $this->shopService->purchaseItem($progress, $shopItem);

        if ($result['success']) {
            $this->addFlash('success', $result['message']);
        } else {
            $this->addFlash('error', $result['message']);
        }

        return $this->redirectToRoute('gallery_shop');
    }
}
