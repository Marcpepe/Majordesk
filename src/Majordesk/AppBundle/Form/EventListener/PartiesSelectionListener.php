<?php

namespace Majordesk\AppBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Doctrine\ORM\EntityRepository;

class PartiesSelectionListener implements EventSubscriberInterface
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

        $chapitre = $data->getChapitre();
		
		if (null === $chapitre) return;

        $this->populateParties($form, $chapitre->getId());
    }
	
	/**
     * @param event FormEvent
     */
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        if (null === $data) return;
 
        $id_chapitre = $data['chapitre'];

        $this->populateParties($form, $id_chapitre);
    }

    protected function populateParties($form, $id_chapitre)
    {
        $form->add($this->factory->createNamed('partie', 'entity', null, array(
			'class'         => 'Majordesk\AppBundle\Entity\Partie',
			'property'      => 'nom',
			'attr' => array( 'class' => 'form-control' ),
			'auto_initialize' => false,
			'query_builder' => function (EntityRepository $r) use ($id_chapitre) {
								    $qb = $r->createQueryBuilder('p')
									 	    ->join('p.chapitre', 'c')
									 	    ->where('c.id = :id_chapitre')
										    ->setParameter('id_chapitre', $id_chapitre)
										    ;
								    return $qb;
							    }
		 )));
    }
}