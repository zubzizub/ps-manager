<?php

declare(strict_types=1);

namespace App\Domain\Auth\UseCase\Reset\Request;

use App\Domain\Auth\Entity\User\Email;
use App\Domain\FlusherInterface;
use App\Domain\Auth\Repository\UserRepositoryInterface;
use App\Domain\Auth\Service\ResetTokenizer;
use App\Domain\Auth\Service\ResetTokenSenderInterface;
use DateTimeImmutable;

class Handler
{
    private UserRepositoryInterface $userRepository;
    private FlusherInterface $flusher;
    private ResetTokenizer $tokenizer;
    private ResetTokenSenderInterface $sender;

    public function handle(Command $command): void
    {
        $user = $this->userRepository->getByEmail(new Email($command->email));

        $user->requestPasswordReset(
            $this->tokenizer->generate(),
            new DateTimeImmutable()
        );

        $this->flusher->flush();

        $this->sender->send($user->getEmail(), $user->getResetToken());
    }
}
