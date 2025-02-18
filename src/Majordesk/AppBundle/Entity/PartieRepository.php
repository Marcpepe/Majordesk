<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * PartieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PartieRepository extends EntityRepository
{
	/**
	 * @return array des chapitres correspondant � une matiere
	 */
	public function search($query)
	{
		$qb = $this->createQueryBuilder('pa');
		$qb		   ->join('pa.chapitre','c')
				   ->join('c.programme','p')
				   ->where(
					  $qb->expr()->like('pa.nom', ':query')
				   )
				   ->setParameter('query', '%'.$query.'%')
				   ->orderBy('p.id')
				   ;		   				   
			
		return $qb->getQuery()
			      ->getResult();
	}
	
	/**
	 * @return array des parties correspondant � une matiere //obsol�te?
	 */
	public function getPartiesByMatiere($id)
	{
		$qb = $this->createQueryBuilder('p')
				   ->join('p.chapitre', 'c')
				   ->join('c.matiere', 'm')
				   ->where('m.id = :id')
				   ->setParameter('id', $id)
				   ->orderBy('p.numero', 'ASC');		   				   
			
		return $qb->getQuery()
			      ->getResult();
	}
	
	/**
	 * @return array des parties correspondant � une matiere et un programme
	 */
	public function getPartiesByMatiereAndProgramme($id_matiere, $id_programme)
	{
		$qb = $this->createQueryBuilder('pa')
				   ->join('pa.chapitre', 'c')
				   ->join('c.matiere', 'm')
				   ->join('c.programme', 'p')
				   ->where('m.id = :id_matiere')				   
				   ->andWhere('p.id = :id_programme')
				   ->setParameter('id_matiere', $id_matiere)	
				   ->setParameter('id_programme', $id_programme)
				   ->orderBy('pa.numero', 'ASC');		   				   
			
		return $qb->getQuery()
			      ->getResult();
	}
	
	/**
	 * @return array des parties correspondant � un chapitre
	 */
	public function getPartiesByChapitre($id_chapitre)
	{
		$qb = $this->createQueryBuilder('p')
				   ->join('p.chapitre', 'c')
				   ->where('c.id = :id_chapitre')
				   ->setParameter('id_chapitre', $id_chapitre)
				   ->orderBy('p.numero', 'ASC');		   				   
			
		return $qb->getQuery()
			      ->getResult();
	}
	
	/**
	 * @return array de la partie de numero $numero dans le chapitre d'id $id_chapitre
	 */
	public function getPartieInChapitreByNumero($id_chapitre, $numero)
	{
		$qb = $this->createQueryBuilder('p')
				   ->join('p.chapitre', 'c')
				   ->where('c.id = :id_chapitre')
				   ->andWhere('p.numero = :numero')
				   ->setParameter('id_chapitre', $id_chapitre)
				   ->setParameter('numero', $numero)
				   ->setMaxResults(1);
			
		return $qb->getQuery()
			      ->getOneOrNullResult();
	}
}
