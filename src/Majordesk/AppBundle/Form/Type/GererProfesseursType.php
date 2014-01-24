<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Majordesk\AppBundle\Entity\ProfesseurRepository;

class GererProfesseursType extends AbstractType
{
	

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('professeurs'               , 'collection', array(
					'type' => 'entity',
					'options' => array(
						'class' => 'MajordeskAppBundle:Professeur',
						'property' => 'nomEntier',
						'group_by' => 'initiale',
						'query_builder' => function(ProfesseurRepository $r) {
								return $r->getProfesseursByAlphabeticalOrder();
							},
						'attr' => array('class'=>'form-control')
						),
					'allow_add' => true,
					'allow_delete' => true,
					'required' => false,
					'by_reference' => false
					))
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
        return 'professeurtype';
    }
}
