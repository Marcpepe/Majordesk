<?php

namespace Majordesk\AppBundle\Service;

use Doctrine\ORM\EntityManager;
 
class DoctrineManager
{
	protected $em;

	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}
	
	/**
     * @info Permet de supprimer les entités présentes dans $initialArray, mais pas dans $newArray
     */
	public function locateEntitiesToDelete($initialArray, $newArray, $toTrash)  //use array_intersect ( ?
	{
		if ( !is_array($newArray) ) {
			foreach( $initialArray as $initial) 
			{
				if(!$newArray->contains($initial)) 
				{
					$toTrash->add($initial);
				}
			}			
		}
		else {
			foreach( $initialArray as $initial) 
			{
				if(!in_array($initial, $newArray))
				{
					$toTrash->add($initial);
				}
			}
		}
		
		return $toTrash;
	}
	
	/**
     * @info Permet de supprimer les entités présentes dans $initialArray, mais pas dans $newArray
     */
	public function flushEntitiesToDelete($toTrash)
	{
		foreach( $toTrash as $trash)
		{
			$this->em->remove($trash);				
		}
	}
}