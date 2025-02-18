<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ModReponseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ModReponseRepository extends EntityRepository
{
	/**
	 * @return query_builder des mod_reponses d'un mod_mapping ordonn�s par leur num�ro
	 */
	public function getOrderedModReponsesByModExercice($id)
	{
		$qb = $this->createQueryBuilder('mr')   
				   ->join('mr.mod_mapping', 'mm')
				   ->join('mm.mod_question', 'mq')
				   ->join('mq.mod_exercice', 'me')
				   ->where('me.id = :id')
				   ->setParameter('id', $id)
				   ->orderBy('mr.numero', 'ASC');
			
		return $qb->getQuery()
			      ->getResult();
	}

	/**
	 * @return query_builder des mod_reponses d'un mod_mapping ordonn�s par leur num�ro
	 */
	public function getOrderedModReponsesByModMappingId($id)
	{
		$qb = $this->createQueryBuilder('mr')   
				   ->join('mr.mod_mapping', 'mm')
				   ->where('mm.id = :id')
				   ->setParameter('id', $id)
				   ->orderBy('mr.numero', 'ASC');
			
		return $qb->getQuery()
			      ->getResult();
	}
	
	/**
	 * @return
	 */
	public function incrementModReponsesInMapping($id_mapping, $numero, $incr)
	{	
		$qb = $this->_em->createQueryBuilder()
		           ->update('Majordesk\AppBundle\Entity\ModReponse','mr')
				   ->set('mr.numero', 'mr.numero + :incr')
				   ->where('mr.mod_mapping = :id_mapping') // car on ne peut pas faire de join dans un update (cf http://stackoverflow.com/questions/15293502/doctrine-query-builder-not-working-with-update-and-inner-join)
				   ->andWhere('mr.numero >= :numero')
				   ->setParameter('id_mapping', $id_mapping)
				   ->setParameter('numero', $numero)
				   ->setParameter('incr', $incr)
				   ;

		$qb->getQuery()
		   ->execute(); 
	}
}
