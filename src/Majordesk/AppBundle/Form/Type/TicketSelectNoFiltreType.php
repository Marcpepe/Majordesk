<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Majordesk\AppBundle\Entity\EleveRepository;
use Majordesk\AppBundle\Form\EventListener\ProfesseurSelectionListener;
use Majordesk\AppBundle\Form\EventListener\MatieresSelectionListener;

class TicketSelectNoFiltreType extends AbstractType
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
					'empty_value' => 'Sélectionner un enfant',
					'query_builder' => function(EleveRepository $r) use($id_famille){
								return $r->createQueryBuilder('e')
								         ->join('e.famille','f')
										 ->where('f.id = :id_famille')
										 ->setParameter('id_famille', $id_famille);
							},
					'attr' => array( 'class' => 'form-control', 'autocomplete' => 'off' )
					))
        ;

		$professeurSubscriber = new ProfesseurSelectionListener($builder->getFormFactory());
        $builder->addEventSubscriber($professeurSubscriber);
		
		$matiereSubscriber = new MatieresSelectionListener($builder->getFormFactory());
        $builder->addEventSubscriber($matiereSubscriber); // add mapped = false ?
		
        $builder
            ->add('quantite', 'choice', array(
				'choices'   => array('10' => '1h', '15' => '1h30', '20' => '2h', '25' => '2h30', '30' => '3h', '35' => '3h30', '40' => '4h', '45' => '4h30', '50' => '5h'),
				'attr' => array('class'=>'form-control')
			))  
			->add('dateCours'    , 'date', array(
				'widget' => 'single_text',
				'format' => 'dd/MM/yyyy',
				'attr' => array('class' =>'form-control datepicker'),
				'invalid_message' => 'La date entrée est incorrecte'
			))
			;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Majordesk\AppBundle\Entity\Ticket'
        ));
    }

    public function getName()
    {
        return 'ticketselectnofiltretype';
    }
}
