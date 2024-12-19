<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Consultation;
use App\Form\AppointmentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentController extends AbstractController
{
    #[Route('/appointment/new', name: 'appointment_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $appointment = new Appointment();

        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $appointment->setStatus('Pending');
            $em->persist($appointment);
            $em->flush();

            $this->addFlash('success', 'Rendez-vous créé avec succès.');

            return $this->redirectToRoute('appointment_new');
        }

        return $this->render('appointment/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/appointment', name: 'appointment_index')]
    public function index(EntityManagerInterface $em): Response
    {
        $appointments = $em->getRepository(Appointment::class)->findAll();

        return $this->render('appointment/index.html.twig', [
            'appointments' => $appointments,
        ]);
    }

    #[Route('/appointment/delete/{id}', name: 'appointment_delete', methods: ['POST'])]
    public function delete(Request $request, Appointment $appointment, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$appointment->getId(), $request->request->get('_token'))) {
            $em->remove($appointment);
            $em->flush();

            $this->addFlash('success', 'Rendez-vous supprimé avec succès.');
        }

        return $this->redirectToRoute('appointment_index');
    }

    #[Route('/appointment/status/{id}', name: 'appointment_status', methods: ['POST'])]
    public function changeStatus(Appointment $appointment, EntityManagerInterface $em): Response
    {
        if ($appointment->getStatus() === 'Pending') {
            $appointment->setStatus('Approved');
            $consultation = new Consultation();
            $consultation->setDate(new \DateTime());
            $consultation->setPatient($appointment->getPatient());
            $consultation->setDoctor($appointment->getDoctor());
            $consultation->setAppointment($appointment);
            $em->persist($consultation);
        } else {
            $appointment->setStatus('Pending');
        }

        $em->flush();
        $this->addFlash('success', 'Statut du rendez-vous mis à jour et consultation créée.');
        return $this->redirectToRoute('appointment_index');
    }

}
