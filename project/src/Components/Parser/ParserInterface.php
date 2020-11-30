<?php

declare(strict_types=1);

namespace App\Components\Parser;

interface ParserInterface
{
    public function getGameById(string $gameId): ParserDto;

    public function getAllGames(): array;
}
