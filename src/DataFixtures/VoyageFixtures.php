<?php

namespace App\DataFixtures;


use App\Entity\Formule;
use App\Entity\Voyage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class VoyageFixtures extends Fixture {


    public function load(ObjectManager $manager): void {
        $formuleBasic = $manager->getRepository(Formule::class)->findOneBy(['nom' => 'basic']);
        $formulePremium = $manager->getRepository(Formule::class)->findOneBy(['nom' => 'premium']);
        $formulePro = $manager->getRepository(Formule::class)->findOneBy(['nom' => 'pro']);


        $destinations = [
            ['Paris', 210, $formuleBasic, 10-06-2026, 17-06-2026, 'en cours'],
            ['Tokyo', 560, $formulePremium, 10-06-2026, 24-06-2026, 'en cours'],
            ['Rome', 1050, $formulePro, 10-06-2026, 01-07-2026, 'en cours'],
            ['New York', 1050, $formulePro, 10-06-2026, 01-07-2026, 'en cours'],
            ['Marrakech', 560, $formulePremium, 10-06-2026, 24-06-2026, 'en cours'],
            ['Istanbul', 560, $formulePremium, 10-06-2026, 24-06-2026, 'en cours'],
        ];

        foreach($destinations as [$destination, $prix, $formule, $dateDebut, $dateFin, $statut]) {
            $voyage = new Voyage();
            $voyage->setDestination($destination);
            $voyage->setDateDebut(new \DateTime($dateDebut));
            $voyage->setDateFin(new \DateTime($dateFin));
            $voyage->setStatut($statut);
            $voyage->setPrix($prix);
            $voyage->setFormule($formule);

            $manager->persist($voyage);
        }

            $manager->flush();
    }
}






?>