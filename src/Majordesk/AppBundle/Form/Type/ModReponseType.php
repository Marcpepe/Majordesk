<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ModReponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contenu', 'hidden')
            ->add('clavier', 'hidden')
            ->add('numero', 'hidden')
            ->add('type', 'choice', array(
				'choices' => array(
					'expression exacte' => 'Expression exacte',
					'expression' => 'Expression',
					'triangle' => 'Triangle',
					'angle' => 'Angle',
					'distance' => 'Distance/Segment',
					'radio' => 'Radio',
					'checkbox' => 'Checkbox',
					'liste' => 'Liste déroulante',
					'vignette' => 'Vignette',
					),
				'attr' => array('class'=>'form-control')
			))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Majordesk\AppBundle\Entity\ModReponse'
        ));
    }

    public function getName()
    {
        return 'modreponsetype';
    }
}
