<?php

namespace App\Service\Quality;

class MoodReasons
{
    public function getReasons() {

        $reasons = array(
            '0' => "Je suis chaud comme la breizh",
            '1' => "Toujours motivé",
            '2' => "C'est la routine mon ami",
            '3' => "Mes tâches à AEI ne m'intéressent plus",
            '4' => "Mes tâches à AEI sont trop redondantes",
            '5' => "J'ai trop de travail à côté",
            '6' => "J'ai la flemme",
            '7' => "J'ai des problèmes personnels"
        );

        return $reasons;
    }
    
}
