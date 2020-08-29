<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerAdmissionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CustomerManagingController extends AbstractController
{
    /**
     * @Route("/customer/managing", name="customer_managing")
     */
    public function index()
    {
        if (!$this->getUser()) {
            $this->addFlash('danger', 'Please log in');
            return $this->redirectToRoute('login');
        }

        $user = $this->getUser();

        $customers = $user->getCustomers();
        $customerInAppointmentId = null;
        $atleastOneAppointedCustomer = 0;

        foreach ($customers as $customer) {
            if ($customer->getIsInAppointment() == '1') {
                $customerInAppointmentId = $customer->getId();
                $atleastOneAppointedCustomer = 1;
                break;
            }
        }

        return $this->render('customer_managing/index.html.twig', [
            'customers' => $customers,
            'doctor' => $user,
            'customerIsInAppointmentId' => $customerInAppointmentId,
            'atleastOneAppointedCustomer' => $atleastOneAppointedCustomer]);
    }

    /**
     * @Route("/customer/delete/{id}", name="customer_delete", methods={"DELETE"})
     */
    public function delete($id)
    {
        $customer = $this->getDoctrine()->getRepository(Customer::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($customer);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }

    /**
     * @Route("/customer/updateAppointment/{id}", name="customer_update", methods={"UPDATE"})
     */
    public function updateAppointment($id)
    {
        $customer = $this->getDoctrine()->getRepository(Customer::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $customer->setIsInAppointment('1');
        $entityManager->persist($customer);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }

    /**
     * @Route("/customer/endAppointment/{id}", name="customer_end", methods={"END"})
     */
    public function endAppointment($id)
    {
        $customer = $this->getDoctrine()->getRepository(Customer::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $customer->setIsInAppointment('0');
        $customer->setAppointmentIsFinished('1');
        $entityManager->persist($customer);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }
}
