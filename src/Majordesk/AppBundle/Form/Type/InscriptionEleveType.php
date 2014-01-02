<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InscriptionEleveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mail'                , 'email', array(
					'attr' => array('class' => 'form-control')
				))
            ->add('password'            , 'repeated', array(
				'type' => 'password',
				'invalid_message' => 'Les mots de passe doivent correspondre',
				'first_name' => 'pass',
				'second_name' => 'confirm',
				'options' => array('always_empty' => false, 'attr' => array('class' => 'form-control')),
				'first_options'  => array('label' => ' '),
				'second_options' => array('label' => 'Confirmation')
				))
            // ->add('salt')
            // ->add('roles')
            ->add('username'              , 'text', array(
					'attr' => array('class' => 'form-control')
				))
            ->add('nom'                 , 'text', array(
					'attr' => array('class' => 'form-control')
				))
            // ->add('actif')
            // ->add('flag')
            ->add('programme'       , 'entity', array(
					'class' => 'MajordeskAppBundle:Programme',
					'property' => 'nom',
					'empty_value' => 'Choisir un programme',
					'attr' => array('class' => 'form-control')
					))
            ->add('lycee'               , 'text', array(
				'required' => false,
				'attr' => array('class'=>'form-control lycee-typeahead', 'autocomplete'=>'off')
				))
            ->add('telephone'           , 'text', array(
				'required' => false,
				'attr' => array('class' => 'form-control')
				))
            // ->add('heures_prises'       , 'integer')
            // ->add('notifications'       , 'integer')
            // ->add('abonnements'         , 'choice', array(
				// 'choices' => array('0' => 'Aucun', '1' => 'Mathématiques', '2' => 'Physique-Chimie', '3' => 'Mathématiques, Physique-Chimie')
				// ))
			// ->add('matieres'         , 'collection', array(
					// 'type'         => 'entity',
					// 'options'      => array(
							// 'class' => 'MajordeskAppBundle:Matiere',
							// 'property' => 'nom',
							// 'empty_value' => 'Choisir une matière'
						// ),
					// 'required' => false,
					// 'allow_add'    => true,
                    // 'allow_delete' => true,
					// 'by_reference' => false
					// ))
			->add('disponibilites', 'collection', array(
					'type'         => new AddDisponibiliteType(),
					'required' => false,
					'allow_add'    => true,
                    'allow_delete' => true,
					'by_reference' => false,
					'attr' => array('class' => 'form-control')
					))
            // ->add('rythme'              , 'integer')
            // ->add('exercice_en_cours')
            // ->add('date_inscription'    , 'date', array(
				// 'widget' => 'single_text',
				// 'format' => 'dd-MM-yyyy',
				// 'attr' => array('class' =>'datepicker'),
				// 'invalid_message' => 'La date entrée est incorrecte'
				// ))
            // ->add('famille'             , 'entity', array(
				// 'class' => 'MajordeskAppBundle:Famille',
				// 'property' => 'id'
				// ))
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
        return 'inscriptionelevetype';
    }
}
