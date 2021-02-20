<?php

declare(strict_types=1);

namespace App\Domain\Auth\Entity\User;

use DateTimeImmutable;
use Doctrine\ORM\Mapping;
use Webmozart\Assert\Assert;

/**
 * @Mapping\Embeddable
 */
class ResetToken
{
    /**
     * @var string|null
     * @Mapping\Column (type="string", nullable=true)
     */
    private ?string $token;

    /**
     * @var DateTimeImmutable
     * @Mapping\Column (type="date_immutable", nullable=true)
     */
    private DateTimeImmutable $expires;

    public function __construct(string $token, DateTimeImmutable $expires)
    {
        Assert::notEmpty($token);
        $this->token = $token;
        $this->expires = $expires;
    }

    public function isExpiredTo(DateTimeImmutable $date): bool
    {
        return $this->expires <= $date;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @internal for postLoad callback
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->token);
    }
}
