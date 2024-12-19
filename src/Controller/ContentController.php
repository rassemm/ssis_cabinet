<?php

namespace App\Controller;

use App\Entity\Doctor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContentController extends AbstractController
{
    #[Route('/content', name: 'content_management')]
    public function index(): Response
    {
        // Statique : Contenu du carrousel et de la section "À propos"
        $carouselItems = [
            [
                'image' => 'https://images.unsplash.com/photo-1576092766420-2f9c47b33f88',
                'alt' => 'Cabinet médical 1',
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1551076805-e1869033e561',
                'alt' => 'Cabinet médical 2',
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1584466977773-c9dc5d6c02de',
                'alt' => 'Salle d\'attente',
            ],
        ];

        $aboutContent = [
            'title' => 'À propos de notre cabinet',
            'paragraph' => 'Bienvenue dans notre cabinet médical, où nous offrons des soins professionnels et attentionnés à tous nos patients.',
        ];

        $footerLinks = [
            'Facebook' => '#',
            'Twitter' => '#',
            'Instagram' => '#',
            'Email' => '#',
        ];

        return $this->render('content/index.html.twig', [
            'carouselItems' => $carouselItems,
            'aboutContent' => $aboutContent,
            'footerLinks' => $footerLinks,
        ]);
    }

    
}
