<?php

declare(strict_types=1);

namespace App\Domain\Auth\UseCase\SignUpByEmail\Confirm;

use App\Domain\Flusher;
use App\Domain\Auth\Repository\UserRepositoryInterface;
use DomainException;

class Handler
{
    private UserRepositoryInterface $userRepository;
    private Flusher $flusher;

    public function __construct(
        UserRepositoryInterface $userRepository,
        Flusher $flusher
    ) {
        $this->userRepository = $userRepository;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $user = $this->userRepository->findByConfirmToken($command->token);
        if ($user === null) {
            throw new DomainException('Incorrect or confirmed token.');
        }

        $user->confirmSignUp();

        $this->flusher->flush();
    }
}
