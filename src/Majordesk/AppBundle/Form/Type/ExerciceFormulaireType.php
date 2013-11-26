<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ExerciceFormulaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('mod_exercice'    , new ModExerciceType())
			->add('mod_questions', 'collection', array(
					'type'         => new ModQuestionType(),
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
        return 'exerciceformulairetype';
    }
}
