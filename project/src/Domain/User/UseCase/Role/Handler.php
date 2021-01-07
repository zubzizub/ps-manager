<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\Role;

use App\Domain\User\Entity\Id;
use App\Domain\User\Entity\Role;
use App\Domain\User\FlusherInterface;
use App\Domain\User\Repository\UserRepositoryInterface;

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
