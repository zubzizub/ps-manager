<?php

declare(strict_types=1);

namespace App\Model\Parser\Entity\Game;

use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

class Id
{
    private string $id;

    public function __construct(string $id)
    {
        Assert::notEmpty($id);
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public static function next(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
