<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Doctor;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class HomePageController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function index(Request $request, EntityManagerInterface $entityManager)
    {


        $choices = array();

        $doctors = $this->getDoctrine()->getRepository(Doctor::class)->findAll();

        foreach ($doctors as $specialty) {
            $choices += array($specialty->getDoctorFirstName() => $specialty->getId());
        }

        $form = $this->createFormBuilder([])

            ->add('firstName', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your name',
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'Name is too short',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                        'maxMessage' => 'Name is too long',
                    ])
                ]
            ])
            ->add('doctors', ChoiceType::class, [
                'placeholder' => 'Select a doctor',
                'choices' => $choices,
                'required' => false,
            ])
            ->add('selectedTime', DateTimeType::class, [
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute'
                ]
            ])
            ->add('search', SubmitType::class, ['label' => 'Register'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $customer = new Customer();
            $length = 20;
            $bytes = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, $length);
           // var_dump($bytes);

            $customer->setCustomerFirstName(ucfirst(trim($form['firstName']->getData())));
            $customer->setCustomerReservationCode($bytes);
           // var_dump($bytes);
            $doctor_id = $form['doctors']->getData();
            //var_dump($bytes);
            //die();
            $post = $this->getDoctrine()->getRepository(Doctor::class)->find($doctor_id);
            $customer->setFkDoctor($post);
            $customer->setAppointmentTime($form['selectedTime']->getData());
            $customer->setAppointmentIsFinished(0);
            $customer->setIsInAppointment(0);
            $entityManager->persist($customer);

            $entityManager->flush();
            $this->addFlash('success', 'This is your Registration code -    ' . $bytes . ' - Please save it immediately');
            return $this->redirect($this->generateUrl('home'));
        }

        return $this->render('home/index.html.twig', ['form' => $form->createView()]);
    }
}
