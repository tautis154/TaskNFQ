<?php

namespace App\Controller;

use App\Form\CustomerRegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param $request
     * @return RedirectResponse|Response
     */
    public function index($request)
    {
        $form = $this->createForm(CustomerRegistrationType::class);

        $form->handleRequest($request);

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}

