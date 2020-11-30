<?php


namespace App\Model\Parser\Entity\Game;

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
