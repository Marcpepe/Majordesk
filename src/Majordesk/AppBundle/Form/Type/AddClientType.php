<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AddClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mail'             , 'email', array(
				'attr' => array('class'=>'form-control')
				))
            ->add('password'         , 'repeated', array(
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
            ->add('username'           , 'text', array(
				'attr' => array('class'=>'form-control')
				))
            ->add('nom'              , 'text', array(
				'attr' => array('class'=>'form-control')
				))
            // ->add('actif')
            ->add('gender'           , 'choice', array(
				'choices' => array('1' => 'Mère', '2' => 'Père', '3' => 'Grand-Mère', '4' => 'Grand-Père', '5' => 'Tante', '6' => 'Oncle', '7' => 'Autre'),
				'attr' => array('class'=>'form-control')
				))
            ->add('adresse'          , 'text', array(
				'required' => false,
				'attr' => array('class'=>'form-control')
				))
            ->add('code_postal'      , 'text', array(
				'required' => false,
				'attr' => array('class'=>'form-control')
				))
            ->add('ville'            , 'text', array(
				'required' => false,
				'attr' => array('class'=>'form-control')
				))
            ->add('telephone'        , 'text', array(
				'required' => false,
				'attr' => array('class'=>'form-control')
				))
            ->add('newsletter'       , 'choice', array(
				'choices' => array('0' => 'Non', '1' => 'Oui'),
				'attr' => array('class'=>'form-control')
				))    
            ->add('alertes'          , 'choice', array(
				'choices' => array('0' => 'Non', '1' => 'Oui'),
				'attr' => array('class'=>'form-control')
				)) 
            // ->add('heure_alertes'    , 'time', array(
				// 'required' => false
				// ))
			->add('heure_alertes'  , 'text', array(
				'attr' => array('class' =>'form-control timepicker-alerte'),
				'invalid_message' => "L'heure de l'alerte est incorrecte."
				))
            // ->add('date_inscription' , 'date', array(
				// 'widget' => 'single_text',
				// 'format' => 'dd-MM-yyyy',
				// 'attr' => array('class' =>'datepicker'),
				// 'invalid_message' => 'La date entrée est incorrecte'
				// ))
            //  ->add('famille')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Majordesk\AppBundle\Entity\Client'
        ));
    }

    public function getName()
    {
        return 'clienttype';
    }
}
