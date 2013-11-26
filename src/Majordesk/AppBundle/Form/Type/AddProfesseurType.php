<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AddProfesseurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mail'                , 'email', array(
				'attr' => array('class'=>'form-control')
				))
            ->add('password'            , 'repeated', array(
				'type' => 'password',
				'invalid_message' => 'Les mots de passe doivent correspondre',
				'first_name' => 'pass',
				'second_name' => 'confirm',
				'first_options'  => array('label' => ' '),
				'second_options' => array('label' => 'Confirmation'),
				'options' => array('attr' => array('class'=>'form-control'))
				))
            // ->add('salt')
            // ->add('roles')
            ->add('username'              , 'text', array(
				'attr' => array('class'=>'form-control')
				))
            ->add('nom'                 , 'text', array(
				'attr' => array('class'=>'form-control')
				))
            // ->add('actif')
            // ->add('heures_donnees')
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
				'attr' => array('class' =>'datepicker form-control'),
				'invalid_message' => 'La date entrée est incorrecte'
				))
            // ->add('notifications')
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
					'type'         => new AddDisponibiliteType(),
					'required' => false,
					'allow_add'    => true,
                    'allow_delete' => true,
					'by_reference' => false,
					'attr' => array('class'=>'form-control')
					))
            // ->add('date_inscription')
            // ->add('eleves')
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
