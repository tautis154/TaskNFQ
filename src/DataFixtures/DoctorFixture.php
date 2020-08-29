<?php

namespace App\DataFixtures;

use App\Entity\Doctor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DoctorFixture extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $doctor = new Doctor();
        $doctor->setUsername('tom');

        $doctor->setPassword
        (
            $this->encoder->encodePassword($doctor, '123')
        );

        $doctor->setDoctorFirstName('Tadas');
        $doctor->setDoctorLastName('Blynas');

        // $product = new Product();
        // $manager->persist($product);
        $manager->persist($doctor);



        $doctor = new Doctor();
        $doctor->setUsername('linas');

        $doctor->setPassword
        (
            $this->encoder->encodePassword($doctor, '123')
        );

        $doctor->setDoctorFirstName('Tom');
        $doctor->setDoctorLastName('John');

        // $product = new Product();
        // $manager->persist($product);
        $manager->persist($doctor);
        $manager->flush();
    }
}
