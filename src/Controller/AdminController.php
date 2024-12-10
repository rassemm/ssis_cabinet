<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Repository\PatientRepository;
use App\Repository\DoctorRepository;
use App\Repository\ConsultationRepository;
use App\Repository\UserRepository;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    #[IsGranted('ROLE_ADMIN')]
    public function adminDashboard(
        PatientRepository $patientRepository,
        DoctorRepository $doctorRepository,
        ConsultationRepository $consultationRepository,
        UserRepository $userRepository
    ): Response
    {
        $numPatients = $patientRepository->count([]);
        $numDoctors = $doctorRepository->count([]);
        $numConsultations = $consultationRepository->count([]);
        $consultationsByDoctor = $consultationRepository->findConsultationsByDoctor();
        $usersByRoleUser = $userRepository->countUsersByRole('ROLE_USER');
        $usersByRoleAdmin = $userRepository->countUsersByRole('ROLE_ADMIN');
        $consultationsByPatient = $consultationRepository->findConsultationsByPatient();
        $numUsers = $userRepository->countUsers(); 
    
        return $this->render('admin/dashboard.html.twig', [
            'numPatients' => $numPatients,
            'numDoctors' => $numDoctors,
            'numConsultations' => $numConsultations,
            'numUsers' => $numUsers,
            'consultationsByDoctor' => $consultationsByDoctor,
            'consultationsByPatient' => $consultationsByPatient,
            'usersByRoleUser' => $usersByRoleUser,
            'usersByRoleAdmin' => $usersByRoleAdmin,
        ]);
    }
}
