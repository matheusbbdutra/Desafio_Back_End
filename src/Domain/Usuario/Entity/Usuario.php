<?php

namespace App\Domain\Usuario\Entity;

use App\Domain\Entity\Collection;
use App\Domain\Transacao\Entity\Carteira;
use App\Domain\Transacao\Entity\Transacao;
use App\Domain\Usuario\ValueObject\Documento;
use App\Domain\Usuario\ValueObject\Email;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection as DoctrineCollection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class Usuario implements PasswordAuthenticatedUserInterface
{
    private int $id;
    private string $nome;
    private Documento $cpfCnpj;
    private string $senha;
    private Email $email;
    private DoctrineCollection $transacoesEnviadas;
    private DoctrineCollection $transacoesRecebidas;
    private Carteira $carteira;
    private bool $isLogista = false;

    public function __construct()
    {
        $this->transacoesEnviadas = new ArrayCollection();
        $this->transacoesRecebidas = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function getCpfCnpj(): Documento
    {
        return $this->cpfCnpj;
    }

    public function setCpfCnpj(Documento $cpfCnpj): void
    {
        $this->cpfCnpj = $cpfCnpj;
    }

    public function getSenha(): string
    {
        return $this->senha;
    }

    public function setSenha(string $senha): void
    {
        $this->senha = $senha;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function setEmail(Email $email): void
    {
        $this->email = $email;
    }

    public function isLogista(): bool
    {
        return $this->isLogista;
    }

    public function setIsLogista(bool $isLogista): void
    {
        $this->isLogista = $isLogista;
    }

    public function getCarteira(): Carteira
    {
        return $this->carteira;
    }

    public function setCarteira(Carteira $carteira): void
    {
        $this->carteira = $carteira;
    }
    public function getTransacoesEnviadas(): DoctrineCollection
    {
        return $this->transacoesEnviadas;
    }

    public function addTransacaoEnviada(Transacao $transacao): void
    {
        if (!$this->transacoesEnviadas->contains($transacao)) {
            $this->transacoesEnviadas->add($transacao);
            $transacao->setRemetente($this);
        }
    }

    public function removeTransacaoEnviada(Transacao $transacao): void
    {
        if ($this->transacoesEnviadas->contains($transacao)) {
            $this->transacoesEnviadas->removeElement($transacao);
            if ($transacao->getRemetente() === $this) {
                $transacao->setRemetente(null);
            }
        }
    }

    public function getTransacoesRecebidas(): DoctrineCollection
    {
        return $this->transacoesRecebidas;
    }

    public function addTransacaoRecebida(Transacao $transacao): void
    {
        if (!$this->transacoesRecebidas->contains($transacao)) {
            $this->transacoesRecebidas->add($transacao);
            $transacao->setDestinatario($this);
        }
    }

    public function removeTransacaoRecebida(Transacao $transacao): void
    {
        if ($this->transacoesRecebidas->contains($transacao)) {
            $this->transacoesRecebidas->removeElement($transacao);
            if ($transacao->getDestinatario() === $this) {
                $transacao->setDestinatario(null);
            }
        }
    }

    public function getPassword(): ?string
    {
        return $this->senha;
    }
}
