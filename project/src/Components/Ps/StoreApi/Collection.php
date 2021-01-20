<?php

declare(strict_types=1);

namespace App\Components\Ps\StoreApi;

use App\Components\Ps\PsGame;
use App\Components\Ps\PsGamesCollection;
use App\Domain\Store\Service\Ps\PsGameInterface;
use App\Domain\Store\Service\Ps\PsGamesCollectionInterface;
use App\Domain\Store\Service\Ps\PsInterface;
use DateTimeImmutable;
use Games;

class Collection implements PsInterface
{
    public function getGameById(string $gameId): PsGameInterface
    {
        $endpoint = sprintf(
            'https://store.playstation.com/store/api/chihiro/00_09_000/titlecontainer/ru/ru/999/%s',
            $gameId
        );

        $dataRequest = file_get_contents($endpoint);
        $game = json_decode($dataRequest, false, 512, JSON_THROW_ON_ERROR);

        $psGame = new PsGame();
        $reward = $game->default_sku->rewards[0];
        $psGame->id = $game->id;
        $psGame->title = $game->name;
        $psGame->description = $game->long_desc;
        $psGame->price = $game->default_sku->price/100 ?? 0;
        $psGame->priceDiscount = $this->getPriceDiscount($psGame->price, $reward->discount);
        $psGame->discountEndDate = new DateTimeImmutable($reward->campaigns[0]->end_date) ;
        $psGame->urlImage = $game->images[0]->url;

        return $psGame;
    }

    public function getAllGames(): PsGamesCollectionInterface
    {
        $freeToPlay = (new Games('ru', 'ru'))->freeToPlay(0, 100);

        $psGameCollection = new PsGamesCollection();
        /** @var PsGame $game */
        foreach ($freeToPlay as $game) {
            $psGame = new PsGame();
            $psGame->id = $game->id;
            $psGame->title = $game->title;
            $psGame->price = $game->default_sku->price/100 ?? 0;
//            $psGame->urlImage = $game->images[0]->url;
            $psGameCollection->add($psGame);
        }

        return $psGameCollection;
    }

    private function getPriceDiscount(int $price, int $percent): int
    {
        return $price - (int)(($price * $percent) / 100) ?? 0;
    }
}
