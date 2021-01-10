<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\SignUpByEmail\Request;

use App\Domain\User\Entity\User\Email;
use App\Domain\User\Entity\User\Id;
use App\Domain\User\Entity\User\User;
use App\Domain\User\FlusherInterface;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Service\ConfirmTokenSender;
use App\Domain\User\Service\PasswordHasher;
use App\Domain\User\Service\SignUpConfirmTokenizer;
use DateTimeImmutable;
use DomainException;

class Handler
{
    private UserRepositoryInterface $userRepository;
    private FlusherInterface $flusher;
    private PasswordHasher $hasher;
    private SignUpConfirmTokenizer $tokenizer;
    private ConfirmTokenSender $tokenSender;

    public function __construct(
        UserRepositoryInterface $userRepository,
        FlusherInterface $flusher,
        PasswordHasher $hasher,
        SignUpConfirmTokenizer $tokenizer,
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

        $user = User::signUpByEmail(
            Id::next(),
            new DateTimeImmutable(),
            $email,
            $this->hasher->hash($command->password),
            $token,
        );

        $this->userRepository->add($user);

        $this->tokenSender->send($email, $token);

        $this->flusher->flush();
    }
}
