<?php

declare(strict_types=1);

namespace App\Domain\Auth\Repository;

use App\Domain\Auth\Entity\User\Email;
use App\Domain\Auth\Entity\User\Id;
use App\Domain\Auth\Entity\User\User;

interface UserRepositoryInterface
{
    public function hasByEmail(Email $email): bool;

    public function add(User $user): void;

    public function findByConfirmToken(string $token): ?User;

    public function hasByNetworkIdentity(string $network, string $identity): bool;

    public function getByEmail(Email $email): User;

    public function findByResetToken(string $token): ?User;

    public function getById(Id $id): User;
}
