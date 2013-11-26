<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EleveMatiereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('matiere'       , 'entity', array(
					'class' => 'MajordeskAppBundle:Matiere',
					'property' => 'nom',
					'empty_value' => 'Choisir une matière',
					'attr' => array('class'=>'form-control')
					))
            ->add('plateforme', 'choice', array(
				'choices'   => array('0' => 'Sans', '1' => 'Avec'),
				'attr' => array('class'=>'form-control')
				))
			->add('prelevement_plateforme', 'choice', array(
				'choices'   => array('0' => 'Non', '1' => 'Oui'),
				'attr' => array('class'=>'form-control')
				))
			->add('cours', 'choice', array(
				'choices'   => array('0' => 'Sans', '1' => 'Avec'),
				'attr' => array('class'=>'form-control')
				))
			->add('prelevement_cours', 'choice', array(
				'choices'   => array('0' => 'Non', '1' => 'Oui'),
				'attr' => array('class'=>'form-control')
				))
            ->add('heures_prises', 'integer', array(
				'attr' => array('class'=>'form-control')
				))
			->add('date_abonnement'    , 'date', array(
				'widget' => 'single_text',
				'format' => 'dd/MM/yyyy',
				'attr' => array('class' =>'form-control datepicker'),
				'invalid_message' => 'La date entrée est incorrecte',
				'required' => false
				))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Majordesk\AppBundle\Entity\EleveMatiere'
        ));
    }

    public function getName()
    {
        return 'elevematieretype';
    }
}
