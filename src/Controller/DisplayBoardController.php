<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\BoardControllerType;
use App\Repository\CustomerRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DisplayBoardController extends AbstractController
{
    /**
     * @Route("/display/board", name="display_board")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function index(Request $request)
    {
        $form = $this->createForm(BoardControllerType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('displayBoard', $form->getData());
        }

        return $this->render('display_board/index.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/displayBoard", name="displayBoard")
     * @param Request $request
     * @param CustomerRepository $customerRepository
     * @return Response
     */
    public function results(Request $request, CustomerRepository $customerRepository)
    {
        $var = $request->get('registrationCode');
        $recipes = $this->getDoctrine()->getRepository(Customer::class)
            ->findBy([
                'customerReservationCode' => $var
            ]);
        if (empty($recipes)) {
            $this->addFlash('warning', 'No such registration code exists, Try entering it again');
            return $this->redirectToRoute('displayBoard');
        }


        $customerInAppointmentId = $customerRepository->getCustomersInAppointmentId();

        $customersInAppointment = $this->getDoctrine()->getRepository(Customer::class)
            ->findBy([
                'id' => $customerInAppointmentId
            ]);
        $doctorFirstNames = array();
        foreach ($customersInAppointment as $customer) {
            $doctorFirstNames[] = ($customer->getFkDoctor()->getDoctorFirstName());
        }


        $upcomingCustomerAppointmentId = $customerRepository->getUpcomingCustomersAppointmentId();

        $upcomingCustomersAppointment = $this->getDoctrine()->getRepository(Customer::class)
            ->findBy(
                [
                'id' => $upcomingCustomerAppointmentId,
                ],
                [
                'appointmentTime' => 'ASC'
                ]
            );
    //    var_dump($upcomingCustomersAppointment);
        //die();
        $doctorFirstNamesUpcomingVisit = array();
        foreach ($upcomingCustomersAppointment as $customer) {
            $doctorFirstNamesUpcomingVisit[] = ($customer->getFkDoctor()->getDoctorFirstName());
        }
        $timeLeftForCustomer = array();
        foreach ($upcomingCustomersAppointment as $upcomingCustomer){

            $customerAppointmentTime = $upcomingCustomer->getAppointmentTime();

            $customerAppointmentTime =  $customerAppointmentTime->format('Y/m/d h:i:s');
            $now = new DateTime();
            $x = DateTime::createFromFormat('U', strtotime($customerAppointmentTime));

            // $customerRemainingTime = $now->diff($x)->format("%y years %m months, %d days, %h hours %i minutes and %s seconds");
            $interval =  $now->diff($x);
            $timeLeft = array();
            if ($interval->invert == 1) {
                $timeLeftForCustomer[] = '0';
            }
            else{
                $interval = array_filter((array) $now->diff($x));
                $spec = ['y' => 'years', 'm' => 'months', 'd' => 'days', 'h' => 'hours', 'i' => 'minutes', 's' => 'seconds' ];
                foreach ($spec as $key => $unit) {
                    if (array_key_exists($key, $interval)) {
                        $timeLeft[] = "{$interval[$key]} $unit";
                    }
                }

                if ((count($timeLeft)) > 1) {
                    $last = array_pop($timeLeft);
                    $timeLeft[] = "and $last";
                }

                $timeLeft =  implode(' ', $timeLeft);

                $timeLeftForCustomer[] = $timeLeft;
            }



            //Padaryt current time diff pries uzregistruota laika
            //Jei <nei current tai tada padaryt, kad butu = 0
            //tada setLeftTime() =>
            //ta persistent flush td
        }
     //   foreach ($timeLeftForCustomer as $dab){
         //  var_dump($dab);
        //}
       // die();


        // var_dump($customerInAppointmentId);
       // var_dump($customersInAppointment);
     //  die();
        //Padaryt current kas yra eilej tai twige padaryt if not null tada rodyt ir else
        //Tai kad parasytu No one is at the doctor visit now
        //Tada padaryt kad rodytu tik 5 vizitus tai if padaryt paziuret ar nera null
        //Jei null tai turi parasyt, kad
        //Padaryt, kad daktaras galetu ieit i ta if user
        $customerRemainingTime = "Variantas";
        //Padaryt taip, kad zmogus galetu matyt kada uzsiregistravo ir kiek valandu jam liko iki jo atejimo

        //IR pakeist kur zmogui atskirai ismeta kad rodytu kiek jam liko valandu (unlimited) ir minuciu min(60) ir sec(60) min
        return $this->render('display_board/board.html.twig', [
            'customers' => $customersInAppointment,
            'doctorFirstNames' => $doctorFirstNames,
            'upcomingCustomers' => $upcomingCustomersAppointment,
            'doctorFirstNamesUpcomingVisit' => $doctorFirstNamesUpcomingVisit,
            'timeLeftForCustomers' => $timeLeftForCustomer]);
    }
}
