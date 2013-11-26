<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ModMacroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('type', 'hidden')
			->add('fond', 'hidden', array(
				'required' => false
			))
			->add('numero', 'hidden')
			->add('couche', 'integer', array(
						'attr' => array('class' => 'form-control')
					))
            ->add('mod_elements', 'collection', array(
					'type'         => new ModElementType(),
					'allow_add' => true,
					'allow_delete' => true,
					'prototype' => false
					))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Majordesk\AppBundle\Entity\ModMacro'
        ));
    }

    public function getName()
    {
        return 'modmacrotype';
    }
}
