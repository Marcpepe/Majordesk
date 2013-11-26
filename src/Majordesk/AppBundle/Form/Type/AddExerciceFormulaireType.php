<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AddExerciceFormulaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('modele_exercice'    , new AddModeleExerciceType())
			->add('modeles_questions', 'collection', array(
					'type'         => new AddModeleQuestionType(),
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
            'data_class' => 'Majordesk\AppBundle\Form\Model\ExerciceFormulaire'
        ));
    }

    public function getName()
    {
        return 'addexerciceformulairetype';
    }
}
