<?php

declare(strict_types=1);

namespace App\Domain\Auth\UseCase\SignUpByNetwork\Request;

use App\Domain\Auth\Entity\User\Id;
use App\Domain\Auth\Entity\User\User;
use App\Domain\FlusherInterface;
use App\Domain\Auth\Repository\UserRepositoryInterface;
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
            throw new DomainException('Auth already exists.');
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
