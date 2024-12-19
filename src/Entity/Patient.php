<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column]
    private ?int $age = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateOfBirth = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $medicalHistory = null;

    #[ORM\OneToMany(mappedBy: 'Patient', targetEntity: Consultation::class)]
    private iterable $consultations;

    #[ORM\OneToMany(targetEntity: Appointment::class, mappedBy: 'patient')]
    private Collection $patient;
    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
    public function __construct()
    {
        $this->consultations = new ArrayCollection();
        $this->patient = new ArrayCollection();
    }

    // Méthode pour accéder à l'ID du patient
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter pour le prénom
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    // Setter pour le prénom
    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    // Getter pour le nom de famille
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    // Setter pour le nom de famille
    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    // Getter pour le nom complet (prénom + nom de famille)
    public function getName(): ?string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    // Getter pour l'âge
    public function getAge(): ?int
    {
        return $this->age;
    }

    // Setter pour l'âge
    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    // Getter pour la date de naissance
    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    // Setter pour la date de naissance
    public function setDateOfBirth(\DateTimeInterface $dateOfBirth): static
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    // Getter pour l'historique médical
    public function getMedicalHistory(): ?string
    {
        return $this->medicalHistory;
    }

    // Setter pour l'historique médical
    public function setMedicalHistory(?string $medicalHistory): static
    {
        $this->medicalHistory = $medicalHistory;

        return $this;
    }

    // Getter pour les consultations du patient
    public function getConsultations(): Collection
    {
        return $this->consultations;
    }

    // Ajouter une consultation au patient
    public function addConsultation(Consultation $consultation): static
    {
        if (!$this->consultations->contains($consultation)) {
            $this->consultations->add($consultation);
            $consultation->setPatient($this);
        }

        return $this;
    }

    // Supprimer une consultation du patient
    public function removeConsultation(Consultation $consultation): static
    {
        if ($this->consultations->removeElement($consultation)) {
            if ($consultation->getPatient() === $this) {
                $consultation->setPatient(null);
            }
        }

        return $this;
    }

    // Méthode __toString pour afficher le nom complet du patient
    public function __toString(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * @return Collection<int, Appointment>
     */
    public function getPatient(): Collection
    {
        return $this->patient;
    }

    public function addPatient(Appointment $patient): static
    {
        if (!$this->patient->contains($patient)) {
            $this->patient->add($patient);
            $patient->setPatient($this);
        }

        return $this;
    }

    public function removePatient(Appointment $patient): static
    {
        if ($this->patient->removeElement($patient)) {
            // set the owning side to null (unless already changed)
            if ($patient->getPatient() === $this) {
                $patient->setPatient(null);
            }
        }

        return $this;
    }
}
