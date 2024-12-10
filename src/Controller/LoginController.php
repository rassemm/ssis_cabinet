<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $roles = $this->getUser()->getRoles();

            if (in_array('ROLE_ADMIN', $roles, true)) {
                return $this->redirectToRoute('admin_path');
            } elseif (in_array('ROLE_USER', $roles, true)) {
                return $this->redirectToRoute('doctor_path'); 
            }
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
    #[Route('/admin', name: 'admin_path')]
public function adminDashboard(): Response
{
    return $this->render('admin/dashboard.html.twig');
}

#[Route('/doctor', name: 'doctor_path')]
public function doctorDashboard(): Response
{
    return $this->render('doctor/index.html.twig');
}

}
