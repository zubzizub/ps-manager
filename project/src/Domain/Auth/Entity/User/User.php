<?php

declare(strict_types=1);

namespace App\Domain\Auth\Entity\User;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping;
use DomainException;

/**
 * @Mapping\Entity
 * @Mapping\HasLifecycleCallbacks
 * @Mapping\Table (name="auth_users", uniqueConstraints={
 *     @Mapping\UniqueConstraint(columns={"email"}),
 *     @Mapping\UniqueConstraint(columns={"reset_token_token"})
 * })
 */
class User
{
    private const STATUS_WAIT = 'wait';
    private const STATUS_ACTIVE = 'active';

    /**
     * @Mapping\Column (type="auth_user_id")
     * @Mapping\Id
     */
    private Id $id;

    /**
     * @Mapping\Column (type="auth_user_email", nullable=true)
     */
    private ?Email $email = null;

    /**
     * @Mapping\Column (type="string", length=16)
     */
    private string $status;

    /**
     * @Mapping\Column (type="string", length=500)
     */
    private string $passwordHash;

    /**
     * @Mapping\Embedded(class="ResetToken", columnPrefix="reset_token_")
     */
    private ?ResetToken $resetToken = null;

    /**
     * @Mapping\Column (type="string", name="confirm_token", nullable=true)
     */
    private ?string $confirmToken = null;

    /**
     * @Mapping\Column (type="date_immutable")
     */
    private DateTimeImmutable $date;

    /**
     * @Mapping\Column (type="auth_user_role")
     */
    private Role $role;

    /**
     * @var ArrayCollection
     * @Mapping\OneToMany(targetEntity="Network", mappedBy="user",
     *      orphanRemoval=true, cascade={"persist"})
     */
    private $networks;

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
            throw new DomainException('Auth is already confirmed.');
        }
        $this->status = self::STATUS_ACTIVE;
        $this->confirmToken = null;
    }

    public function requestPasswordReset(ResetToken $token, DateTimeImmutable $date): void
    {
        if (!$this->isActive()) {
            throw new DomainException('Auth is not active.');
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
            throw new DomainException('Auth already has this role.');
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

    /**
     * @Mapping\PostLoad()
     */
    public function checkEmbeds()
    {
        if ($this->resetToken->isEmpty()) {
            $this->resetToken = null;
        }
    }
}
