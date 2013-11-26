<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModMapping
 *
 * @ORM\Table(name="modmapping")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\ModMappingRepository")
 */
class ModMapping
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
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=25, nullable=true)
     */
    private $type;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\ModQuestion", inversedBy="mod_mappings", cascade={"persist"})
	* @ORM\JoinColumn(nullable=false)
	*/
	private $mod_question;
	
	/**
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\ModReponse", mappedBy="mod_mapping", cascade={"persist", "remove"})
	*/
	private $mod_reponses;


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
     * Set type
     *
     * @param string $type
     * @return ModMapping
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * Set mod_question
     *
     * @param \Majordesk\AppBundle\Entity\ModQuestion $modQuestion
     * @return ModMapping
     */
    public function setModQuestion(\Majordesk\AppBundle\Entity\ModQuestion $modQuestion)
    {
        $this->mod_question = $modQuestion;
    
        return $this;
    }

    /**
     * Get mod_question
     *
     * @return \Majordesk\AppBundle\Entity\ModQuestion 
     */
    public function getModQuestion()
    {
        return $this->mod_question;
    }

    /**
     * Add mod_reponses
     *
     * @param \Majordesk\AppBundle\Entity\ModReponse $modReponses
     * @return ModMapping
     */
    public function addModReponse(\Majordesk\AppBundle\Entity\ModReponse $modReponse)
    {
        $this->mod_reponses[] = $modReponse;
		$modReponse->setModMapping($this);
		
        return $this;
    }

    /**
     * Remove mod_reponses
     *
     * @param \Majordesk\AppBundle\Entity\ModReponse $modReponses
     */
    public function removeModReponse(\Majordesk\AppBundle\Entity\ModReponse $modReponse)
    {
        $this->mod_reponses->removeElement($modReponse);
    }

    /**
     * Get mod_reponses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModReponses()
    {
        return $this->mod_reponses;
    }
	
	/**
     * Return true si une ModRéponse comportant ce numéro existe
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function hasModReponse($numero)
    {	
		$mod_reponse = $this->selectModReponseByNumero($numero);
        return !empty($mod_reponse);
    }
	
	/**
     * Get le mod_reponse de numero $numero
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function selectModReponseByNumero($numero)
    {
		foreach($this->mod_reponses as $mod_reponse) {
			if ($mod_reponse->getNumero() == $numero) {
				return $mod_reponse;
			}
		}
		return null;
    }
	
	/**
     * Get le mod_reponse de numero $numero
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function selectAllModReponseByNumero($numero)
    {
		$mod_reponses = array();
		foreach($this->mod_reponses as $mod_reponse) {
			if ($mod_reponse->getNumero() == $numero) {
				$mod_reponses[] = $mod_reponse;
			}
		}
		return $mod_reponses;
    }
	
	/**
     * Get le mod_reponse d'indice $key
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModReponse($key)
    {
        return $this->mod_reponses[$key];
    }
	
	/**
     * Get mod_reponses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function sortModReponses()
    {
		$modReponses = $this->mod_reponses->toArray();
		usort($modReponses, function($a, $b)
		{
			if ($a->getNumero() == $b->getNumero()) {
				return 0;
			}
			return ($a->getNumero() < $b->getNumero()) ? -1 : 1;
		});
		
		$newModReponses = new \Doctrine\Common\Collections\ArrayCollection();
		foreach($modReponses as $modReponse)
		{
			$newModReponses->add($modReponse);
		}
		
		$this->mod_reponses = $newModReponses;
    }
	
	/**
     * Check si le ModMapping contient (return true) ou non (return false) plusieurs numéros de réponse. Exemple: Deux réponses 1 alternatives ne comptent pas (returnera false)
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function isMultiple()
    {
		$isMultiple = false;
		$numero = null;
        foreach( $this->mod_reponses as $mod_reponse ) {
			if ($numero == null) {
				$numero = $mod_reponse->getNumero();
			}
			if ($numero != $mod_reponse->getNumero()) {
				$isMultiple = true;
			}
		}
		return $isMultiple;
    }
	
	/**
     * Constructor
     */
    public function __construct()
    {
		// $this->type = 'aucune';
        $this->mod_reponses = new \Doctrine\Common\Collections\ArrayCollection();
    }
}