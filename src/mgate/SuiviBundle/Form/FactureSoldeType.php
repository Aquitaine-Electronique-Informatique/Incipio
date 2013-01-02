<?php

namespace mgate\SuiviBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FactureSoldeType extends DocTypeType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
             $builder->add('facturesolde',new SuiviFactureSoldeType(),array('label'=>'Suivi du document'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'mgate\SuiviBundle\Entity\Etude'
        ));
    }

    public function getName()
    {
        return 'mgate_suivibundle_facturesoldetype';
    }
}