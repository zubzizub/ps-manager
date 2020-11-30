<?php

declare(strict_types=1);

namespace App\Model\Parser\UseCase\Game\Create;

use App\Model\Flusher;
use App\Model\Parser\Entity\Game\Game;
use App\Model\Parser\Entity\Game\Id;
use App\Model\Parser\Entity\Game\Price;
use App\Model\Parser\Repository\GameRepository;
use App\Components\Parser\ParserInterface;
use DateTimeImmutable;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class Handler
{
    private ParserInterface $parser;

    private GameRepository $repository;
    private Flusher $flusher;

    public function __construct(
        ParserInterface $parser,
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
            throw new \DomainException('Game already exists.');
        }

        $dataParser = $this->parser->getGameById($externalId);

        $game = new Game(
            Id::next(),
            $externalId,
            $dataParser->title,
            $dataParser->description,
            new Price($dataParser->price),
            new Price($dataParser->priceDiscount),
            null,
            $dataParser->discountEndDate,
            new DateTimeImmutable()
        );

        $this->repository->add($game);
        $this->flusher->flush();
    }
}
