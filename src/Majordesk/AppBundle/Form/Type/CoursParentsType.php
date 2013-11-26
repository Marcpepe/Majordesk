<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Majordesk\AppBundle\Entity\MatiereRepository;

class CoursParentsType extends AbstractType
{
	protected $id_famille;
	
	public function __construct ($id_famille)
	{
		$this->id_famille = $id_famille;
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$id_famille = $this->id_famille;
	
        $builder
			->add('matiere'         , 'entity', array(
					'class' => 'MajordeskAppBundle:Matiere',
					'property' => 'nom',
					// 'empty_value' => 'Choisir une matière',
					'query_builder' => function(MatiereRepository $r) use($id_eleve){
								return $r->createQueryBuilder('m')
										 ->join('m.eleve_matieres', 'e_m')
										 ->join('e_m.eleve', 'e')
										 ->where('e.id = :id_eleve')
										 ->setParameter('id_eleve', $id_eleve);
							},
					'attr' => array( 'class' => 'form-control', 'autocomplete' => 'off' )
					))
            ->add('dateCours'    , 'date', array(
				'widget' => 'single_text',
				'format' => 'dd/MM/yyyy',
				'attr' => array('class' =>'form-control datepicker'),
				'invalid_message' => 'La date entrée est incorrecte'
				))
            ->add('heureDebut', 'text', array(
				'attr' => array('class' =>'form-control timepicker-debut'),
				'invalid_message' => "L'heure de début est incorrecte."
				))
            ->add('heureFin'  , 'text', array(
				'attr' => array('class' =>'form-control timepicker-fin'),
				'invalid_message' => "L'heure de fin est incorrecte."
				))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Majordesk\AppBundle\Entity\CalEvent'
        ));
    }

    public function getName()
    {
        return 'courstype';
    }
}
