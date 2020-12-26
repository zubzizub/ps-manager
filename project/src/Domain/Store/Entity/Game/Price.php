<?php

declare(strict_types=1);

namespace App\Domain\Store\Entity\Game;

use Webmozart\Assert\Assert;

class Price
{
    private int $price;

    public function __construct(int $price)
    {
//        Assert::notEmpty($price);
        $this->price = $price;
    }

    public function getPrice(): int
    {
        return $this->price;
    }
}
