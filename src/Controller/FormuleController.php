<?php

namespace App\Controller;

use App\Entity\Formule;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FormuleController extends AbstractController
{
    #[Route('/formule', name: 'app_formule', methods:['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        $formules = $em->getRepository(Formule::class)->findAll(); /*- Récupère le repository et pour récupérer toutes les formules en BDD. */


        return $this->render('formule/index.html.twig', [
            'formules' => $formules,
        ]);

    }

    #[Route('/formule/choisir/{id}', name: 'app_formule_choisir', methods:['POST'])]
    public function choisir(Formule $formule, EntityManagerInterface $em, Request $request) {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY'); /* vérifie si l'utilisateur est bien connéceter */


        /** @var \App\Entity\User $user */
        $user = $this->getUser(); /* retourne l'utilisateur connecté */ 

        $user->setFormule($formule); 

        $em->flush();

        return $this->redirectToRoute('app_voyage');

    } 
}
