<?php

namespace App\Model\Parser\Repository;

use App\Model\Parser\Entity\Game\Game;
use App\Model\Parser\Entity\Game\Id;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class GameRepository
{
    /**
     * @var EntityRepository
     */
    private $repo;
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(Game::class);
        $this->em = $em;
    }

    /**
     * @param Id $id
     * @return Game
     * @throws EntityNotFoundException
     */
    public function get(Id $id): Game
    {
        /** @var Game $game */
        if (!$game = $this->repo->find($id->getId())) {
            throw new EntityNotFoundException('Game is not found.');
        }
        return $game;
    }

    /**
     * @param $externalId
     * @return Game
     * @throws EntityNotFoundException
     */
    public function getByExternalId($externalId): Game
    {
        /** @var Game $game */
        if (!$game = $this->repo->findOneBy(['externalId' => $externalId])) {
            throw new EntityNotFoundException('Game is not found.');
        }
        return $game;
    }

    /**
     * @param $externalId
     * @return bool
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function hasByExternalId($externalId): bool
    {
        $countGames = $this->repo->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->andWhere('t.externalId = :externalId')
            ->setParameter(':externalId', $externalId)
            ->getQuery()
            ->getSingleScalarResult();

        return $countGames > 0;
    }

    public function add(Game $game): void
    {
        $this->em->persist($game);
    }

    public function remove(Game $game): void
    {
        $this->em->remove($game);
    }
}
