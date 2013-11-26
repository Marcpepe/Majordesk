<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EleveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mail'                , 'email', array(
				'attr' => array('class'=>'form-control')
				))
            // ->add('password'            , 'password')
            // ->add('salt')
            // ->add('roles')
            ->add('username'              , 'text', array(
				'attr' => array('class'=>'form-control')
				))
            ->add('nom'                 , 'text', array(
				'attr' => array('class'=>'form-control')
				))
            // ->add('actif')
            // ->add('flag')
			->add('programme'       , 'entity', array(
					'class' => 'MajordeskAppBundle:Programme',
					'property' => 'nom',
					'empty_value' => 'Choisir un programme',
					'attr' => array('class'=>'form-control')
					))
            ->add('lycee'               , 'text', array(
				'required' => false,
				'attr' => array('class'=>'form-control')
				))
            ->add('telephone'           , 'text', array(
				'required' => false,
				'attr' => array('class'=>'form-control')
				))
            ->add('heures_prises'       , 'integer', array(
				'attr' => array('class'=>'form-control')
				))
            ->add('notifications'       , 'integer', array(
				'attr' => array('class'=>'form-control')
				))
            ->add('rythme'              , 'integer', array(
				'attr' => array('class'=>'form-control')
				))
            ->add('date_inscription'    , 'date', array(
				'widget' => 'single_text',
				'format' => 'dd/MM/yyyy',
				'attr' => array('class' =>'form-control datepicker'),
				'invalid_message' => 'La date entrée est incorrecte'
				))
			// ->add('date_abonnement'    , 'date', array(
				// 'widget' => 'single_text',
				// 'format' => 'dd/MM/yyyy',
				// 'attr' => array('class' =>'form-control datepicker'),
				// 'invalid_message' => 'La date entrée est incorrecte'
				// ))
			// ->add('situation'         , 'choice', array(
				// 'choices' => array('0' => 'Aucune', '1' => 'Plateforme', '2' => 'Cours', '3' => 'Cours et Plateforme'),
				// 'attr' => array('class'=>'form-control')
				// ))
			// ->add('autorisation_prelevement'         , 'choice', array(
				// 'choices' => array('0' => 'Non', '1' => 'Oui (Plateforme)', '2' => 'Oui (Cours)', '3' => 'Oui (Cours et Plateforme)'),
				// 'attr' => array('class'=>'form-control')
				// ))
			->add('eleve_matieres'         , 'collection', array(
					'type'         => new EleveMatiereType(),
					'options'      => array(
							'attr' => array('class'=>'form-control')
						),
					'required' => false,
					'allow_add'    => true,
                    'allow_delete' => true,
					'by_reference' => false
					))
			->add('disponibilites', 'collection', array(
					'type'         => new DisponibiliteSansFinType(),
					'required' => false,
					'allow_add'    => true,
                    'allow_delete' => true,
					'by_reference' => false,
					'options'      => array(
							'attr' => array('class'=>'form-control')
						),
					))
            // ->add('famille'             , 'integer')
            // ->add('professeurs'          , 'integer')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Majordesk\AppBundle\Entity\Eleve'
        ));
    }

    public function getName()
    {
        return 'elevetype';
    }
}
