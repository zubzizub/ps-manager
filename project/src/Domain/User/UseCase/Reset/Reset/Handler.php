<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\Reset\Reset;

use App\Domain\User\FlusherInterface;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Service\PasswordHasher;
use DateTimeImmutable;
use DomainException;

class Handler
{
    private UserRepositoryInterface $userRepository;
    private FlusherInterface $flusher;
    private PasswordHasher $hasher;

    public function __construct(
        UserRepositoryInterface $userRepository,
        FlusherInterface $flusher,
        PasswordHasher $hasher
    ) {
        $this->userRepository = $userRepository;
        $this->flusher = $flusher;
        $this->hasher = $hasher;
    }

    public function handle(Command $command): void
    {
        $user = $this->userRepository->findByResetToken($command->token);
        if ($user === null) {
            throw new DomainException('Incorrect or confirmed token.');
        }

        $user->passwordReset(
            $this->hasher->hash($command->password),
            new DateTimeImmutable()
        );

        $this->flusher->flush();
    }
}
