<?php

namespace App\Controller;

use App\Entity\Voyage;
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
            $voyages = $voyageRepository->createQueryBuilder('v')  # je construie une requet pour l'entité voyage
            ->where('LOWER(v.destination) LIKE :destination')   # je utilise la fonction LOWER pour metre tous en minuscules coté bdd et LIKE pour rechercher exactement la destination
            ->setParameter('destination', '%' . mb_strtolower($destination) . '%' )     # je donne une valeur au paramètre :destination pour protège contre les injections SQL
            ->orderBy('v.destination', 'ASC')   # je fait un tri sur la colonne destination de de l'entité v
            ->getQuery()     # je prépare le QueryBuilde requet à ètre excuté 
            ->getResult();   # j'exécutes la requête sous forme de tableau d'objet
        } else {
            $voyages = $voyageRepository->findBy([], ['destination' => 'ASC']); # je récupère tous les voyage
        }


        $modification = false; # je initialises un flag pour savoir si j'ai modifié au moins un voyage

        foreach ($voyages as $voyage) {
            if(!$voyage->getImageUrl()) { # je vérifie si le voyage n'a aucune image en bdd
                $imageUrl = $unsplash->getImageUrl($voyage->getDestination()); # j'appelles mon service et je lui passe la destination 

                if($imageUrl) { # je vérifie si $imageUrl n'est pas vide 
                    $voyage->setImageUrl($imageUrl); # j'appelle le setter et je lui passe l'image
                    $modification = true; # j'indique que au moins un voyage a été modifié
                }
            }
        }

        if($modification) { # je vérifi si est $modification true 
            $entityManager->flush(); # je envoie toutes les modif en bdd
        }

            return $this->render('voyage/index.html.twig', [
            'destination' => $destination,
            'voyages' =>$voyages   
        ]);     

    }


    #[Route('/voyage/{id}', name: 'voyage_detail', requirements:['id' => '\d+'])]
    public function detail(Voyage $voyage): Response {
 
        return $this->render('voyage/detail.html.twig', [
            'voyage' => $voyage,
        ]); 
            

        
    }

}
