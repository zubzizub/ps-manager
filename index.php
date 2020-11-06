<?php

use QL\QueryList;

require __DIR__ . '/vendor/autoload.php';

echo handle();


/**
 * @return string
 * @throws JsonException
 */
function handle(): string
{
	$query = QueryList::get('https://store.playstation.com/ru-ru/product/EP4529-CUSA04099_00-SNIPERELITE40000');
	$dataPrice = $query->find('span[data-qa=mfeCtaMain#offer0#finalPrice]')->texts();
	$dataPriceOriginal = $query->find('span[data-qa=mfeCtaMain#offer0#originalPrice]')->texts();
	$name = $query->find('h1[data-qa=mfe-game-title#name]')->texts();
	$price = (int)trim(str_replace('.', '', str_replace('RUB', '', $dataPrice[0])));
	$priceOriginal = (int)trim(str_replace('.', '', str_replace('RUB', '', $dataPriceOriginal[0])));

	//data-qa="mfeUpsell#productEdition0#ctaWithPrice#offer0#finalPrice"
	//data-qa="mfe-game-title#name"
	return json_encode(
        [
            'name' => $name[0],
            'price' => $price,
            'price original' => $priceOriginal,
            'discount' => $priceOriginal - $price,
            '% discount' => round(100 - ($price / $priceOriginal) * 100) . '%'
        ],
        JSON_THROW_ON_ERROR
    );
}


