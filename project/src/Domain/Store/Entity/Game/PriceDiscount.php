<?php


namespace App\Domain\Store\Entity\Game;

class PriceDiscount
{
    private ?int $priceDiscount;

    public function __construct(?int $price)
    {
        $this->priceDiscount = $price;
    }

    public function getPrice(): ?int
    {
        return $this->priceDiscount;
    }
}
