<?php

namespace App\Domain\Transacao\Repository;

use App\Domain\Transacao\Entity\Carteira;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class CarteiraRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
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
