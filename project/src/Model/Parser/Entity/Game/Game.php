<?php

declare(strict_types=1);

namespace App\Model\Parser\Entity\Game;

use DateTimeImmutable;
use Doctrine\ORM\Mapping;

/**
 * @Mapping\Entity
 * @Mapping\Table(name="parser_games")
 */
class Game
{
    /**
     * @var Id
     * @Mapping\Column(type="game_id")
     * @Mapping\Id
     */
    private Id $id;

    /**
     * @var string
     * @Mapping\Column(type="string")
     */
    private string $idPs;

    /**
     * @var string
     * @Mapping\Column(type="string")
     */
    private string $title;

    /**
     * @var string
     * @Mapping\Column(type="text")
     */
    private string $description;

    /**
     * @var Price
     * @Mapping\Column(type="game_price")
     */
    private Price $price;

    /**
     * @var Price
     * @Mapping\Column(type="game_price")
     */
    private Price $priceDiscount;

    /**
     * @var string
     * @Mapping\Column(type="string")
     */
    private string $version;

    /**
     * @var ?string
     * @Mapping\Column(type="string", nullable=true)
     */
    private ?string $imageUrl;

    /**
     * @var DateTimeImmutable
     * @Mapping\Column(type="datetime_immutable", nullable=true, name="discount_end_date")
     */
    private DateTimeImmutable $discountEndDate;

    /**
     * @var DateTimeImmutable
     * @Mapping\Column(type="datetime_immutable", nullable=true, name="create_date")
     */
    private DateTimeImmutable $createDate;

    /**
     * @var DateTimeImmutable
     * @Mapping\Column(type="datetime_immutable", nullable=true, name="update_date")
     */
    private DateTimeImmutable $updateDate;

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getIdPs(): string
    {
        return $this->idPs;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return Price
     */
    public function getPriceDiscount(): Price
    {
        return $this->priceDiscount;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDiscountEndDate(): DateTimeImmutable
    {
        return $this->discountEndDate;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreateDate(): DateTimeImmutable
    {
        return $this->createDate;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getUpdateDate(): DateTimeImmutable
    {
        return $this->updateDate;
    }
}
