<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Majordesk\AppBundle\Entity\CalEventRepository;

class TicketEnfantType extends AbstractType
{
	protected $heures;
	
	public function __construct ($heures, $id_eleve)
	{
		$this->heures = $heures;
		$this->id_eleve = $id_eleve;
	}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$heures = $this->heures;
		$id_eleve = $this->id_eleve;
		$heure_from = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
		$heure_from->sub(new \DateInterval('PT5H'));
		$heure_to = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
		$heure_to->add(new \DateInterval('PT1H'));
	
        $builder
            ->add('quantite', 'choice', array(
				'choices'   => $heures,
				'attr' => array('class'=>'form-control')
			))   
			->add('cal_event', 'entity', array(
				'class' => 'MajordeskAppBundle:CalEvent',
				'property' => 'titre',
				'query_builder' => function(CalEventRepository $r) use($id_eleve, $heure_from, $heure_to){
								return $r->getEleveCalEventsQb($id_eleve, $heure_from, $heure_to);
							},
				'attr' => array('class'=>'form-control'),
			))
			->add('passparent', 'password', array(
				'attr' => array('class'=>'form-control', 'placeholder' =>'Mot de passe d\'un parent'),
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
        return 'ticketenfanttype';
    }
}
