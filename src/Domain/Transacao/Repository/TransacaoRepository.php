<?php

namespace App\Domain\Transacao\Repository;

use App\Domain\Transacao\Entity\Transacao;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

use Doctrine\Persistence\ManagerRegistry;

class TransacaoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transacao::class);
    }
}
