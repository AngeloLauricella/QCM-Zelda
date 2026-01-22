<?php

namespace App\Service;

use App\Entity\GameProgress;
use App\Entity\ShopItem;
use App\Entity\PurchaseHistory;
use App\Repository\PurchaseHistoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class ShopService
{
    public function __construct(
        private EntityManagerInterface $em,
        private PurchaseHistoryRepository $purchaseRepo,
    ) {
    }

    public function canAfford(GameProgress $progress, ShopItem $item): bool
    {
        return $progress->getPoints() >= $item->getCost();
    }

    public function purchaseItem(GameProgress $progress, ShopItem $item): array
    {
        if (!$this->canAfford($progress, $item)) {
            return [
                'success' => false,
                'message' => 'Vous n\'avez pas assez de points',
            ];
        }

        if (!$item->isAvailable()) {
            return [
                'success' => false,
                'message' => 'Article indisponible',
            ];
        }

        $progress->setPoints($progress->getPoints() - $item->getCost());
        $item->decrementStock();

        $purchase = new PurchaseHistory();
        $purchase->setGameProgress($progress);
        $purchase->setTrophy($item->getTrophy());
        $purchase->setCostPaid($item->getCost());
        $purchase->setPurchasedAt(new \DateTimeImmutable());

        $this->em->persist($purchase);
        $this->em->flush();

        return [
            'success' => true,
            'message' => 'Achat effectué!',
            'trophy' => $item->getTrophy(),
        ];
    }

    public function hasOwnedItem(GameProgress $progress, ShopItem $item): bool
    {
        $purchase = $this->purchaseRepo->findOneBy([
            'gameProgress' => $progress,
            'trophy' => $item->getTrophy(),
        ]);

        return $purchase !== null;
    }
}
