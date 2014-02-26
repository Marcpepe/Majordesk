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
			->add('mail','choice', array(
				'choices'   => array('1' => 'Cas "Famille a enregistré sa carte" : On envoie un mail à la Famille et au Prof pour les mettre en relation.', '2' => 'Cas "Famille n\'a pas encore enregistré sa carte" : On envoie un mail à la Famille pour lui signaler qu\'un prof a été trouvé.', '3' => 'Pas d\'envoi de mail'),
				'attr' => array('class'=>'form-control'),
				'data' => 3,
				'expanded' => true,
				'mapped' => false
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
