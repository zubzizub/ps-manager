<?php

namespace App\Infrastructure\User\Types;

use App\Domain\Auth\Entity\User\Email;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class EmailType extends StringType
{
    public const NAME = 'auth_user_email';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Email ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Email($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
