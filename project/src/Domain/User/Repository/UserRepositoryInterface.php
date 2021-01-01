<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\Email;
use App\Domain\User\Entity\User;

interface UserRepositoryInterface
{
    public function hasByEmail(Email $email): bool;

    public function add(User $user): void;
}
