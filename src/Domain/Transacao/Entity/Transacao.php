<?php

namespace App\Domain\Transacao\Entity;

use App\Domain\Transacao\Enums\Status;
use App\Domain\Transacao\Enums\TipoTransacao;
use App\Domain\Usuario\Entity\Usuario;

class Transacao
{
    private readonly int $id;

    public function __construct(
        private Usuario $remetente,
        private ?Usuario $destinatario,
        private float $valor,
        private Status $status,
        private TipoTransacao $tipoTransacao,
        private \DateTime $dtTransacao,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRemetente(): Usuario
    {
        return $this->remetente;
    }

    public function setRemetente(Usuario $remetente): void
    {
        $this->remetente = $remetente;
    }

    public function getDestinatario(): ?Usuario
    {
        return $this->destinatario;
    }

    public function setDestinatario(?Usuario $destinatario): void
    {
        $this->destinatario = $destinatario;
    }

    public function getValor(): float
    {
        return $this->valor;
    }

    public function setValor(float $valor): void
    {
        $this->valor = $valor;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

    public function getDtTransacao(): \DateTime
    {
        return $this->dtTransacao;
    }

    public function setDtTransacao(\DateTime $dtTransacao): void
    {
        $this->dtTransacao = $dtTransacao;
    }

    public function getTipoTransacao(): TipoTransacao
    {
        return $this->tipoTransacao;
    }

    public function setTipoTransacao(TipoTransacao $tipoTransacao): void
    {
        $this->tipoTransacao = $tipoTransacao;
    }
}
