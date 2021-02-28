<?php

declare(strict_types=1);

namespace App\Controller\Store;

use App\Domain\Store\UseCase\Game\Create\Command;
use App\Domain\Store\UseCase\Game\Create\Handler;
use App\Infrastructure\Store\Game\Forms\CreateForm;
use App\ReadModel\Store\Game\GameFetcher;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use DomainException;
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
        return $this->render('app/store/game/show.html.twig', compact('games'));
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

        $form = $this->createForm(CreateForm::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute('home');
            } catch (DomainException $exception) {
                $this->addFlash('error', $exception->getMessage());
            }
        }

        return $this->render('app/store/game/create.html.twig', ['form' => $form->createView()]);
    }
}
