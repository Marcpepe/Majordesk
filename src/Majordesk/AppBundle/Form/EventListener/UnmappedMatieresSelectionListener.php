<?php

namespace Majordesk\AppBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Doctrine\ORM\EntityRepository;

class UnmappedMatieresSelectionListener implements EventSubscriberInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $factory;

    /**
     * @param factory FormFactoryInterface
     */
    public function __construct(FormFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public static function getSubscribedEvents()
    {
        return array(
			FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit',
        );
    }

    /**
     * @param event FormEvent
     */
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        $eleve = $data->getEleve();
		
		if (null === $eleve) return;

        $this->populateMatieres($form, $eleve->getId());
    }
	
	/**
     * @param event FormEvent
     */
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        if (null === $data) return;
 
        $id_eleve = $data['eleve'];

        $this->populateMatieres($form, $id_eleve);
    }

    protected function populateMatieres($form, $id_eleve)
    {
        $form->add($this->factory->createNamed('matiere', 'entity', null, array(
			'class'         => 'Majordesk\AppBundle\Entity\Matiere',
			'property'      => 'nom',
			'attr' => array( 'class' => 'form-control' ),
			
			'auto_initialize' => false,
			'query_builder' => function (EntityRepository $r) use ($id_eleve) {
								    $qb = $r->createQueryBuilder('m')
									 	    ->join('m.eleve_matieres', 'e_m')
									 	    ->join('e_m.eleve', 'e')
									 	    ->where('e.id = :id_eleve')
										    ->setParameter('id_eleve', $id_eleve)
										    ;
								    return $qb;
							    },
			'mapped' => false
		 )));
    }
}