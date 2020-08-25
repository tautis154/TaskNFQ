<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 */
class Customer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $customerFirstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $customerReservationCode;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Doctor", inversedBy="customers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fk_doctor;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isInAppointment;

    /**
     * @ORM\Column(type="boolean")
     */
    private $appointmentIsFinished;

    /**
     * @ORM\Column(type="datetime")
     */
    private $appointmentTime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomerFirstName(): ?string
    {
        return $this->customerFirstName;
    }

    public function setCustomerFirstName(string $customerFirstName): self
    {
        $this->customerFirstName = $customerFirstName;

        return $this;
    }

    public function getCustomerReservationCode(): ?string
    {
        return $this->customerReservationCode;
    }

    public function setCustomerReservationCode(string $customerReservationCode): self
    {
        $this->customerReservationCode = $customerReservationCode;

        return $this;
    }

    public function getFkDoctor(): ?Doctor
    {
        return $this->fk_doctor;
    }

    public function setFkDoctor(?Doctor $fk_doctor): self
    {
        $this->fk_doctor = $fk_doctor;

        return $this;
    }

    public function getIsInAppointment(): ?bool
    {
        return $this->isInAppointment;
    }

    public function setIsInAppointment(bool $isInAppointment): self
    {
        $this->isInAppointment = $isInAppointment;

        return $this;
    }

    public function getAppointmentIsFinished(): ?bool
    {
        return $this->appointmentIsFinished;
    }

    public function setAppointmentIsFinished(bool $appointmentIsFinished): self
    {
        $this->appointmentIsFinished = $appointmentIsFinished;

        return $this;
    }

    public function getAppointmentTime(): ?\DateTimeInterface
    {
        return $this->appointmentTime;
    }

    public function setAppointmentTime(\DateTimeInterface $appointmentTime): self
    {
        $this->appointmentTime = $appointmentTime;

        return $this;
    }
}
