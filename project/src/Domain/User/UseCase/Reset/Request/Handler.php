<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\Reset\Request;

use App\Domain\User\Entity\Email;
use App\Domain\User\FlusherInterface;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Service\ResetTokenizer;
use App\Domain\User\Service\ResetTokenSenderInterface;
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
