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

        $doctorFirstNamesUpcomingVisit = array();
        foreach ($upcomingCustomersAppointment as $customer) {
            $doctorFirstNamesUpcomingVisit[] = ($customer->getFkDoctor()->getDoctorFirstName());
        }
        $timeLeftForCustomer = array();



        foreach ($upcomingCustomersAppointment as $upcomingCustomer) {
            $customerAppointmentTime = $upcomingCustomer->getAppointmentTime();

            $now = date('Y-m-d h:i:s', time());
            $x = new DateTime($now);
            $x = $x->diff($customerAppointmentTime);

            if (1 === $x->invert) {
                $timeLeftForCustomer[] = 0;
            } else {
                $timeLeftForCustomer[] = $x->format("%Y Years %D Days %H:%I.%S");
            }
        }
        return $this->render('display_board/board.html.twig', [
            'customers' => $customersInAppointment,
            'doctorFirstNames' => $doctorFirstNames,
            'upcomingCustomers' => $upcomingCustomersAppointment,
            'doctorFirstNamesUpcomingVisit' => $doctorFirstNamesUpcomingVisit,
            'timeLeftForCustomers' => $timeLeftForCustomer]);
    }
}
