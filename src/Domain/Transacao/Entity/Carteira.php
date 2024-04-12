<?php

namespace App\Domain\Transacao\Entity;

use App\Domain\Usuario\Entity\Usuario;

class Carteira
{
    private int $id;
    private Usuario $usuario;
    private ?float $saldo;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): void
    {
        $this->usuario = $usuario;
    }

    public function getSaldo(): ?float
    {
        return $this->saldo;
    }

    public function setSaldo(?float $saldo): void
    {
        $this->saldo = $saldo;
    }

    public function adicionarSaldo(float $quantia): void
    {
        if ($quantia < 0) {
            throw new \InvalidArgumentException("Quantia não pode ser negativa.");
        }
        $this->saldo += $quantia;
    }

    public function subtrairSaldo(float $quantia): void
    {
        if ($quantia < 0) {
            throw new \InvalidArgumentException("Quantia não pode ser negativa.");
        }
        if ($this->saldo < $quantia) {
            throw new \LogicException("Saldo insuficiente.");
        }
        $this->saldo -= $quantia;
    }
}
