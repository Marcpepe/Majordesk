<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ModQuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero'  , 'hidden')
			->add('mod_macros', 'collection', array(
					'type'         => new ModMacroType(),
					'allow_add' => true,
					'allow_delete' => true,
					'required' => false,
					'prototype' => false
					))
			->add('mod_mappings', 'collection', array(
					'type'         => new ModMappingType(),
					'allow_add' => true,
					'allow_delete' => true,
					'required' => false,
					'prototype' => false
					))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Majordesk\AppBundle\Entity\ModQuestion'
        ));
    }

    public function getName()
    {
        return 'modquestiontype';
    }
}
