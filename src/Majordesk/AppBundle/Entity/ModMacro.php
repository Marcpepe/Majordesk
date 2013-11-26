<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModMacro
 *
 * @ORM\Table(name="modmacro")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\ModMacroRepository")
 */
class ModMacro
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
     * @var integer
     *
     * @ORM\Column(name="couche", type="smallint")
     */
    private $couche;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=25)
     */
    private $type;
	
	/**
     * @var smallint
     *
     * @ORM\Column(name="fond", type="smallint")
     */
    private $fond;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\ModQuestion", inversedBy="mod_macros")
	*/
	private $mod_question;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\ModExercice", inversedBy="mod_macros")
	*/
	private $mod_exercice;
	
	/**
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\ModElement", mappedBy="mod_macro", cascade={"persist", "remove"})
	*/
	private $mod_elements;


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
     * Set numero
     *
     * @param string $numero
     * @return ModElement
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    
        return $this;
    }

    /**
     * Get numero
     *
     * @return string 
     */
    public function getNumero()
    {
        return $this->numero;
    }
	
	/**
     * Set couche
     *
     * @param string $couche
     * @return ModElement
     */
    public function setCouche($couche)
    {
        $this->couche = $couche;
    
        return $this;
    }

    /**
     * Get couche
     *
     * @return string 
     */
    public function getCouche()
    {
        return $this->couche;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return ModMacro
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
     * Set fond
     *
     * @param string $fond
     * @return ModMacro
     */
    public function setFond($fond)
    {
        $this->fond = $fond;
    
        return $this;
    }

    /**
     * Get fond
     *
     * @return string 
     */
    public function getFond()
    {
        return $this->fond;
    }
    
    /**
     * Set mod_question
     *
     * @param \Majordesk\AppBundle\Entity\ModQuestion $modQuestion
     * @return ModMacro
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
     * Set mod_exercice
     *
     * @param \Majordesk\AppBundle\Entity\ModExercice $modExercice
     * @return ModMacro
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
     * Add mod_elements
     *
     * @param \Majordesk\AppBundle\Entity\ModElement $modElements
     * @return ModMacro
     */
	 
    public function addModElement(\Majordesk\AppBundle\Entity\ModElement $modElement)
    {
        $this->mod_elements[] = $modElement;
		$modElement->setModMacro($this);
		
        return $this;
    }

    /**
     * Remove mod_elements
     *
     * @param \Majordesk\AppBundle\Entity\ModElement $modElements
     */
    public function removeModElement(\Majordesk\AppBundle\Entity\ModElement $modElement)
    {
        $this->mod_elements->removeElement($modElement);
    }
	
	/**
     * Get mod_elements
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getElementsNumberByType($type)
    {
		$compteur = 0;
        foreach( $this->mod_elements as $mod_element ) {
			if ($mod_element->getType() == $type) {
				$compteur++;
			}
		}
		return $compteur;
    }

    /**
     * Get mod_elements
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModElements()
    {
        return $this->mod_elements;
    }
	
	/**
     * Get sorted mod_elements
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModElementsByNumero()
    {
        $this->sortModElements();
		return $this;
    }
	
	/**
     * Get mod_elements
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function sortModElements()
    {
		$modElements = $this->mod_elements->toArray();
		usort($modElements, function($a, $b)
		{
			if ($a->getNumero() == $b->getNumero()) {
				return 0;
			}
			return ($a->getNumero() < $b->getNumero()) ? -1 : 1;
		});
		
		$newModElements = new \Doctrine\Common\Collections\ArrayCollection();
		foreach($modElements as $modElement)
		{
			$newModElements->add($modElement);
		}
		
		$this->mod_elements = $newModElements;
    }
	
	/**
     * Constructor
     */
    public function __construct()
    {
		$this->type = 'aucune';
		$this->couche = 0;
        $this->mod_elements = new \Doctrine\Common\Collections\ArrayCollection();
    }
}