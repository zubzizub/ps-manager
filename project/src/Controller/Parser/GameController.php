<?php

declare(strict_types=1);

namespace App\Controller\Parser;

use App\Model\Flusher;
use App\Model\Parser\Entity\Game\Price;
use App\Model\Parser\Repository\GameRepository;
use App\Components\Parser\ParserInterface;
use App\Model\Parser\UseCase\Game\Create\Command;
use App\Model\Parser\UseCase\Game\Create\Form;
use App\Model\Parser\UseCase\Game\Create\Handler;
use App\ReadModel\Parser\Game\GameFetcher;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param GameFetcher $fetcher
     * @return Response
     * @throws Exception
     */
    public function index(GameFetcher $fetcher): Response
    {
        $games = $fetcher->all();
        return $this->render('app/parser/game/show.html.twig', compact('games'));
    }

    /**
     * @Route("/game/create", name="game-create")
     * @param Request $request
     * @param Handler $handler
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function create(Request $request, Handler $handler): Response
    {
        $command = new Command();

        $form = $this->createForm(Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute('home');
            } catch (\DomainException $exception) {
                $this->addFlash('error', $exception->getMessage());
            }
        }

        return $this->render('app/parser/game/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/game/update-all", name="gameUpdateAll", methods={"POST"})
     *
     * @param GameFetcher $fetcher
     * @param GameRepository $repository
     * @param ParserInterface $parser
     * @param Flusher $flusher
     * @return Response
     * @throws Exception
     * @throws \Doctrine\ORM\EntityNotFoundException
     */
    public function updateAll(
        GameFetcher $fetcher,
        GameRepository $repository,
        ParserInterface $parser,
        Flusher $flusher
    ): Response
    {
        $games = $fetcher->allIds();

        foreach ($games as $key => $externalId) {
            $game = $repository->getByExternalId($externalId);

            $dataParser = $parser->getGameById($externalId);
            $game->setPrice(new Price($dataParser->price));
            $game->setPriceDiscount(new Price($dataParser->priceDiscount));
            $repository->add($game);
        }

        $flusher->flush();

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/game/show-all", name="showAllGames")
     *
     * @param ParserInterface $parser
     * @return Response
     */
    public function showAll(ParserInterface $parser): Response
    {
        $games = $parser->getAllGames();
        return $this->render('app/parser/game/show-all.html.twig', compact('games'));
    }
}
