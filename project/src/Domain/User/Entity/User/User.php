<?php

declare(strict_types=1);

namespace App\Domain\User\Entity\User;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use DomainException;

class User
{
    private const STATUS_WAIT = 'wait';
    private const STATUS_ACTIVE = 'active';

    private Id $id;

    private ?Email $email = null;

    private string $status;

    private string $passwordHash;

    private ?ResetToken $resetToken = null;

    private ?string $confirmToken = null;

    private DateTimeImmutable $date;

    private Role $role;
    /**
     * @var ArrayCollection
     */
    private ArrayCollection $networks;

    private function __construct(Id $id, DateTimeImmutable $date)
    {
        $this->id = $id;
        $this->date = $date;
        $this->role = Role::user();
        $this->networks = new ArrayCollection();
    }

    public static function signUpByEmail(
        Id $id,
        DateTimeImmutable $date,
        Email $email,
        string $hash,
        string $token
    ): User {
        $user = new self($id, $date);
        $user->email = $email;
        $user->passwordHash = $hash;
        $user->confirmToken = $token;
        $user->status = self::STATUS_WAIT;
        return $user;
    }

    public static function signUpByNetwork(
        Id $id,
        DateTimeImmutable $date,
        string $network,
        string $identity
    ): User
    {
        $user = new self($id, $date);
        $user->attacheNetwork($network, $identity);
        $user->status = self::STATUS_ACTIVE;
        return $user;
    }

    private function attacheNetwork(string $network, string $identity): void
    {
        /** @var Network $existsNetwork */
        foreach ($this->networks as $existsNetwork) {
            if ($existsNetwork->isForNetwork($network)) {
                throw new DomainException('Network is already attached.');
            }
        }
        $this->networks->add(new Network($this, $network, $identity));
    }

    public function confirmSignUp(): void
    {
        if (!$this->isWait()) {
            throw new DomainException('User is already confirmed.');
        }
        $this->status = self::STATUS_ACTIVE;
        $this->confirmToken = null;
    }

    public function requestPasswordReset(ResetToken $token, DateTimeImmutable $date): void
    {
        if (!$this->isActive()) {
            throw new DomainException('User is not active.');
        }

        if (!$this->email) {
            throw new DomainException('Email is not specified.');
        }

        if ($this->resetToken !== null && !$this->resetToken->isExpiredTo($date)) {
            throw new DomainException('Resetting is already requested.');
        }

        $this->resetToken = $token;
    }

    public function passwordReset(string $hash, DateTimeImmutable $date):void
    {
        if ($this->resetToken === null) {
            throw new DomainException('Resetting is not requested.');
        }

        if ($this->resetToken->isExpiredTo($date)) {
            throw new DomainException('Reset token is expired.');
        }
        $this->passwordHash = $hash;
        $this->resetToken = null;
    }

    public function changeRole(Role $role): void
    {
        if ($this->role->isEqual($role)) {
            throw new DomainException('User already has this role.');
        }

        $this->role = $role;
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

    public function getResetToken(): ?ResetToken
    {
        return $this->resetToken;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * @return Network[]
     */
    public function getNetworks(): array
    {
        return $this->networks->toArray();
    }
}
