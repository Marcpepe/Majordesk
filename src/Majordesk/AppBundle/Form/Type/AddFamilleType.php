<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AddFamilleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('heures_achetees'         , 'integer')
            // ->add('heures_restantes'        , 'integer')
            ->add('alerte_heures'           , 'choice', array(
				'choices' => array(0 => 'Aucune', 1 => '1h restante', 2 => '2h restantes', 3 => '3h restantes', 4 => '5h restantes', 5 => '10h restantes'),
				'attr' => array('class'=>'form-control')
				))
            // ->add('immatriculation_urssaf'  , 'text', array(
				// 'required' => false
				// ))
            // ->add('securite_sociale'        , 'text', array(
				// 'required' => false,
				// 'attr' => array('class'=>'form-control')
				// ))
            // ->add('heures_prises'           , 'integer')
            ->add('abonnement'                     , 'text', array(
				'required' => false,
				'attr' => array('class'=>'form-control')
				))
			->add('clients', 'collection', array(
					'type'         => new AddClientType(),
					'required' => false,
                    'allow_add'    => true,
                    'allow_delete' => true,
					'by_reference' => false,
					'attr' => array('class'=>'form-control')
					))
            // ->add('actif')
            // ->add('flag')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Majordesk\AppBundle\Entity\Famille'
        ));
    }

    public function getName()
    {
        return 'addfamilletype';
    }
}
