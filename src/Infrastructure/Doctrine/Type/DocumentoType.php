<?php

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\Usuario\ValueObject\Documento;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class DocumentoType extends Type
{
    private const NAME = 'documento';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Documento
    {
        if (! is_string($value)) {
            throw new \InvalidArgumentException('O valor deve ser uma string para construção do Documento');
        }

        return new Documento($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if ($value instanceof Documento) {
            return $value->getCpfCnpj();
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        throw new \InvalidArgumentException('O valor fornecido não pode ser convertido para string de forma segura.');
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
