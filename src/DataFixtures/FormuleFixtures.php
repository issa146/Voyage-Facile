<?php

namespace App\DataFixtures;

use App\Entity\Formule;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FormuleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

            $formules = [
                ['Basic', '210', 'basic'],
                ['Premium', '560', 'premium'],
                ['Pro', '1050', 'pro'],
        ];

        foreach ($formules as [$nom, $prix, $niveau]) {
                $f = new Formule();
                $f->setNom($nom);
                $f->setPrix($prix);
                $f->setNiveau($niveau);
                $manager->persist($f);
        }

        $manager->flush();
    }
}
