<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModComplement
 *
 * @ORM\Table(name="modcomplement")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\ModComplementRepository")
 */
class ModComplement
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
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=50)
     */
    private $type;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\ModQuestion", inversedBy="mod_complements", cascade={"persist"})
	*/
	private $mod_question;
	
	/**
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\ModBrique", mappedBy="mod_complement", cascade={"persist", "remove"})
	*/
	private $mod_briques;


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
     * @param integer $numero
     * @return ModComplement
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    
        return $this;
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
     * Set type
     *
     * @param string $type
     * @return ModComplement
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
     * Add mod_briques
     *
     * @param \Majordesk\AppBundle\Entity\ModBrique $modBriques
     * @return ModComplement
     */
    public function addModBrique(\Majordesk\AppBundle\Entity\ModBrique $modBrique)
    {
        $this->mod_briques[] = $modBrique;
		$modBrique->setModComplement($this);
		
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
		// $modBrique->setModComplement(null);
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
     * Get mod_question
     *
     * @return string 
     */
    public function getModQuestion()
    {
        return $this->mod_question;
    }
	
	/**
     * Set mod_question
     *
     * @param string $v
     * @return ModComplement
     */
    public function setModQuestion($mod_question)
    {
        $this->mod_question = $mod_question;
    
        return $this;
    }
	
	/**
     * Constructor
     */
    public function __construct()
    {
		$this->numero = 0;
    }
}
