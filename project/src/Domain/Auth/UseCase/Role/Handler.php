<?php

declare(strict_types=1);

namespace App\Domain\Auth\UseCase\Role;

use App\Domain\Auth\Entity\User\Id;
use App\Domain\Auth\Entity\User\Role;
use App\Domain\Auth\FlusherInterface;
use App\Domain\Auth\Repository\UserRepositoryInterface;

class Handler
{
    private UserRepositoryInterface $userRepository;
    private FlusherInterface $flusher;

    public function __construct(UserRepositoryInterface $userRepository, FlusherInterface $flusher)
    {
        $this->userRepository = $userRepository;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $user = $this->userRepository->getById(new Id($command->id));

        $user->changeRole(new Role($command->role));

        $this->flusher->flush();
    }
}
