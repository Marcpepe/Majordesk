<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ModElementRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ModElementRepository extends EntityRepository
{
	/**
	 * @return query_builder des mod_elements d'un mod_exercice ordonn�s par leur num�ro
	 */
	public function getOrderedModElementsByModMacroId($id)
	{
		$qb = $this->createQueryBuilder('mel')	   
				   ->join('mel.mod_macro', 'mm')
				   ->where('mm.id = :id')
				   ->setParameter('id', $id)
				   ->orderBy('mel.numero', 'ASC');
			
		return $qb->getQuery()
			      ->getResult();
	}

	/**
	 * @return query_builder des mod_elements d'un mod_exercice ordonn�s par leur num�ro
	 */
	public function getOrderedModElementsExerciceByModExerciceId($id)
	{
		$qb = $this->createQueryBuilder('mel')			   
				   ->join('mel.mod_macro', 'mm')
				   ->join('mm.mod_exercice', 'me')
				   ->where('me.id = :id')
				   ->setParameter('id', $id)
				   // ->orderBy('mel.numero', 'ASC');
				   ->orderBy('mm.numero', 'ASC')
				   ->addOrderBy('mel.numero', 'ASC');
			
		return $qb->getQuery()
			      ->getResult();
	}
	
	/**
	 * @return query_builder des mod_elements d'une mod_question d'un mod_exercice ordonn�s par leur num�ro
	 */
	public function getOrderedModElementsByModExerciceId($id)
	{
		$qb = $this->createQueryBuilder('mel')			   
				   ->join('mel.mod_macro', 'mm')
				   ->join('mm.mod_question', 'mq')
				   ->join('mq.mod_exercice', 'me')	   
				   ->where('me.id = :id')
				   ->setParameter('id', $id)
				   // ->orderBy('mel.numero', 'ASC');
				   ->orderBy('mm.numero', 'ASC')
				   ->addOrderBy('mel.numero', 'ASC');
			
		return $qb->getQuery()
			      ->getResult();
	}
}
