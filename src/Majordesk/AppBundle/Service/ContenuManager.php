<?php

namespace Majordesk\AppBundle\Service;

use Doctrine\ORM\EntityManager;
 
class ContenuManager
{
	protected $em;

	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}
	
	/**
     * @info Initialise un exercice déjà existant
     */
	public function getModExercicesEnCours($statut, $matieres)
	{
		$ids_exercices_en_cours = array();
		
		foreach($matieres as $matiere)
		{
			$mod_exercice_en_cours = $this->em->getRepository('MajordeskAppBundle:ModExercice')
					                         ->getModExerciceEnCours($statut, $matiere->getId());
			
			if (!empty($mod_exercice_en_cours))
			{
				$id = $mod_exercice_en_cours->getId();
			}
			else
			{
				$id = 0;
			}
			$ids_exercices_en_cours[$matiere->getId()] = $id;
		}
		
		return $ids_exercices_en_cours;
	}
}