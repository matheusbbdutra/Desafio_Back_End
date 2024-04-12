<?php

namespace App\Domain\Usuario\ValueObject;

class Documento
{
    public function __construct(private string $cpfCnpj)
    {
        $cpfCnpj = preg_replace("/[^0-9]/", "", $cpfCnpj);

        if (strlen($cpfCnpj) === 11 && !$this->validateCPF($cpfCnpj)) {
            throw new \InvalidArgumentException('CPF informado é inválido!');
        } elseif (strlen($cpfCnpj) === 14 && !$this->validateCNPJ($this->cpfCnpj)) {
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
        $cpf = preg_replace('/[^0-9]/is', '', $cpfCnpj);

        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
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
        $cnpj = preg_replace('/[^0-9]/', '', $cpfCnpj);

        if (strlen($cnpj) != 14) {
            return false;
        }

        $calcularCNPJ = function ($cnpj, $posicao = 5) {
            $calculo = 0;
            for ($i = 0; $i < strlen($cnpj); $i++) {
                $calculo = $calculo + ($cnpj[$i] * $posicao);
                $posicao = ($posicao == 2) ? 9 : $posicao - 1;
            }
            return $calculo;
        };

        $digito1 = $calcularCNPJ(substr($cnpj, 0, 12));
        $digito1 = (11 - ($digito1 % 11)) > 9 ? 0 : (11 - ($digito1 % 11));
        $digito2 = $calcularCNPJ(substr($cnpj, 0, 12) . $digito1);
        $digito2 = (11 - ($digito2 % 11)) > 9 ? 0 : (11 - ($digito2 % 11));

        return  $digito1 === (int) $cnpj[12] && $digito2 === (int) $cnpj[13];
    }
}
