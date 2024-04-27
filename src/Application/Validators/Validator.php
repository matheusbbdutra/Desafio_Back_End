<?php

namespace App\Application\Validators;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Validator
{

    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    public function validate(object $object, ?array $groups = null)
    {
        $erros = $this->validator->validate($object, null , $groups);

        if (count($erros) > 0) {
            $mensagem = '';
            foreach ($erros as $erro) {
                $mensagem .= $erro->getMessage() . PHP_EOL;
            }
            throw new \Exception($mensagem);
        }
    }
}