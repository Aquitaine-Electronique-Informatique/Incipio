<?php

/*
   Alexandre Couedelo @ 2015-02-17 20:15:24
    Importé depuis Emagine/incipio
 */

namespace App\DataFixtures;

use App\Entity\Personne\Filiere;
use App\Entity\Personne\Poste;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPosteFiliereData extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $postes = [
            //Bureau
            'Président',
            'Président par procuration',
            'Vice-président',
            'Trésorier',
            'Secrétaire général',
            //ca
            'Respo. Qualité',
            'Respo. Communication',
            'DSI',
            "Respo. Commercial",
            "Respo. Études",
            //Membre
            'Suiveur Qualité-Tréso',
            "Respo. Évènements",
            "Respo. Engagements",
            "Respo. Partenariats",
            "Chargé d'études",
            "Chargé de mission SI",
            "Chargé de mission Communication",
            "Chargé de mission Qualité",
            "Commercial",
            'Comptable',
            'Membre',
            'Réalisateur',
        ];

        foreach ($postes as $poste) {
            $p = new Poste();
            $p->setIntitule($poste);
            $p->setDescription('a completer');

            $manager->persist($p);
        }

        $info = new Filiere();
        $info->setNom('Informatique');
        $info->setDescription("Filière informatique de l'ENSEIRB-MATMECA.");

        $matmeca = new Filiere();
        $matmeca->setNom('MatMéca');
        $matmeca->setDescription("Filière mathématiques et mécanique de l'ENSEIRB-MATMECA.");

        $elec = new Filiere();
        $elec->setNom('Électronique');
        $elec->setDescription("Filière électronique de l'ENSEIRB-MATMECA.");

        $telecom = new Filiere();
        $telecom->setNom('Télécommunications');
        $telecom->setDescription("Filière télécommunications de l'ENSEIRB-MATMECA.");

        $manager->flush();
    }
}
