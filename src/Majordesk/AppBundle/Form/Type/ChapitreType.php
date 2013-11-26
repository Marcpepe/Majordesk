<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChapitreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom'                , 'text', array(
				'attr'=> array('class'=>'form-control')
			))
			->add('parties', 'collection', array(
					'type'         => new PartieType(),
					'required' => false,
					'options' => array(
						'attr' => array('class'=>'form-control')
					),
					'allow_add'    => true,
                    'allow_delete' => true,
					'by_reference' => false
					))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Majordesk\AppBundle\Entity\Chapitre'
        ));
    }

    public function getName()
    {
        return 'chapitretype';
    }
}
