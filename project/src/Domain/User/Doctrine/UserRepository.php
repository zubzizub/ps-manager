<?php

declare(strict_types=1);

namespace App\Domain\User\Doctrine;

use App\Domain\User\Entity\Email;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;

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
}
