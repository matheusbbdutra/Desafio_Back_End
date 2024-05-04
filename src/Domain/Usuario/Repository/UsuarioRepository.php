<?php

namespace App\Domain\Usuario\Repository;

use App\Domain\Usuario\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Persistence\ManagerRegistry;

class UsuarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Usuario::class);
    }

    public function criarUsuario(Usuario $usuario): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->beginTransaction();

        try {
            $entityManager->persist($usuario);
            $entityManager->flush();
            $entityManager->commit();
        } catch (UniqueConstraintViolationException $e) {
            $entityManager->rollback();

            throw new \RuntimeException("Erro: Já existe um usuário cadastrado para esse CPF.", 0, $e);
        } catch (\Exception $e) {
            $entityManager->rollback();

            throw new \RuntimeException("Erro ao criar usuário: " . $e->getMessage(), 0, $e);
        }
    }

    public function atualizarUsuario(Usuario $usuario): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->beginTransaction();

        try {
            $entityManager->persist($usuario);
            $entityManager->flush();
            $entityManager->commit();
        } catch (\Exception $e) {
            $entityManager->rollback();

            throw new \RuntimeException("Erro ao atualizar usuário: " . $e->getMessage(), 0, $e);
        }
    }
}
