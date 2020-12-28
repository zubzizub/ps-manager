<?php

declare(strict_types=1);

namespace App\Domain\Store\UseCase\Game\Create;

use App\Domain\Flusher;
use App\Domain\Store\Entity\Game\Game;
use App\Domain\Store\Entity\Game\Id;
use App\Domain\Store\Entity\Game\Price;
use App\Domain\Store\Repository\GameRepository;
use App\Domain\Store\Service\Ps\PsInterface;
use DateTimeImmutable;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use DomainException;

class Handler
{
    private PsInterface $parser;

    private GameRepository $repository;
    private Flusher $flusher;

    public function __construct(
        PsInterface $parser,
        GameRepository $repository,
        Flusher $flusher
    ) {
        $this->parser = $parser;
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
        $externalId = $command->externalId;

        if ($this->repository->hasByExternalId($externalId)) {
            throw new DomainException('Game already exists.');
        }

        $dataParser = $this->parser->getGameById($externalId);

        $game = new Game(
            Id::next(),
            $externalId,
            $dataParser->title,
            $dataParser->description,
            new Price($dataParser->price),
            new Price($dataParser->priceDiscount),
            $dataParser->getUrlImage(),
            $dataParser->discountEndDate,
            new DateTimeImmutable()
        );

        $this->repository->add($game);
        $this->flusher->flush();
    }
}
