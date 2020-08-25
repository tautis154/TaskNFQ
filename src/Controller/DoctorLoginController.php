<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DoctorLoginController extends AbstractController
{
    /**
     * @Route("/doctor/login", name="doctor_login")
     */
    public function index()
    {
        return $this->render('doctor_login/index.html.twig', [
            'controller_name' => 'DoctorLoginController',
        ]);
    }
}
