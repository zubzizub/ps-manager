<?php

declare(strict_types=1);

namespace App\Domain\Auth\Entity\User;

use Doctrine\ORM\Mapping;
use Ramsey\Uuid\Uuid;

/**
 * @Mapping\Entity
 * @Mapping\Table (name="auth_user_networks", uniqueConstraints={
 *      @Mapping\UniqueConstraint(columns={"network", "identity"})
 * })
 */
class Network
{
    /**
     * @var string
     * @Mapping\Column (type="guid")
     * @Mapping\Id
     */
    private string $id;

    /**
     * @var User
     * @Mapping\ManyToOne(targetEntity="User", inversedBy="networks")
     * @Mapping\JoinColumn(name="user_id", referencedColumnName="id",
     *      nullable=false, onDelete="CASCADE")
     */
    private User $user;

    /**
     * @var string
     * @Mapping\Column (type="string", length=32, nullable=true)
     */
    private string $network;

    /**
     * @var string
     * @Mapping\Column (type="string", length=32, nullable=true)
     */
    private string $identity;

    public function __construct(User $user, string $network, string $identity)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->user = $user;
        $this->network = $network;
        $this->identity = $identity;
    }

    public function isForNetwork(string $network): bool
    {
        return $this->network === $network;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getNetwork(): string
    {
        return $this->network;
    }

    public function getIdentity(): string
    {
        return $this->identity;
    }
}
