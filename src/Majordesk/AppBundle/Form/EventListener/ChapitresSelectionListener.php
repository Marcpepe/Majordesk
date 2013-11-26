<?php

namespace Majordesk\AppBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Doctrine\ORM\EntityRepository;

class ChapitresSelectionListener implements EventSubscriberInterface
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

        $programme = $data->getProgramme();
        $matiere = $data->getMatiere();
		
		if (null === $programme || null === $matiere) return;

        $this->populateChapitres($form, $programme->getId(), $matiere->getId());
    }
	
	/**
     * @param event FormEvent
     */
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        if (null === $data) return;
 
        $id_programme = $data['programme'];
        $id_matiere = $data['matiere'];

        $this->populateChapitres($form, $id_programme, $id_matiere);
    }

    protected function populateChapitres($form, $id_programme, $id_matiere)
    {
        $form->add($this->factory->createNamed('chapitre', 'entity', null, array(
			'class'         => 'Majordesk\AppBundle\Entity\Chapitre',
			'property'      => 'nom',
			'attr' => array( 'class' => 'form-control' ),
			'auto_initialize' => false,
			'query_builder' => function (EntityRepository $r) use ($id_programme, $id_matiere) {
								    $qb = $r->createQueryBuilder('c')
									 	    ->join('c.programme', 'p')
									 	    ->join('c.matiere', 'm')
									 	    ->where('p.id = :id_programme')
										    ->andWhere('m.id = :id_matiere')
										    ->setParameter('id_programme', $id_programme)
										    ->setParameter('id_matiere', $id_matiere)
										    ;
								    return $qb;
							    }
		 )));
    }
}