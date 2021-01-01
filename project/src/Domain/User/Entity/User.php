<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use DateTimeImmutable;
use DomainException;

class User
{
    private const STATUS_WAIT = 'wait';
    private const STATUS_ACTIVE = 'active';

    private Id $id;

    private Email $email;

    private string $passwordHash;

    private string $status;

    private ?string $confirmToken;

    private DateTimeImmutable $date;

    public function __construct(
        Id $id,
        Email $email,
        string $passwordHash,
        string $token,
        DateTimeImmutable $date
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->confirmToken = $token;
        $this->date = $date;
        $this->status = self::STATUS_WAIT;
    }

    public function confirmSignUp(): void
    {
        if (!$this->isWait()) {
            throw new DomainException('User is already confirmed.');
        }
        $this->status = self::STATUS_ACTIVE;
        $this->confirmToken = null;
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getConfirmToken(): ?string
    {
        return $this->confirmToken;
    }
}
