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
     * @return Response
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
        date_default_timezone_set('UTC');
        $date = date('Y/m/d h:i:s', time());
   // var_dump($date);
        $customerFirstName = $recipes[0]->getCustomerFirstName();
        $customerAppointmentTime = $recipes[0]->getAppointmentTime();
      //  var_dump($customerAppointmentTime);
       // $date2 = strtotime($customerAppointmentTime['datetimefield']);
       // echo $customerFirstName;
      //  echo date('Y-m-d H:i:s', $date2);
        $customerAppointmentTime =  $customerAppointmentTime->format('Y/m/d h:i:s');

        $now = new DateTime();
        $x = DateTime::createFromFormat('U', strtotime($customerAppointmentTime));
       // $diff = $now->diff($x);

// Function call to find date difference
       // $dateDiff = date_diff($customerAppointmentTime, $date);
       // var_dump($dateDiff);
        $customerRemainingTime = $now->diff($x)->format("%y years %m months, %d days, %h hours %i minutes and %s seconds");


// Display the result
        //  printf("Difference between two dates: "
        //      . $dateDiff . " Days ");
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
            'remainingTime' => $customerRemainingTime]);
    }
}
