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
     * @Mapping\Column(type="game_id")
     * @Mapping\Id
     */
    private Id $id;

    /**
     * @Mapping\Column(type="string")
     */
    private string $externalId;

    /**
     * @Mapping\Column(type="string")
     */
    private string $title;

    /**
     * @Mapping\Column(type="text")
     */
    private string $description;

    /**
     * @Mapping\Embedded (class="Price", columnPrefix="price_")
     */
    private Price $price;

    /**
     * @Mapping\Column(type="game_status", length=16)
     */
    private Status $status;

    /**
     * @Mapping\Column(type="string")
     */
    private string $version;

    /**
     * @Mapping\Column(type="string", nullable=true)
     */
    private ?string $imageUrl;

    /**
     * @Mapping\Column(type="datetime_immutable", name="create_date")
     */
    private DateTimeImmutable $createDate;

    /**
     * @Mapping\Column(type="datetime_immutable", nullable=true, name="update_date")
     */
    private ?DateTimeImmutable $updateDate;

    public function __construct(
        Id $id,
        string $externalId,
        string $title,
        string $description,
        Price $price,
        ?string $imageUrl,
        DateTimeImmutable $createDate
    ) {
        $this->id = $id;
        $this->externalId = $externalId;
        $this->title = $title;
        $this->description = $description;
        $this->version = 'ps4';
        $this->price = $price;
        $this->status = Status::active();
        $this->imageUrl = $imageUrl;
        $this->createDate = $createDate;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getCreateDate(): DateTimeImmutable
    {
        return $this->createDate;
    }

    public function getUpdateDate(): ?DateTimeImmutable
    {
        return $this->updateDate;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setPrice(Price $price): void
    {
        $this->price = $price;
    }

    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }
}
