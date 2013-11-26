<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ModMappingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('type', 'hidden')
            ->add('mod_reponses', 'collection', array(
					'type'         => new ModReponseType(),
					'allow_add' => true,
					'allow_delete' => true,
					'prototype' => false
					))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Majordesk\AppBundle\Entity\ModMapping'
        ));
    }

    public function getName()
    {
        return 'modmappingtype';
    }
}
