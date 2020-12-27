<?php

declare(strict_types=1);

namespace App\Domain\Store\Entity\Game;

use DateTimeImmutable;
use Doctrine\ORM\Mapping;

/**
 * @Mapping\Entity
 * @Mapping\Table(name="store_games")
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
    private string $externalId;

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
     * @var Price|null
     * @Mapping\Column(type="game_price", nullable=true)
     */
    private ?Price $priceDiscount;

    /**
     * @var Status
     * @Mapping\Column(type="game_status", length=16)
     */
    private Status $status;

    /**
     * @var string
     * @Mapping\Column(type="string")
     */
    private string $version;

    /**
     * @var string|null
     * @Mapping\Column(type="string", nullable=true)
     */
    private ?string $imageUrl;

    //todo: after create user
//    private bool $isFavourite;

    /**
     * @var DateTimeImmutable
     * @Mapping\Column(type="datetime_immutable", nullable=true, name="discount_end_date")
     */
    private DateTimeImmutable $discountEndDate;

    /**
     * @var DateTimeImmutable
     * @Mapping\Column(type="datetime_immutable", name="create_date")
     */
    private DateTimeImmutable $createDate;

    /**
     * @var DateTimeImmutable|null
     * @Mapping\Column(type="datetime_immutable", nullable=true, name="update_date")
     */
    private ?DateTimeImmutable $updateDate;

    public function __construct(
        Id $id,
        string $externalId,
        string $title,
        string $description,
        Price $price,
        ?Price $priceDiscount,
        ?string $imageUrl,
        DateTimeImmutable $discountEndDate,
        DateTimeImmutable $createDate
    ) {
        $this->id = $id;
        $this->externalId = $externalId;
        $this->title = $title;
        $this->description = $description;
        $this->version = 'ps4';
        $this->price = $price;
        $this->priceDiscount = $priceDiscount;
        $this->status = Status::active();
        $this->imageUrl = $imageUrl;
        $this->discountEndDate = $discountEndDate;
        $this->createDate = $createDate;
    }

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
    public function getExternalId(): string
    {
        return $this->externalId;
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
     * @return Price
     */
    public function getPrice(): Price
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

    public function getStatus(): Status
    {
        return $this->status;
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
     * @return DateTimeImmutable|null
     */
    public function getDiscountEndDate(): ?DateTimeImmutable
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
     * @return DateTimeImmutable|null
     */
    public function getUpdateDate(): ?DateTimeImmutable
    {
        return $this->updateDate;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param Price $price
     */
    public function setPrice(Price $price): void
    {
        $this->price = $price;
    }

    /**
     * @param Price|null $priceDiscount
     */
    public function setPriceDiscount(?Price $priceDiscount): void
    {
        $this->priceDiscount = $priceDiscount;
    }

    /**
     * @param Status $status
     */
    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

    /**
     * @param string|null $imageUrl
     */
    public function setImageUrl(?string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    /**
     * @param DateTimeImmutable $discountEndDate
     */
    public function setDiscountEndDate(DateTimeImmutable $discountEndDate): void
    {
        $this->discountEndDate = $discountEndDate;
    }
}
