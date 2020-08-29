<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CancelType;
use App\Form\RegistrationCodeType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationCodeController extends AbstractController
{
    /**
     * @Route("/registration/code", name="registration_code")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function index(Request $request)
    {
        $form = $this->createForm(RegistrationCodeType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('search', $form->getData());
        }

        return $this->render('registration_code/index.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route("/search", name="search")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws \Exception
     */
    public function results(Request $request, EntityManagerInterface $entityManager)
    {
        $var = $request->get('registrationCode');
        $recipes = $this->getDoctrine()->getRepository(Customer::class)
            ->findBy([
                'customerReservationCode' => $var
            ]);
        if (empty($recipes)) {
            $this->addFlash('warning', 'No such registration code exists, Try entering it again');
            return $this->redirectToRoute('registration_code');
        }

        $customerFirstName = $recipes[0]->getCustomerFirstName();
        $customerAppointmentTime = $recipes[0]->getAppointmentTime();

        $now = date('Y-m-d h:i:s', time());
        $x = new DateTime($now);
        $x = $x->diff($customerAppointmentTime);

        if (1 === $x->invert) {
            $customerTimeLeft = 0;
        }
        else{
        $customerTimeLeft = $x->format("%Y Years %D Days %H:%I.%S");
        }

        $form = $this->createForm(CancelType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cancelledCustomer = $this->getDoctrine()->getRepository(Customer::class)
                ->findOneBy([
                    'customerReservationCode' => $var
                ]);

            $entityManager->remove($cancelledCustomer);

            $entityManager->flush();

            $this->addFlash('success', 'Your registration is successfully cancelled');
            return $this->redirectToRoute('home');
        }
        return $this->render('registration_code/results.html.twig', [
            'form' => $form->createView(),
            'customerName' => $customerFirstName,
            'remainingTime' => $customerTimeLeft]);
    }
}
