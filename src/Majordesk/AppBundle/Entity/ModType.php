<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModType
 *
 * @ORM\Table(name="modtype")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\ModTypeRepository")
 */
class ModType
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
	
	/**
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\ModExercice", mappedBy="mod_type", cascade={"persist", "remove"})
	*/
	private $mod_exercices;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
	
	/**
     * Add mod_exercice
     *
     * @param \Majordesk\AppBundle\Entity\ModExercice $mod_exercice
     * @return ModExercice
     */
    public function addModExercice(\Majordesk\AppBundle\Entity\ModExercice $mod_exercice)
    {
        $this->mod_exercices[] = $mod_exercice;
    
        return $this;
    }

    /**
     * Remove mod_exercice
     *
     * @param \Majordesk\AppBundle\Entity\ModExercice $mod_exercice
     */
    public function removeModExercice(\Majordesk\AppBundle\Entity\ModExercice $mod_exercice)
    {
        $this->mod_exercices->removeElement($mod_exercice);
    }

    /**
     * Get mod_exercices
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModExercices()
    {
        return $this->mod_exercices;
    }
}
