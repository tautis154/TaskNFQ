<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class BoardControllerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('registrationCode', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your registration code',
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'Registration code is too short',
                        // max length allowed by Symfony for security reasons
                        'max' => 210,
                        'maxMessage' => 'Registration code  is too long',
                    ])
                ]
            ])
            ->add('search', SubmitType::class, ['label' => 'Search'])
        ;
    }

}
