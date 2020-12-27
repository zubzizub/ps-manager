<?php

declare(strict_types=1);

namespace App\Components\Ps\StoreApi;

use App\Components\Ps\PsGame;
use App\Components\Ps\PsGamesCollection;
use App\Domain\Store\Service\Ps\PsGameInterface;
use App\Domain\Store\Service\Ps\PsGamesCollectionInterface;
use App\Domain\Store\Service\Ps\PsInterface;
use Games;

class Collection implements PsInterface
{
    public function getGameById(string $gameId): PsGameInterface
    {
        return new PsGame();
    }

    public function getAllGames(): PsGamesCollectionInterface
    {
        $freeToPlay = (new Games('ru', 'ru'))->freeToPlay(0, 100);

        $psGameCollection = new PsGamesCollection();
        /** @var  $game */
        foreach ($freeToPlay as $game) {
            $psGame = new PsGame();
            $psGame->id = $game->id;
            $psGame->title = $game->name;
            $psGame->price = $game->default_sku->price/100 ?? 0;
            $psGame->urlImage = $game->images[0]->url;
            $psGameCollection->add($psGame);
        }

        return $psGameCollection;
    }
}
