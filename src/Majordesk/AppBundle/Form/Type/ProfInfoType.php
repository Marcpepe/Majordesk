<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Majordesk\AppBundle\Entity\EleveRepository;

use Majordesk\AppBundle\Form\Type\CasierType;
use Majordesk\AppBundle\Form\Type\CarteEtudiantType;

class ProfInfoType extends AbstractType
{
	

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('casier'                 , new CasierType(), array(
				'required' => false,
				'attr' => array('class'=>'form-control')
				))
			->add('carteEtudiant'                 , new CarteEtudiantType(), array(
				// 'class' => 'MajordeskAppBundle:CarteEtudiant',
				// 'property' => 'file',
				'required' => false,
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
			// ->add('transport'           , 'choice', array(
				// 'choices'   => array('Transports en commun' => 'Transports en commun', 'Voiture' => 'Voiture', '2 roues' => '2 roues', 'Vélo' => 'Vélo', 'Autre' => 'Autre'),
				// 'attr' => array('class'=>'form-control')
				// ))
			// ->add('disponibilites', 'collection', array(
					// 'type'         => new DisponibiliteType(),
					// 'options'      => array(
							// 'attr' => array('class'=>'form-control')
						// ),
					// 'required' => false,
					// 'allow_add'    => true,
                    // 'allow_delete' => true
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
        return 'info_professeurtype';
    }
}
