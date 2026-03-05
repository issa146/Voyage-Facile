<?php

namespace App\Controller;

use App\Service\UnsplashService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class VoyageController extends AbstractController
{
    #[Route('/voyage', name: 'app_voyage')]
    public function index(Request $request, UnsplashService $unsplash): Response
    {
        

        $destination = trim((string) $request->query->get('destination', 'paris')); # je récupere les parmètere GET  

        $imageUrl = $unsplash->getImageUrl($destination) ?? ''; # j'appel mon service avec la destination

        $cards = [];
            for ($i = 1; $i <= 12; $i++) {
                $cards[] = [
                'id' => $i,
                'destination' => $destination,
                'formule' => 'basic',
                'prix' => 210,
                'image' => $imageUrl,
                ];
                
            }

            return $this->render('voyage/index.html.twig', [
            'destination' => $destination,
            'cards' => $cards

        ]);     

    }


    #[Route('/voyage/{destination}', name: 'voyage_detail', requirements:['destination' => '.+'])]
    public function detail(string $destination, UnsplashService $unsplash) {
        $destination = trim($destination);

        $imageUrl = $unsplash->getImageUrl($destination);

        
    }

}
