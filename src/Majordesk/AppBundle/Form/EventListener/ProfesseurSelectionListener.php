<?php

namespace Majordesk\AppBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Doctrine\ORM\EntityRepository;

class ProfesseurSelectionListener implements EventSubscriberInterface
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

        $this->populateProfesseurs($form, $eleve->getId());
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

        $this->populateProfesseurs($form, $id_eleve);
    }

    protected function populateProfesseurs($form, $id_eleve)
    {
        $form->add($this->factory->createNamed('professeur', 'entity', null, array(
			'class'         => 'Majordesk\AppBundle\Entity\Professeur',
			'property'      => 'username',
			'attr' => array( 'class' => 'form-control' ),
			'auto_initialize' => false,
			'query_builder' => function (EntityRepository $r) use ($id_eleve) {
								    $qb = $r->createQueryBuilder('p')
									 	    ->join('p.eleves', 'e')
									 	    ->where('e.id = :id_eleve')
										    ->setParameter('id_eleve', $id_eleve)
										    ;
								    return $qb;
							    }
		 )));
    }
}