<?php

/*
 * This file is part of the Incipio package.
 *
 * (c) Florian Lefevre
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\Personne;

use App\Entity\Personne\Mandat;
use App\Entity\Personne\Poste;
use Genemu\Bundle\FormBundle\Form\JQuery\Type\DateType as GenemuDateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MandatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('debutMandat', GenemuDateType::class,
                ['label' => 'Date de début', 'format' => 'dd/MM/yyyy', 'required' => false, 'widget' => 'single_text'])
            ->add('finMandat', GenemuDateType::class,
                ['label' => 'Date de Fin', 'format' => 'dd/MM/yyyy', 'required' => false, 'widget' => 'single_text'])
            ->add('poste', EntityType::class,
                ['label' => 'Intitulé',
                 'class' => Poste::class,
                 'choice_label' => 'intitule',
                 'required' => true,
                ]); //ajout de la condition "requis" pour éviter la corruption de la liste des membres par manque d'intitulé.
    }

    public function getBlockPrefix()
    {
        return 'personne_mandatetype';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Mandat::class,
        ]);
    }
}
