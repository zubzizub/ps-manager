<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\SignUpByNetwork\Request;

use App\Domain\User\Entity\Id;
use App\Domain\User\Entity\User;
use App\Domain\User\FlusherInterface;
use App\Domain\User\Repository\UserRepositoryInterface;
use DateTimeImmutable;
use DomainException;

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
        if ($this->userRepository->hasByNetworkIdentity($command->network, $command->identity)) {
            throw new DomainException('User already exists.');
        }

        $user = User::signUpByNetwork(
            Id::next(),
            new DateTimeImmutable(),
            $command->network,
            $command->identity
        );

        $this->userRepository->add($user);

        $this->flusher->flush();
    }
}
