<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('oldpass', 'password', array(
				'attr' => array('class'=>'form-control'),
				'mapped' => false
			))
			->add('newpass', 'repeated', array(
				'type' => 'password',
				'invalid_message' => 'Les mots de passe doivent correspondre',
				'first_name' => 'pass',
				'second_name' => 'confirm',
				'first_options'  => array('label' => ' '),
				'second_options' => array('label' => 'Confirmation'),
				'options' => array('attr' => array('class'=>'form-control')),
				'mapped' => false
			))
			;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        // $resolver->setDefaults(array(
            // 'data_class' => 'Majordesk\AppBundle\Entity\Professeur'
        // ));
    }

    public function getName()
    {
        return 'passwordtype';
    }
}
