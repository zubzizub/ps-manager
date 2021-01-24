<?php

declare(strict_types=1);

namespace App\Domain\Store\Service\Ps;

interface PsInterface
{
    public function getGameById(string $gameId): PsGameInterface;

    public function getAllGames(int $startPage): PsGamesCollectionInterface;
}
