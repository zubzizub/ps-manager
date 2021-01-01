<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\SignUp\Request;

use App\Domain\User\Entity\Email;
use App\Domain\User\Entity\Id;
use App\Domain\User\Entity\User;
use App\Domain\User\FlusherInterface;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Service\ConfirmTokenizer;
use App\Domain\User\Service\ConfirmTokenSender;
use App\Domain\User\Service\PasswordHasher;
use DateTimeImmutable;
use DomainException;

class Handler
{
    private UserRepositoryInterface $userRepository;
    private FlusherInterface $flusher;
    private PasswordHasher $hasher;
    private ConfirmTokenizer $tokenizer;
    private ConfirmTokenSender $tokenSender;

    public function __construct(
        UserRepositoryInterface $userRepository,
        FlusherInterface $flusher,
        PasswordHasher $hasher,
        ConfirmTokenizer $tokenizer,
        ConfirmTokenSender $tokenSender
    ) {
        $this->userRepository = $userRepository;
        $this->flusher = $flusher;
        $this->hasher = $hasher;
        $this->tokenizer = $tokenizer;
        $this->tokenSender = $tokenSender;
    }

    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        if ($this->userRepository->hasByEmail($email)) {
            throw new DomainException('User already exists.');
        }

        $token = $this->tokenizer->generate();

        $user = new User(
            Id::next(),
            $email,
            $this->hasher->hash($command->password),
            $token,
            new DateTimeImmutable()
        );

        $this->userRepository->add($user);

        $this->tokenSender->send($email, $token);

        $this->flusher->flush();
    }
}
