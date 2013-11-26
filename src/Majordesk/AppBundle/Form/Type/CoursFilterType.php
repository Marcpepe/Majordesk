<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Majordesk\AppBundle\Form\EventListener\MatieresSelectionListener;
use Majordesk\AppBundle\Entity\EleveRepository;

class CoursFilterType extends AbstractType
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
            ->add('eleve'         , 'entity', array(
					'class' => 'MajordeskAppBundle:Eleve',
					'property' => 'username',
					'empty_value' => 'Pour quel enfant ?',
					'query_builder' => function(EleveRepository $r) use($id_famille){
								return $r->createQueryBuilder('e')
								         ->join('e.famille','f')
										 ->where('f.id = :id_famille')
										 ->setParameter('id_famille', $id_famille);
							},
					'attr' => array( 'class' => 'form-control', 'autocomplete' => 'off' )
					))
        ;

		$matiereSubscriber = new MatieresSelectionListener($builder->getFormFactory());
        $builder->addEventSubscriber($matiereSubscriber);
		
		$builder
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
        return 'matieresselectortype';
    }
}
