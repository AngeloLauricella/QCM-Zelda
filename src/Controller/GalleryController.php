<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Entity\PlayerGalleryItem;
use App\Service\PlayerService;
use App\Repository\GalleryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/gallery', name: 'gallery_')]
#[IsGranted('ROLE_USER')]
class GalleryController extends AbstractController
{
    public function __construct(
        private PlayerService $playerService,
        private GalleryRepository $galleryRepo,
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $player = $this->playerService->getOrCreatePlayerForUser($this->getUser());
        
        // Récupérer UNIQUEMENT les items achetés par ce joueur
        $purchasedItems = $this->em->getRepository(PlayerGalleryItem::class)
            ->findBy(['player' => $player], ['purchasedAt' => 'DESC']);
        
        return $this->render('gallery/index.html.twig', [
            'purchasedItems' => $purchasedItems,
            'player' => $player,
            'shopPoints' => $player->getShopPoints(),
        ]);
    }

    #[Route('/shop', name: 'shop_index', methods: ['GET'])]
    public function shop(): Response
    {
        $player = $this->playerService->getOrCreatePlayerForUser($this->getUser());
        
        // Tous les articles disponibles à l'achat
        $allItems = $this->galleryRepo->findAll();
        
        // IDs des articles déjà achetés par ce joueur
        $purchasedItems = $this->em->getRepository(PlayerGalleryItem::class)
            ->findBy(['player' => $player]);
        
        $purchasedItemIds = array_map(
            fn($purchase) => $purchase->getGalleryItem()->getId(),
            $purchasedItems
        );
        
        return $this->render('gallery/shop.html.twig', [
            'items' => $allItems,
            'purchasedItemIds' => $purchasedItemIds,
            'player' => $player,
            'shopPoints' => $player->getShopPoints(),
        ]);
    }

    #[Route('/shop/buy/{itemId}', name: 'shop_buy', methods: ['POST'])]
    public function buyItem(int $itemId): Response
    {
        $player = $this->playerService->getOrCreatePlayerForUser($this->getUser());
        $item = $this->galleryRepo->find($itemId);
        
        // Vérifications
        if (!$item) {
            $this->addFlash('error', '❌ Article introuvable');
            return $this->redirectToRoute('gallery_shop_index');
        }
        
        // Vérifier si déjà acheté
        $alreadyPurchased = $this->em->getRepository(PlayerGalleryItem::class)
            ->findOneBy(['player' => $player, 'galleryItem' => $item]);
        
        if ($alreadyPurchased) {
            $this->addFlash('warning', '⚠️ Tu possèdes déjà cet article!');
            return $this->redirectToRoute('gallery_shop_index');
        }
        
        // Vérifier les points
        if (!$player->hasEnoughShopPoints($item->getPrice())) {
            $this->addFlash('error', sprintf(
                '❌ Points insuffisants! Il te faut %d points (tu as %d points)',
                $item->getPrice(),
                $player->getShopPoints()
            ));
            return $this->redirectToRoute('gallery_shop_index');
        }
        
        // EFFECTUER L'ACHAT
        $player->removeShopPoints($item->getPrice());
        
        $purchase = new PlayerGalleryItem();
        $purchase->setPlayer($player);
        $purchase->setGalleryItem($item);
        $purchase->setPurchasedAt(new \DateTime());
        
        $this->em->persist($purchase);
        $this->em->flush();
        
        $this->addFlash('success', sprintf(
            '✅ Article "%s" acheté avec succès! Il te reste %d points 💎',
            $item->getTitle(),
            $player->getShopPoints()
        ));
        
        return $this->redirectToRoute('gallery_shop_index');
    }
}

