<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModBrique
 *
 * @ORM\Table(name="modbrique")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\ModBriqueRepository")
 */
class ModBrique
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
     * @ORM\Column(name="type", type="string", length=50)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="clavier", type="string", length=50, nullable=true)
     */
    private $clavier;

    /**
     * @var integer
     *
     * @ORM\Column(name="couche", type="smallint")
     */
    private $couche;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\ModQuestion", inversedBy="mod_briques", cascade={"persist"})
	*/
	private $mod_question;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\ModComplement", inversedBy="mod_briques", cascade={"persist"})
	*/
	private $mod_complement;
	
	/**
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\ModReponse", mappedBy="mod_brique", cascade={"persist", "remove"})
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
     * Set contenu
     *
     * @param string $contenu
     * @return ModBrique
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
     * @param integer $numero
     * @return ModBrique
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
     * Set clavier
     *
     * @param string $clavier
     * @return ModBrique
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
     * Set couche
     *
     * @param integer $couche
     * @return ModBrique
     */
    public function setCouche($couche)
    {
        $this->couche = $couche;
    
        return $this;
    }

    /**
     * Get couche
     *
     * @return integer 
     */
    public function getCouche()
    {
        return $this->couche;
    }
	
	/**
     * Set mod_question
     *
     * @param string $mod_question
     * @return ModBrique
     */
    public function setModQuestion($mod_question)
    {
        $this->mod_question = $mod_question;
    
        return $this;
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
     * Set mod_complement
     *
     * @param string $v
     * @return ModBrique
     */
    public function setModComplement($mod_complement)
    {
        $this->mod_complement = $mod_complement;
    
        return $this;
    }

    /**
     * Get mod_complement
     *
     * @return string 
     */
    public function getModComplement()
    {
        return $this->mod_complement;
    }
	
	/**
     * Add mod_reponses
     *
     * @param \Majordesk\AppBundle\Entity\ModReponse $modReponses
     * @return ModBrique
     */
    public function addModReponse(\Majordesk\AppBundle\Entity\ModReponse $modReponse)
    {
        $this->mod_reponses[] = $modReponse;
		$modReponse->setModBrique($this);
		
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
     * Get ppn
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlusPetitNumeroModReponse()
    {
		$ppn = 0;
        foreach($this->mod_reponses as $mod_reponse) {
			if ($ppn != 0) {
				$ppn = min($ppn, $mod_reponse->getNumero());
			} else {
				$ppn = $mod_reponse->getNumero();
			}
		}
		return $ppn;
    }

	/**
     * Get le nombre de mod_reponse ayant un numéro différent (cad non alternative l'une de l'autre)
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDifferentModReponsesCount()
    {
		$indices = array();
        foreach($this->mod_reponses as $mod_reponse) {
			$numero = $mod_reponse->getNumero();
			if (!in_array($numero, $indices)) {
				$indices[] = $numero;
			}
		}
		return count($indices);
    }
	
	/**
     * Constructor
     */
    public function __construct()
    {
		$this->couche = 0;
    }
}
