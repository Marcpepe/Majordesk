<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModQuestion
 *
 * @ORM\Table(name="modquestion")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\ModQuestionRepository")
 */
class ModQuestion
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
     * @var integer
     *
     * @ORM\Column(name="numero", type="smallint")
     */
    private $numero;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\ModExercice", inversedBy="mod_questions", cascade={"persist"})
	* @ORM\JoinColumn(nullable=false)
	*/
	private $mod_exercice;
	
	/**
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\ModMapping", mappedBy="mod_question", cascade={"persist", "remove"})
	*/
	private $mod_mappings;
	
	/**
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\ModBrique", mappedBy="mod_question", cascade={"persist", "remove"})
	*/
	private $mod_briques;
	
	/**
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\ModComplement", mappedBy="mod_question", cascade={"persist", "remove"})
	*/
	private $mod_complements;
	
	/**
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\ModMacro", mappedBy="mod_question", cascade={"persist", "remove"})
	*/
	private $mod_macros;
	
	/**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=50, nullable=true)
     */
    private $type;


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
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     * @return ModQuestion
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    
        return $this;
    }
    
    /**
     * Set mod_exercice
     *
     * @param \Majordesk\AppBundle\Entity\ModExercice $modExercice
     * @return ModQuestion
     */
    public function setModExercice(\Majordesk\AppBundle\Entity\ModExercice $modExercice)
    {
        $this->mod_exercice = $modExercice;
    
        return $this;
    }

    /**
     * Get mod_exercice
     *
     * @return \Majordesk\AppBundle\Entity\ModExercice 
     */
    public function getModExercice()
    {
        return $this->mod_exercice;
    }

    /**
     * Add mod_mappings
     *
     * @param \Majordesk\AppBundle\Entity\ModMapping $modMappings
     * @return ModQuestion
     */
    public function addModMapping(\Majordesk\AppBundle\Entity\ModMapping $modMapping)
    {
        $this->mod_mappings[] = $modMapping;
		$modMapping->setModQuestion($this);
		
        return $this;
    }

    /**
     * Remove mod_mappings
     *
     * @param \Majordesk\AppBundle\Entity\ModMapping $modMappings
     */
    public function removeModMapping(\Majordesk\AppBundle\Entity\ModMapping $modMapping)
    {
        $this->mod_mappings->removeElement($modMapping);
		// $modMapping->setModQuestion(null);
    }

    /**
     * Get mod_mappings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModMappings()
    {
        return $this->mod_mappings;
    }
	
	/**
     * Add mod_briques
     *
     * @param \Majordesk\AppBundle\Entity\ModBrique $modBriques
     * @return ModQuestion
     */
    public function addModBrique(\Majordesk\AppBundle\Entity\ModBrique $modBrique)
    {
        $this->mod_briques[] = $modBrique;
		$modBrique->setModQuestion($this);
		
        return $this;
    }

    /**
     * Remove mod_briques
     *
     * @param \Majordesk\AppBundle\Entity\ModBrique $modBriques
     */
    public function removeModBrique(\Majordesk\AppBundle\Entity\ModBrique $modBrique)
    {
        $this->mod_briques->removeElement($modBrique);
		// $modBrique->setModQuestion(null);
    }

    /**
     * Get mod_briques
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModBriques()
    {
        return $this->mod_briques;
    }
	
	/**
     * Get mod_briques
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrderedModBriques()
    {
		$modBriques = $this->mod_briques->toArray();
		usort($modBriques, function($a, $b)
		{
			if ($a->getNumero() == $b->getNumero()) {
				return 0;
			}
			return ($a->getNumero() < $b->getNumero()) ? -1 : 1;
		});

		return $modBriques;
    }
	
	/**
     * Add mod_complements
     *
     * @param \Majordesk\AppBundle\Entity\ModComplement $modComplement
     * @return ModQuestion
     */
    public function addModComplement(\Majordesk\AppBundle\Entity\ModComplement $modComplement)
    {
        $this->mod_complements[] = $modComplement;
		$modComplement->setModQuestion($this);
		
        return $this;
    }

    /**
     * Remove mod_complements
     *
     * @param \Majordesk\AppBundle\Entity\ModComplement $modBriques
     */
    public function removeModComplement(\Majordesk\AppBundle\Entity\ModComplement $modComplement)
    {
        $this->mod_complements->removeElement($modComplement);
		// $modComplement->setModQuestion(null);
    }

    /**
     * Get mod_complements
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModComplements()
    {
        return $this->mod_complements;
    }
	
	/**
     * Get correction
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCorrection()
    {
        foreach($this->mod_complements as $mod_complement) {
			if ($mod_complement->getType() == 'correction') {
				return $mod_complement;
			}
		}
    }
	
	/**
     * Get indice
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIndice()
    {
        foreach($this->mod_complements as $mod_complement) {
			if ($mod_complement->getType() == 'indice') {
				return $mod_complement;
			}
		}
    }

    /**
     * Add mod_macrots
     *
     * @param \Majordesk\AppBundle\Entity\ModMacro $modMacro
     * @return ModQuestion
     */
    public function addModMacro(\Majordesk\AppBundle\Entity\ModMacro $modMacro)
    {
        $this->mod_macros[] = $modMacro;
		$modMacro->setModQuestion($this);
    
        return $this;
    }

    /**
     * Remove mod_macros
     *
     * @param \Majordesk\AppBundle\Entity\ModMacro $modMacro
     */
    public function removeModMacro(\Majordesk\AppBundle\Entity\ModMacro $modMacro)
    {
        $this->mod_macros->removeElement($modMacro);
		// $modMacro->setModQuestion(null);
    }

    /**
     * Get mod_macros
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModMacros()
    {
        return $this->mod_macros;
    }
	
	/**
     * Get nombre de couches total
     *
     * @return smallint
     */
    public function getNbCouches()
    {
        $couches = array();
		
		foreach($this->mod_macros as $mod_macro) {
			$couche = $mod_macro->getCouche();
			if ( $couche != 0 && !in_array($couche, $couches) ) {
				$couches[] = $couche;
			}
		}
		
        return count($couches);
    }
	
	/**
     * Get mod_macros
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModMacroByType($type)
    {
		foreach($this->mod_macros as $mod_macro) {
			if ($mod_macro->getType() == $type) {
				return $mod_macro;
			}
		}
        return null;
    }
	
	/**
     * Get mod_macros
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function sortModMacros()
    {
		$modMacros = $this->mod_macros->toArray();
		usort($modMacros, function($a, $b)
		{
			if ($a->getNumero() == $b->getNumero()) {
				return 0;
			}
			return ($a->getNumero() < $b->getNumero()) ? -1 : 1;
		});
		
		$newModMacros = new \Doctrine\Common\Collections\ArrayCollection();
		foreach($modMacros as $modMacro)
		{
			$newModMacros->add($modMacro);
		}
		
		$this->mod_macros = $newModMacros;
    }
	
	/**
     * Get mod_mappings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function sortModMappingsByNumeroReponse()
    {
		$modMappings = $this->mod_mappings->toArray();
		
		foreach($modMappings as $modMapping)
		{
			$modMapping->sortModReponses();
		}
		usort($modMappings, function($a, $b)
		{
			if ($a->getModReponse(0)->getNumero() == $b->getModReponse(0)->getNumero()) {
				return 0;
			}
			return ($a->getModReponse(0)->getNumero() < $b->getModReponse(0)->getNumero()) ? -1 : 1;
		});
		
		$newModMappings = new \Doctrine\Common\Collections\ArrayCollection();
		foreach($modMappings as $modMapping)
		{
			$newModMappings->add($modMapping);
		}
		
		$this->mod_mappings = $newModMappings;
    }
	
	/**
     * Get mod_macros
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModMacrosOrderedByNumero()
    {
		$modMacros = $this->mod_macros->toArray();
		usort($modMacros, function($a, $b)
		{
			if ($a->getNumero() == $b->getNumero()) {
				return 0;
			}
			return ($a->getNumero() < $b->getNumero()) ? -1 : 1;
		});

		return $modMacros;
    }
	
	/**
     * Set type
     *
     * @param string $type
     * @return ModBrique
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
     * Constructor
     */
    public function __construct()
    {
        $this->mod_mappings = new \Doctrine\Common\Collections\ArrayCollection();
		$this->numero = 0;
    }
}