<?php

declare(strict_types=1);

namespace App\Components\Parser;

use QL\QueryList;

class Parser implements ParserInterface
{
    /**
     * @param string $gameId
     * @return ParserDto
     * @throws \JsonException
     */
    public function getGameById(string $gameId): ParserDto
    {
        $urlGame = "https://store.playstation.com/ru-ru/product/{$gameId}";
        $query = (new QueryList)->get($urlGame);
        return $this->findGameByAttr($query, $gameId);
    }

    public function getAllGames(): array
    {
        $urlGame = "https://store.playstation.com/ru-ru/category/4df8ce70-bb0a-41c0-a2d2-98a3781c0c78/1";
        $query = (new QueryList)->get($urlGame);
        $data = $query->find('a[class=ems-sdk-product-tile-link]')->attrs('data-telemetry-meta')->all();
        if (empty($data)) {
            throw new \DomainException('Page with games not founded.');
        }

        $games = [];
        foreach ($data as $key => $game) {
            $games[$key] = json_decode($game, true, 512, JSON_THROW_ON_ERROR);
            $games[$key]['price'] = $this->handlePrice($games[$key]['price']);
        }
        return $games;
    }

    public function findGameByAttr($query, string $gameId): ParserDto
    {
        $data = $query->find('button[data-qa=mfeCtaMain#cta#action]')->attrs('data-telemetry-meta')->first();

        if (empty($data)) {
            throw new \DomainException('Page with game not founded: ' . $gameId);
        }
        $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        $productDetail = $data['productDetail'][0];
        $sku = $productDetail['productId'];
        $productName = $productDetail['productName'];
        $productPriceDetail = $productDetail['productPriceDetail'][0];

        if (!isset($sku) || $sku !== $gameId) {
            throw new \DomainException('Page with game not founded: ' . $gameId);
        }

        $parseDto = new ParserDto();
        $parseDto->title = $productName;
        $parseDto->description = $productName;
        $parseDto->price = $this->handlePrice($productPriceDetail['originalPriceFormatted']) ?? 0;
        $parseDto->priceDiscount = $this->handlePrice($productPriceDetail['discountPriceFormatted']) ?? 0;
        $parseDto->version = 'ps4';
        $parseDto->discountEndDate = new \DateTimeImmutable();

        return $parseDto;
    }

    public function findByScript(QueryList $query, string $gameId): ParserDto
    {
        $data = $query->find('script[id=mfe-jsonld-tags]')->text();

        if (empty($data)) {
            throw new \DomainException('Page with game not founded: ' . $gameId);
        }
        $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);

        if (!isset($data['sku']) || $data['sku'] !== $gameId) {
            throw new \DomainException('Page with game not founded: ' . $gameId);
        }

        $parseDto = new ParserDto();
        $parseDto->title = $data['name'] ?? '';
        $parseDto->description = $data['description'] ?? '';
        $parseDto->price = $data['offers']['price'] ?? '';
        $parseDto->priceDiscount = $data['offers']['price'] ?? '';
        $parseDto->version = 'ps4';
        $parseDto->discountEndDate = new \DateTimeImmutable();

        return $parseDto;
    }

    private function handlePrice(string $price): int
    {
        return (int)trim(str_replace('.', '', str_replace('RUB', '', $price)));
    }
}
