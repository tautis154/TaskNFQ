<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DoctorRepository")
 */
class Doctor
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $userName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $userPassword;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $doctorFirstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $doctorLastName;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Customer", mappedBy="fk_doctor")
     */
    private $customers;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getUserPassword(): ?string
    {
        return $this->userPassword;
    }

    public function setUserPassword(string $userPassword): self
    {
        $this->userPassword = $userPassword;

        return $this;
    }

    public function getDoctorFirstName(): ?string
    {
        return $this->doctorFirstName;
    }

    public function setDoctorFirstName(string $doctorFirstName): self
    {
        $this->doctorFirstName = $doctorFirstName;

        return $this;
    }

    public function getDoctorLastName(): ?string
    {
        return $this->doctorLastName;
    }

    public function setDoctorLastName(string $doctorLastName): self
    {
        $this->doctorLastName = $doctorLastName;

        return $this;
    }

    /**
     * @return Collection|Customer[]
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers[] = $customer;
            $customer->setFkDoctor($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customers->contains($customer)) {
            $this->customers->removeElement($customer);
            // set the owning side to null (unless already changed)
            if ($customer->getFkDoctor() === $this) {
                $customer->setFkDoctor(null);
            }
        }

        return $this;
    }
}
