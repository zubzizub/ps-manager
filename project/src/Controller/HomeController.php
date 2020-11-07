<?php

namespace App\Controller;

use QL\QueryList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     * @return Response
     */
    public function index(): Response
    {
        $query = (new QueryList)->get('https://store.playstation.com/ru-ru/product/EP4529-CUSA04099_00-SNIPERELITE40000');
        $dataPrice = $query->find('span[data-qa=mfeCtaMain#offer0#finalPrice]')->texts();
        $dataPriceOriginal = $query->find('span[data-qa=mfeCtaMain#offer0#originalPrice]')->texts();
        $name = $query->find('h1[data-qa=mfe-game-title#name]')->texts();
        $price = (int)trim(str_replace('.', '', str_replace('RUB', '', $dataPrice[0])));
        $priceOriginal = (int)trim(str_replace('.', '', str_replace('RUB', '', $dataPriceOriginal[0])));

        return $this->json(
            [
                'name' => $name[0],
                'price' => $price,
                'price original' => $priceOriginal,
                'discount' => $priceOriginal - $price,
                '% discount' => round(100 - ($price / $priceOriginal) * 100) . '%'
            ]
        );
    }
}
