<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Majordesk\AppBundle\Form\EventListener\ChapitresSelectionListener;
use Majordesk\AppBundle\Form\EventListener\PartiesSelectionListener;

class ModExerciceType extends AbstractType
{	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mod_macros', 'collection', array(
					'type'         => new ModMacroType(),
					'allow_add' => true,
					'allow_delete' => true,
					'required' => false,
					'prototype' => false
					))
            ->add('programme'       , 'entity', array(
					'class' => 'MajordeskAppBundle:Programme',
					'property' => 'nom',
					'empty_value' => 'Choisir un programme',
					'attr' => array( 'class' => 'form-control' )
					))
            ->add('matiere'         , 'entity', array(
					'class' => 'MajordeskAppBundle:Matiere',
					'property' => 'nom',
					'empty_value' => 'Choisir une matière',
					'attr' => array( 'class' => 'form-control' )
					))
			->add('niveau'         , 'integer', array(
						'attr' => array('class' => 'form-control')
					))
        ;

		$chapitreSubscriber = new ChapitresSelectionListener($builder->getFormFactory());
        $builder->addEventSubscriber($chapitreSubscriber);
		$partieSubscriber = new PartiesSelectionListener($builder->getFormFactory());
        $builder->addEventSubscriber($partieSubscriber);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Majordesk\AppBundle\Entity\ModExercice'
        ));
    }

    public function getName()
    {
        return 'modexercicetype';
    }
}
