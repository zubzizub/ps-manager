<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Doctrine;

use App\Domain\Auth\Entity\User\Email;
use App\Domain\Auth\Entity\User\Id;
use App\Domain\Auth\Entity\User\User;
use App\Domain\Auth\Repository\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function hasByEmail(Email $email): bool
    {
        // TODO: Implement hasByEmail() method.
    }

    public function add(User $user): void
    {
        // TODO: Implement add() method.
    }

    public function findByConfirmToken(string $token): ?User
    {
        // TODO: Implement findByConfirmToken() method.
    }

    public function hasByNetworkIdentity(string $network, string $identity): bool
    {
        // TODO: Implement hasByNetworkIdentity() method.
    }

    public function getByEmail(Email $email): User
    {
        // TODO: Implement getByEmail() method.
    }

    public function findByResetToken(string $token): ?User
    {
        // TODO: Implement findByResetToken() method.
    }

    public function getById(Id $id): User
    {
        // TODO: Implement getById() method.
    }
}
