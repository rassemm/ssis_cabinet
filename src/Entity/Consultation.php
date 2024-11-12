<?php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;  // Le nom a été changé ici, pour suivre les conventions PHP

    #[ORM\ManyToOne(targetEntity: Patient::class, inversedBy: 'consultations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Patient $patient = null;  // Idem ici, changer "Patient" en "patient"

    #[ORM\ManyToOne(targetEntity: Doctor::class, inversedBy: 'consultations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Doctor $doctor = null;  // Idem ici, changer "Doctor" en "doctor"

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;  // Correction ici : "Desciption" -> "description"

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getDoctor(): ?Doctor
    {
        return $this->doctor;
    }

    public function setDoctor(?Doctor $doctor): static
    {
        $this->doctor = $doctor;

        return $this;
    }

    public function getDescription(): ?string  // Changement ici : "getDesciption" -> "getDescription"
    {
        return $this->description;
    }

    public function setDescription(string $description): static  // Changement ici : "setDesciption" -> "setDescription"
    {
        $this->description = $description;

        return $this;
    }
}
