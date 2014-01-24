<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Majordesk\AppBundle\Entity\EleveRepository;

class ProfesseurType extends AbstractType
{
	

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mail'                , 'email', array(
				'attr' => array('class'=>'form-control')
				))
            // ->add('password'            , 'repeated', array(
				// 'type' => 'password',
				// 'invalid_message' => 'Les mots de passe doivent correspondre',
				// 'first_name' => 'pass',
				// 'second_name' => 'confirm',
				// 'first_options'  => array('label' => ' '),
				// 'second_options' => array('label' => 'Confirmation'),
				// ))
            // ->add('salt')
            // ->add('roles')
            ->add('username'              , 'text', array(
				'attr' => array('class'=>'form-control')
				))
            ->add('nom'                 , 'text', array(
				'attr' => array('class'=>'form-control')
				))
            // ->add('actif')
            ->add('heures_donnees'      , 'integer', array(
				'attr' => array('class'=>'form-control')
				))
            ->add('prepa'               , 'choice', array(
				'choices'   => array('MP' => 'MP', 'PSI' => 'PSI', 'PC' => 'PC', 'PT' => 'PT', 'Autre' => 'Autre'),
				'attr' => array('class'=>'form-control')
				))
            ->add('lycee'               , 'text', array(
				'required' => false,
				'attr' => array('class'=>'form-control')
				))
            ->add('adresse'             , 'text', array(
				'required' => false,
				'attr' => array('class'=>'form-control')
				))
            ->add('code_postal'         , 'text', array(
				'required' => false,
				'attr' => array('class'=>'form-control')
				))
            ->add('ville'               , 'text', array(
				'required' => false,
				'attr' => array('class'=>'form-control')
				))
            ->add('telephone'           , 'text', array(
				'required' => false,
				'attr' => array('class'=>'form-control')
				))
            ->add('fin_dispo'           , 'date', array(
				'widget' => 'single_text',
				'format' => 'dd/MM/yyyy',
				'attr' => array('class' =>'form-control datepicker'),
				'invalid_message' => 'La date entrée est incorrecte'
				))
            ->add('notifications', 'integer', array(
				'attr' => array('class'=>'form-control')
				))
            ->add('securite_sociale'    , 'text', array(
				'required' => false,
				'attr' => array('class'=>'form-control')
				))
            ->add('rib'                 , 'text', array(
				'required' => false,
				'attr' => array('class'=>'form-control')
				))
			->add('transport'           , 'choice', array(
				'choices'   => array('Transports en commun' => 'Transports en commun', 'Voiture' => 'Voiture', '2 roues' => '2 roues', 'Vélo' => 'Vélo', 'Autre' => 'Autre'),
				'attr' => array('class'=>'form-control')
				))
			->add('nb_heures_max', 'integer', array(
				'required' => false,
				'attr' => array('class'=>'form-control')
				))
			->add('matieres'         , 'collection', array(
					'type'         => 'entity',
					'options'      => array(
							'class' => 'MajordeskAppBundle:Matiere',
							'property' => 'nom',
							'empty_value' => 'Choisir une matière',
							'attr' => array('class'=>'form-control')
						),
					'required' => false,
					'allow_add'    => true,
                    'allow_delete' => true,
					'by_reference' => false
					))
			->add('disponibilites', 'collection', array(
					'type'         => new DisponibiliteType(),
					'options'      => array(
							'attr' => array('class'=>'form-control')
						),
					'required' => false,
					'allow_add'    => true,
                    'allow_delete' => true
					))
            ->add('date_inscription'    , 'date', array(
				'widget' => 'single_text',
				'format' => 'dd/MM/yyyy',
				'attr' => array('class' =>'form-control datepicker'),
				'invalid_message' => 'La date entrée est incorrecte'
				))
			// ->add('eleves'               , 'collection', array(
					// 'type' => 'entity',
					// 'options' => array(
						// 'class' => 'MajordeskAppBundle:Eleve',
						// 'property' => 'nomEntier',
						// 'group_by' => 'initiale',
						// 'query_builder' => function(EleveRepository $r) {
								// return $r->getElevesByAlphabeticalOrder();
							// },
						// 'attr' => array('class'=>'form-control')
						// ),
					// 'allow_add' => true,
					// 'allow_delete' => true,
					// 'required' => false,
					// 'by_reference' => false
					// ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Majordesk\AppBundle\Entity\Professeur'
		));
    }

    public function getName()
    {
        return 'professeurtype';
    }
}
