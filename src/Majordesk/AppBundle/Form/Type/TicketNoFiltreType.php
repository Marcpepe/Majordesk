<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Majordesk\AppBundle\Entity\ProfesseurRepository;
use Majordesk\AppBundle\Entity\MatiereRepository;

class TicketNoFiltreType extends AbstractType
{
	protected $id_eleve;
	
	public function __construct ($id_eleve)
	{
		$this->id_eleve = $id_eleve;
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$id_eleve = $this->id_eleve;
	
        $builder
            ->add('quantite', 'choice', array(
				'choices'   => array('10' => '1h', '15' => '1h30', '20' => '2h', '25' => '2h30', '30' => '3h', '35' => '3h30', '40' => '4h', '45' => '4h30', '50' => '5h'),
				'attr' => array('class'=>'form-control')
			))   
			->add('professeur', 'entity', array(
				'class' => 'MajordeskAppBundle:Professeur',
				'property' => 'username',
				'query_builder' => function(ProfesseurRepository $r) use($id_eleve){
								return $r->getProfesseursFilteredByEleveQb($id_eleve);
							},
				'attr' => array('class'=>'form-control'),
			))
			->add('dateCours'    , 'date', array(
				'widget' => 'single_text',
				'format' => 'dd/MM/yyyy',
				'attr' => array('class' =>'form-control datepicker'),
				'invalid_message' => 'La date entrée est incorrecte'
			))
			->add('matiere', 'entity', array(
				'class' => 'MajordeskAppBundle:Matiere',
				'property' => 'nom',
				'query_builder' => function(MatiereRepository $r) use($id_eleve){
								return $r->getMatieresByEleveQb($id_eleve);
							},
				'attr' => array('class'=>'form-control'),
				'mapped' => false
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
        return 'ticketnofiltretype';
    }
}
