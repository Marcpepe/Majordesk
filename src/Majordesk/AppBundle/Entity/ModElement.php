<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModElement
 *
 * @ORM\Table(name="modelement")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\ModElementRepository")
 */
class ModElement
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
     * @ORM\Column(name="contenu", type="text", nullable=true)
     */
    private $contenu;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="numero", type="smallint")
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=25)
     */
    private $type;
	
	/**
     * @var string
     *
     * @ORM\Column(name="clavier", type="string", length=55, nullable=true)
     */
    private $clavier;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\ModMacro", inversedBy="mod_elements")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $mod_macro;


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
     * Set contenu
     *
     * @param string $contenu
     * @return ModElement
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
    
        return $this;
    }

    /**
     * Get contenu
     *
     * @return string 
     */
    public function getContenu()
    {
        return $this->contenu;
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
     * Set type
     *
     * @param string $type
     * @return ModElement
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
     * Set clavier
     *
     * @param string $clavier
     * @return ModElement
     */
    public function setClavier($clavier)
    {
        $this->clavier = $clavier;
    
        return $this;
    }

    /**
     * Get clavier
     *
     * @return string 
     */
    public function getClavier()
    {
        return $this->clavier;
    }
	
	/**
     * Set mod_macro
     *
     * @param \Majordesk\AppBundle\Entity\ModMacro $modMacro
     * @return ModElement
     */
    public function setModMacro(\Majordesk\AppBundle\Entity\ModMacro $modMacro)
    {
        $this->mod_macro = $modMacro;
    
        return $this;
    }

    /**
     * Get mod_macro
     *
     * @return \Majordesk\AppBundle\Entity\ModMacro
     */
    public function getModMacro()
    {
        return $this->mod_macro;
    }
}
