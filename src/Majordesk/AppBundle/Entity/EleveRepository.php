<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * EleveRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EleveRepository extends EntityRepository
{	
	/**
	 * @return query_builder des �l�ves n'ayant pas de professeur
	 */
	public function getElevesSansProfesseur()
	{
		return $this->createQueryBuilder('e')
				    ->leftJoin('e.professeurs', 'p')
				    ->where('p IS NULL')
				    ->orderBy('e.nom', 'ASC');
	}
	
	/**
	 * @return $qb query_builder donnant la liste de tous les �l�ves tri�s par order alphab�tique
	 */
	public function getElevesByAlphabeticalOrder()
	{
		return $this->createQueryBuilder('e')
					// ->groupBy('e.username')
					->orderBy('e.nom', 'ASC');
	}
	
	/**
	 * @return query_builder des �l�ves d'un professeur d'id $id
	 */
	public function getElevesFilteredByProfesseur($id)
	{
		$qb = $this->createQueryBuilder('e')
				   ->join('e.professeurs', 'p')
				   ->where('p.id = :id')
				   ->setParameter('id', $id);
			
		return $qb->getQuery()
			      ->getResult();
	}
}
