<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AddDisponibiliteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('jour'      , 'choice', array(
				'choices'   => array('Lundi' => 'Lundi', 'Mardi' => 'Mardi', 'Mercredi' => 'Mercredi', 'Jeudi' => 'Jeudi', 'Vendredi' => 'Vendredi', 'Samedi' => 'Samedi', 'Dimanche' => 'Dimanche'),
				'attr' => array('class' =>'form-control')
				))
            ->add('heureDebut', 'text', array(
				'attr' => array('class' =>'form-control timepicker-debut'),
				'invalid_message' => "L'heure de début est incorrecte."
				))
            ->add('heureFin'  , 'text', array(
				'attr' => array('class' =>'form-control timepicker-fin'),
				'invalid_message' => "L'heure de fin est incorrecte."
				))
            // ->add('actif')
            // ->add('professeur')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Majordesk\AppBundle\Entity\Disponibilite'
        ));
    }

    public function getName()
    {
        return 'adddisponibilitetype';
    }
}
