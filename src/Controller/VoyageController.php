<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Voyage;
use App\Repository\VoyageRepository;
use App\Service\GeoapifyService;
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
        $user = $this->getUser(); # je récupère l'utilisateur conncté

        if(!$user instanceof User) { # vérifier que l'utilisateur est connecté
            return $this->redirectToRoute('app_login');  
        }

        $formule = $user->getFormule(); # je récupère la formule de l'utilisateur

        if(!$formule){ # je vérife si l'utilisateur a une formule
            return $this->redirectToRoute('app_formule'); 
        }

        $destination = trim((string) $request->query->get('destination', '')); # je récupere les parmètere GET  

         $queryBuilder = $voyageRepository->createQueryBuilder('v')  # je construie une requet pour l'entité voyage
            ->where('v.formule = :formule')           # j'ajoute une condition where avec un paramètre nommé pour ce protège contre les injections SQL
            ->setParameter('formule', $formule )     # je donne une valeur au paramètre :formule 
            ->orderBy('v.destination', 'ASC');      # je fait un tri sur la colonne destination de de l'entité v

        # si la bar de recherch n'est pas vide
        if($destination !== '') {
            $queryBuilder
                ->andWhere('LOWER(v.destination) LIKE :destination')     # avec LOWER je mes tous en minscule en BDD  
                ->setParameter('destination', '%' . mb_strtolower($destination) . '%');  # je passe la valeur et je fait une recherche partous dans la chaine
              
        } 


        $voyages = $queryBuilder
        ->getQuery() # je compile la requête
        ->getResult(); # j'excute la requête


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
    public function detail(Voyage $voyage, GeoapifyService $geoapifyService): Response {

        $user = $this->getUser(); # je récupère l'utilisateur conncté

        if(!$user instanceof User) { # vérifier que l'utilisateur est connecté
            return $this->redirectToRoute('app_login');
        }

        if(!$user->getFormule()) {  # je vérife si l'utilisateur a une formule
            return $this->redirectToRoute('app_formule');
        }

        if($voyage->getFormule() !== $user->getFormule()) { # je compare la formule associée à ce voyage et la formule de l’utilisateur

            throw $this->createAccessDeniedException('Accès interdit');
        }

        $places = $geoapifyService->getPlaceCity($voyage->getDestination());
 
        return $this->render('voyage/detail.html.twig', [
            'voyage' => $voyage,
            'places' => $places,
        ]);         
    }

}
