<?php

namespace App\DataFixtures;

use App\Entity\Hr\Competence;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCompetenceData extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $competences = [
            'PHP',
            'HTML',
            'CSS',
            'Symfony 3',
            'Javascript',
            'React',
            'Angular',
            'Machine Learning',
            'Arduino/Raspberry Pi',
            'Traitement du signal',
            'Jquery',
            'Bootstrap',
            'Android',
            'Java',
            'Python',
            'Wordpress',
            'Phonegap / Cordova',
            'IOS',
        ];

        foreach ($competences as $competence) {
            $c = new Competence();
            $c->setNom($competence);

            $manager->persist($c);
        }
        $manager->flush();
    }
}
