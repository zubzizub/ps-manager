<?php

declare(strict_types=1);

namespace App\Model\Parser\Entity\Game;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;

class PriceType extends IntegerType
{
    public const NAME = 'game_price';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Price ? $value->getPrice() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Price($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform) : bool
    {
        return true;
    }
}
