<?php

declare(strict_types=1);

namespace App\Domain\Store\UseCase\Game\Create;

use App\Domain\Store\Entity\Game\Game;
use App\Domain\Store\Entity\Game\Id;
use App\Domain\Store\Entity\Game\Price;
use App\Domain\Store\FlusherInterface;
use App\Domain\Store\Repository\GameRepository;
use DateTimeImmutable;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use DomainException;

class Handler
{
    private GameRepository $repository;
    private FlusherInterface $flusher;

    public function __construct(GameRepository $repository, FlusherInterface $flusher)
    {
        $this->repository = $repository;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function handle(Command $command): void
    {
        if ($this->repository->hasByExternalId($command->externalId)) {
            throw new DomainException('Game already exists.');
        }

        $game = new Game(
            Id::next(),
            $command->externalId,
            $command->title,
            $command->description,
            new Price($command->price),
            new Price($command->lowerPrice),
            $command->imageUrl,
            $command->discountEndDate,
            new DateTimeImmutable()
        );

        $this->repository->add($game);
        $this->flusher->flush();
    }
}
