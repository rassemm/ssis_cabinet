<?php
// src/Controller/HomeController.php

namespace App\Controller;
use App\Entity\Doctor;
use App\Repository\AppointmentRepository;
use App\Entity\Patient;
use App\Entity\Appointment;
use App\Repository\DoctorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/Home', name: 'homepage')]
    public function index(
        DoctorRepository $doctorRepository,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $doctors = $doctorRepository->findAll();

        $form = $this->createFormBuilder()
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('age', IntegerType::class, [
                'label' => 'Âge',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateOfBirth', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de Naissance',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('medicalHistory', TextType::class, [
                'label' => 'Antécédents Médicaux',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            // Champs Appointment
            ->add('appointmentDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date du Rendez-vous',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('doctor', EntityType::class, [
                'class' => 'App\Entity\Doctor',
                'choice_label' => 'name',
                'label' => 'Docteur',
                'attr' => ['class' => 'form-control'],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Création des entités Patient et Appointment
            $patient = new Patient();
            $patient->setFirstName($data['firstName']);
            $patient->setLastName($data['lastName']);
            $patient->setAge($data['age']);
            $patient->setDateOfBirth($data['dateOfBirth']);
            $patient->setMedicalHistory($data['medicalHistory']);

            $appointment = new Appointment();
            $appointment->setDate($data['appointmentDate']);
            $appointment->setDoctor($data['doctor']);
            $appointment->setPatient($patient);
            $appointment->setStatus('En attente');

            // Enregistrer dans la base de données
            $em->persist($patient);
            $em->persist($appointment);
            $em->flush();

            $this->addFlash('success', 'Rendez-vous créé avec succès.');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('home/index.html.twig', [
            'doctors' => $doctors,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/doctor/{id}', name: 'appointments')]
    public function doctorAppointments(Doctor $doctor, AppointmentRepository $appointmentRepository): Response
    {
        $today = new \DateTime();
        $appointments = $appointmentRepository->findByDoctorAndDate($doctor, $today);
    
        return $this->render('home/index.html.twig', [
            'doctor' => $doctor,
            'appointments' => $appointments,
        ]);
    }
}
