<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ModQuestionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ModQuestionRepository extends EntityRepository
{
	/**
	 * @return query_builder des mod_questions d'un mod_exercice ordonn�es par leur num�ro
	 */
	public function getOrderedModQuestionsByModExerciceId($id)
	{
		$qb = $this->createQueryBuilder('mq') 
				   ->join('mq.mod_exercice', 'me')
				   ->where('me.id = :id')
				   ->setParameter('id', $id)
				   ->orderBy('mq.numero', 'ASC');
			
		return $qb->getQuery()
			      ->getResult();
	}
	
	/**
	 * @return
	 */
	public function incrementModQuestions($id_exercice, $numero, $incr)
	{	
		$qb = $this->_em->createQueryBuilder()
		           ->update('Majordesk\AppBundle\Entity\ModQuestion','mq')
				   ->set('mq.numero', 'mq.numero + :incr')
				   ->where('mq.mod_exercice = :id_exercice') // car on ne peut pas faire de join dans un update (cf http://stackoverflow.com/questions/15293502/doctrine-query-builder-not-working-with-update-and-inner-join)
				   ->andWhere('mq.numero >= :numero')
				   ->setParameter('id_exercice', $id_exercice)
				   ->setParameter('numero', $numero)
				   ->setParameter('incr', $incr)
				   ;

		$qb->getQuery()
		   ->execute(); 
	}
}
