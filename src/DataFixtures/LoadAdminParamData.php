<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 29/01/2017
 * Time: 10:36.
 */

namespace App\DataFixtures;

use App\Entity\Dashboard\AdminParam;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Loads the parameters modifiable through the admin form.
 */
class LoadAdminParamData extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $parameters = [
        ['name' => 'nom', 'paramType' => 'string', 'defaultValue' => 'Aquitaine Électronique Informatique', 'required' => true, 'paramLabel' => 'Nom de la junior'],
        ['name' => 'abbr', 'paramType' => 'string', 'defaultValue' => 'AEI', 'required' => true, 'paramLabel' => 'Abbréviation de la junior'],
        ['name' => 'logo', 'paramType' => 'url', 'defaultValue' => 'https://www.junior-aei.com/wp-content/uploads/2016/11/logo_aei2.png', 'required' => true, 'paramLabel' => 'URL du logo de la Junior Entreprise'],
        ['name' => 'adresse', 'paramType' => 'string', 'defaultValue' => '1 avenue du Docteur Albert Schweitzer 33402 Talence', 'required' => true, 'paramLabel' => 'Adresse postale'],
        ['name' => 'url', 'paramType' => 'url', 'defaultValue' => 'http://www.junior-aei.com', 'required' => true, 'paramLabel' => 'URL du site web'],
        ['name' => 'email', 'paramType' => 'string', 'defaultValue' => 'contact@junior-aei.com', 'required' => true, 'paramLabel' => 'Email de contact de la junior'],
        ['name' => 'domaineEmailEtu', 'paramType' => 'string', 'defaultValue' => 'enseirb-matmeca.fr', 'required' => true, 'paramLabel' => 'Suffixe des emails étudiants'],
        ['name' => 'domaineEmailAncien', 'paramType' => 'string', 'defaultValue' => 'junior-aei.com', 'required' => true, 'paramLabel' => 'Suffixe des emails alumni'],
        ['name' => 'presidentPrenom', 'paramType' => 'string', 'defaultValue' => 'Antoine', 'required' => true, 'paramLabel' => 'Prenom du president'],
        ['name' => 'presidentNom', 'paramType' => 'string', 'defaultValue' => 'Dupond', 'required' => true, 'paramLabel' => 'Nom du président'],
        ['name' => 'presidentTexte', 'paramType' => 'string', 'defaultValue' => 'Son président,', 'required' => true, 'paramLabel' => 'Texte d\'annonce pour la signature président'],
        ['name' => 'tresorierPrenom', 'paramType' => 'string', 'defaultValue' => 'Thomas', 'required' => true, 'paramLabel' => 'Prenom du trésorier'],
        ['name' => 'tresorierNom', 'paramType' => 'string', 'defaultValue' => 'Dupont', 'required' => true, 'paramLabel' => 'Nom du trésorier'],
        ['name' => 'tresorierTexte', 'paramType' => 'string', 'defaultValue' => 'Son trésorier,', 'required' => true, 'paramLabel' => 'Texte d\'annonce pour la signature trésorier'],
        ['name' => 'tva', 'paramType' => 'number', 'defaultValue' => 0.2, 'required' => true, 'paramLabel' => 'Taux de TVA (20% -> 0.2)'],
        ['name' => 'anneeCreation', 'paramType' => 'string', 'defaultValue' => '1981', 'required' => true, 'paramLabel' => 'Année de création de la junior'],
        ['name' => 'annee1Jeyser', 'paramType' => 'string', 'defaultValue' => '2018', 'required' => true, 'paramLabel' => 'Année de début d\'utilisation de Jeyser'],
        ['name' => 'gaTracking', 'paramType' => 'string', 'defaultValue' => '', 'required' => false, 'paramLabel' => 'Code de suivi Google Analytics'],
        ['name' => 'namingConvention', 'paramType' => 'string', 'defaultValue' => 'nom', 'required' => true, 'paramLabel' => 'Convention de nommage',
            'paramDescription' => 'Quel champ d\'une étude doit être utilisé dans les reférences à un document ? Accepte les valeurs numero ou nom', ],
        ['name' => 'fraisDossierDefaut', 'paramType' => 'string', 'defaultValue' => '90', 'required' => true, 'paramLabel' => 'Frais de dossier par défaut',
            'paramDescription' => 'Valeur par défaut des frais de dossier à la création de l\'Avant-Projet', ],
        ['name' => 'pourcentageAcompteDefaut', 'paramType' => 'number', 'defaultValue' => 0.4, 'required' => true, 'paramLabel' => 'Acompte par défaut',
            'paramDescription' => 'Valeur par défaut de l\'acompte à la création de la Convention Client', ],
        ];

        $i = 0;
        foreach ($parameters as $param) {
            $p = new AdminParam();
            $p->setName($param['name']);
            $p->setParamType($param['paramType']);
            $p->setDefaultValue($param['defaultValue']);
            $p->setRequired($param['required']);
            $p->setParamLabel($param['paramLabel']);
            $p->setParamDescription($param['paramDescription'] ?? '');
            $p->setPriority(1000 - $i * 10);
            $manager->persist($p);
            ++$i;
        }

        $manager->flush();
    }
}
