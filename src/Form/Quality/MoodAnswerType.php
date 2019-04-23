<?php

namespace App\Form\Quality;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Genemu\Bundle\FormBundle\Form\JQuery\Type\Select2ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Quality\MoodAnswer;

class MoodAnswerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->reasons = $options['reasons'];

        $builder
            ->add('answer1', Select2ChoiceType::class, array( 
                'choices' => array(
                    "1" => 1,
                    "2" => 2,
                    "3" => 3,
                    "4" => 4,
                    "5" => 5,
                    "6" => 6,
                    "7" => 7,
                    "8" => 8,
                    "9" => 9,
                    "10" => 10
                ), 'multiple' => false, 'required' => true,
                'label' => "Sur une échelle de 1 à 10, à quel point es-tu motivé ces derniers temps ?"))
            ->add('answer2', Select2ChoiceType::class, array(
                'choices' => array_flip($this->reasons), 'multiple' => true, 'required' => true, 'label' => "Quelles en sont les raisons ?"))
            ->add('answer3', TextareaType::class, array('attr' => array('placeholder' => 'Entrez votre réponse'), 'required' => false, 'label' => "Détails (facultatif)"))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MoodAnswer::class,
            'reasons' => null,
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'quality_mood_answer';
    }
}
