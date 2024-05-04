<?php

namespace App\Domain\Transacao\Repository;

use App\Domain\Transacao\Entity\Carteira;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CarteiraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,)
    {
        parent::__construct($registry, Carteira::class);
    }

    public function criarCarteira(Carteira $carteira)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->beginTransaction();
        try {
            $entityManager->persist($carteira);
            $entityManager->flush();
            $entityManager->commit();
        } catch (\Exception $e) {
            $entityManager->rollback();

            throw new \RuntimeException("Erro ao criar carteiro do usuário: " . $e->getMessage(), 0, $e);
        }
    }
}
