<?php

namespace App\Domain\Transacao\Services;

use App\Domain\Transacao\Entity\Carteira;
use App\Domain\Transacao\Repository\CarteiraRepository;
use App\Domain\Usuario\Entity\Usuario;

class CarteiraService
{
    public function __construct(private CarteiraRepository $carteiraRepository)
    {
    }

    public function criarCarteira(Usuario $usuario): void
    {
        $carteira = new Carteira();
        $carteira->setUsuario($usuario);
        $this->carteiraRepository->criarCarteira($carteira);
    }
}
