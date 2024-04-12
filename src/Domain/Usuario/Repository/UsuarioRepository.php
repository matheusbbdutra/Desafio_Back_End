<?php

namespace App\Domain\Usuario\Repository;

use App\Domain\Usuario\Entity\Usuario;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class UsuarioRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
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

            throw new \RuntimeException("Erro: Usuário já existe.", 0, $e);
        } catch (ForeignKeyConstraintViolationException $e) {
            $entityManager->rollback();

            throw new \RuntimeException("Erro de integridade de referência.", 0, $e);
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
