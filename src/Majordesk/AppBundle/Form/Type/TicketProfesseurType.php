<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Majordesk\AppBundle\Entity\MatiereRepository;
use Majordesk\AppBundle\Entity\EleveRepository;
// use Majordesk\AppBundle\Form\EventListener\ProfesseurSelectionListener;
// use Majordesk\AppBundle\Form\EventListener\MatieresSelectionListener;

class TicketProfesseurType extends AbstractType
{
	protected $id_professeur;
	
	public function __construct ($id_professeur)
	{
		$this->id_professeur = $id_professeur;
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$id_professeur = $this->id_professeur;
	
        $builder
            ->add('eleve'         , 'entity', array(
					'class' => 'MajordeskAppBundle:Eleve',
					'property' => 'username',
					'empty_value' => 'Sélectionner un élève',
					'query_builder' => function(EleveRepository $r) use($id_professeur){
								return $r->createQueryBuilder('e')
								         ->join('e.professeurs','p')
										 ->where('p.id = :id_professeur')
										 ->setParameter('id_professeur', $id_professeur);
							},
					'attr' => array( 'class' => 'form-control', 'autocomplete' => 'off' )
					))
			->add('matiere'         , 'entity', array(
					'class' => 'MajordeskAppBundle:Matiere',
					'property' => 'nom',
					// 'empty_value' => 'Sélectionner une matière',
					'query_builder' => function(MatiereRepository $r) use($id_professeur){
								return $r->createQueryBuilder('m')
								         ->join('m.professeurs','p')
										 ->where('p.id = :id_professeur')
										 ->setParameter('id_professeur', $id_professeur);
							},
					'attr' => array( 'class' => 'form-control', 'autocomplete' => 'off' ),
					'mapped' => false
					))
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
			->add('passparent', 'password', array(
				'attr' => array('class'=>'form-control', 'placeholder' =>'Mot de passe Parent ou Enfant'),
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
        return 'ticketselectnofiltretype';
    }
}
