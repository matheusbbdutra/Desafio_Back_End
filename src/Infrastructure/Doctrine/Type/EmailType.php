<?php

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\Usuario\ValueObject\Email;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class EmailType extends Type
{
    private const NAME = 'email';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Email
    {
        return new Email($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if ($value instanceof Email) {
            return $value->getEmail();
        }
        return $value;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
