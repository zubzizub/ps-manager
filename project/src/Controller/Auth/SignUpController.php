<?php

namespace App\Controller\Auth;

use App\Domain\Auth\UseCase\SignUpByEmail\Request\Command;
use App\Domain\Auth\UseCase\SignUpByEmail\Request\Handler;
use App\Infrastructure\User\Forms\SignUpForm;
use DomainException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignUpController extends AbstractController
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route ("/signup", name="auth.signup")
     * @param Request $request
     * @param Handler $handler
     * @return Response
     */
    public function request(Request $request, Handler $handler): Response
    {
        $command = new Command();

        $form = $this->createForm(SignUpForm::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Check your email.');
                return $this->redirectToRoute('home');
            } catch (DomainException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/auth/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
