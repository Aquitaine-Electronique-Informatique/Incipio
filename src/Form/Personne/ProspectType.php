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

use App\Entity\Personne\Prospect;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProspectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class)
            ->add('entite', ChoiceType::class,
                ['choices' => array_flip(Prospect::getEntiteChoice()), 'required' => false])
            ->add('adresse', TextareaType::class, ['required' => false])
            ->add('codepostal', TextType::class, ['required' => false, 'attr' => ['placeholder' => 'Code Postal']])
            ->add('ville', TextType::class, ['required' => false, 'attr' => ['placeholder' => 'Ville']])
            ->add('pays', TextType::class, ['required' => false, 'attr' => ['placeholder' => 'Pays']])
            ->add('chaud', CheckboxType::class, ['required' => false, 'attr' => ['placeholder' => 'Chaud']])
            ->add('direct', CheckboxType::class, ['required' => false, 'attr' => ['placeholder' => 'Direct']])
            ->add('interet', ChoiceType::class, ['choices' => array([1,2,3,4,5]),'required' => false, 'attr' => ['placeholder' => 'Interêt']])
            ->add('date_rencontre', DateType::class, ['required' => false, 'attr' => ['placeholder' => 'Date De Rencontre']]);
    }

    public function getBlockPrefix()
    {
        return 'suivi_prospecttype';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Prospect::class,
        ]);
    }
}
