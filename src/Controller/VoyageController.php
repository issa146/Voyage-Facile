<?php

namespace App\Controller;

use App\Repository\VoyageRepository;
use App\Service\UnsplashService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class VoyageController extends AbstractController
{
    #[Route('/voyage', name: 'app_voyage')]
    public function index(Request $request, VoyageRepository $voyageRepository, UnsplashService $unsplash, EntityManagerInterface $entityManager): Response
    {
        

        $destination = trim((string) $request->query->get('destination', '')); # je récupere les parmètere GET  

        // $imageUrl = $unsplash->getImageUrl($destination) ?? ''; # j'appel mon service avec la destination

        # si la bar de recherch n'est pas vide
        if($destination !== '') {
            $voyages = $voyageRepository->createQueryBuilder('v')  
            ->where('LOWER(v.destination) LIKE :destination') # je utilise la fonction LOWER pour metre tous en minuscules coté bdd et LIKE pour
            ->setParameter('destination', '%' . mb_strtolower($destination) . '%' ) # je donne une valeur au paramètre :destination pour protège contre les injections SQL
            ->orderBy('v.destination', 'ASC') 
            ->getQuery()
            ->getResult();
        } else {
            $voyages = $voyageRepository->findBy([], ['destination' => 'ASC']);
        }


        $modification = false; # je initialises un flag pour savoir si j'ai modifié au moins un voyage

        foreach ($voyages as $voyage) {
            if(!$voyage->getImageUrl()) {
                $imageUrl = $unsplash->getImageUrl($voyage->getDestination());

                if($imageUrl) {
                    $voyage->setImageUrl($imageUrl);
                    $modification = true;
                }
            }
        }

        if($modification) {
            $entityManager->flush();
        }

            return $this->render('voyage/index.html.twig', [
            'destination' => $destination,
            'vo$voyages' =>$voyages   
        ]);     

    }


    #[Route('/voyage/{destination}', name: 'voyage_detail', requirements:['destination' => '.+'])]
    public function detail(string $destination, VoyageRepository $voyageRepository) {
        

    $voyage = $voyageRepository->findOneBy(['destination' => $destination]);

    if (!$voyage) {
        throw $this->createNotFoundException('Voyage introuvable');
    }

        return $this->render('voyage/detail.html.twig', [
            'voyage' => $voyage,
        ]); 
            
            
        

        



        
    }

}
