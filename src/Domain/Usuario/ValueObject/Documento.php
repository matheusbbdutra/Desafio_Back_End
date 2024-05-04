<?php

namespace App\Domain\Usuario\ValueObject;

class Documento
{
    public function __construct(private string $cpfCnpj)
    {
        $cpfCnpj = (string) preg_replace('/[^0-9]/', '', $cpfCnpj);

        if (11 === strlen($cpfCnpj) && ! $this->validateCPF($cpfCnpj)) {
            throw new \InvalidArgumentException('CPF informado é inválido!');
        } elseif (14 === strlen($cpfCnpj) && ! $this->validateCNPJ($this->cpfCnpj)) {
            throw new \InvalidArgumentException('CNPJ informado é inválido!');
        }

        $this->cpfCnpj = $cpfCnpj;
    }

    public function getCpfCnpj(): string
    {
        return $this->cpfCnpj;
    }

    public function __toString(): string
    {
        return $this->cpfCnpj;
    }

    private function validateCPF(string $cpfCnpj): bool
    {
        $cpf = (string) preg_replace('/[^0-9]/is', '', $cpfCnpj);

        if (11 != strlen($cpf) || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; ++$t) {
            for ($d = 0, $c = 0; $c < $t; ++$c) {
                $d += intval($cpf[$c]) * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }

    private function validateCNPJ(string $cpfCnpj): bool
    {
        $cnpj = (string) preg_replace('/[^0-9]/', '', $cpfCnpj);

        if (14 != strlen($cnpj)) {
            return false;
        }

        $calcularCNPJ = function ($cnpj, $posicao = 5) {
            $calculo = 0;
            for ($i = 0; $i < strlen($cnpj); ++$i) {
                $calculo = $calculo + ($cnpj[$i] * $posicao);
                $posicao = (2 == $posicao) ? 9 : $posicao - 1;
            }

            return $calculo;
        };

        $digito1 = $calcularCNPJ(substr($cnpj, 0, 12));
        $digito1 = (11 - ($digito1 % 11)) > 9 ? 0 : (11 - ($digito1 % 11));
        $digito2 = $calcularCNPJ(substr($cnpj, 0, 12).$digito1);
        $digito2 = (11 - ($digito2 % 11)) > 9 ? 0 : (11 - ($digito2 % 11));

        return $digito1 === (int) $cnpj[12] && $digito2 === (int) $cnpj[13];
    }
}
