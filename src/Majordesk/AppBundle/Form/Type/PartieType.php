<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PartieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom'                , 'text', array(
				'attr'=> array('class'=>'form-control')
			))
            ->add('numero'             , 'integer', array(
				'attr'=> array('class'=>'form-control')
			))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Majordesk\AppBundle\Entity\Partie'
        ));
    }

    public function getName()
    {
        return 'partietype';
    }
}
