<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModReponse
 *
 * @ORM\Table(name="modreponse")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\ModReponseRepository")
 */
class ModReponse
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
     * @var text
     *
     * @ORM\Column(name="contenu", type="text")
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
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\ModMapping", inversedBy="mod_reponses", cascade={"persist"})
	* @ORM\JoinColumn(nullable=false)
	*/
	private $mod_mapping;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\ModBrique", inversedBy="mod_reponses")
	*/
	private $mod_brique;
	
	/**
	* @ORM\ManyToMany(targetEntity="Majordesk\AppBundle\Entity\Tag", mappedBy="mod_reponses", cascade={"persist"})
	*/
	private $tags;

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
     * @return ModReponse
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
     * @return ModReponse
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
     * @return ModReponse
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
     * Set mod_mapping
     *
     * @param \Majordesk\AppBundle\Entity\ModMapping $modMapping
     * @return ModReponse
     */
    public function setModMapping(\Majordesk\AppBundle\Entity\ModMapping $modMapping)
    {
        $this->mod_mapping = $modMapping;
    
        return $this;
    }

    /**
     * Get mod_mapping
     *
     * @return \Majordesk\AppBundle\Entity\ModMapping 
     */
    public function getModMapping()
    {
        return $this->mod_mapping;
    }
	
	/**
     * Add tags
     *
     * @param \Majordesk\AppBundle\Entity\Tag $tag
     * @return Tag
     */
    public function addTag(\Majordesk\AppBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;
		$tag->addModReponse($this);
    
        return $this;
    }

    /**
     * Remove tags
     *
     * @param \Majordesk\AppBundle\Entity\Tag $tag
     */
    public function removeTag(\Majordesk\AppBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
		$tag->removeModReponse($this);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }
	
	
	/**
     * Set mod_brique
     *
     * @param \Majordesk\AppBundle\Entity\ModBrique $modBrique
     * @return ModReponse
     */
    public function setModBrique(\Majordesk\AppBundle\Entity\ModBrique $modBrique)
    {
        $this->mod_brique = $modBrique;
    
        return $this;
    }

    /**
     * Get mod_brique
     *
     * @return \Majordesk\AppBundle\Entity\modBrique 
     */
    public function getModBrique()
    {
        return $this->mod_brique;
    }
	
	/**
     */
    public function isAlt()
    {
		$thisNum = 0;
		$isAlt = false;
        foreach($this->mod_mapping->getModReponses() as $mod_reponse) {
			if ($mod_reponse->getNumero() == $this->numero) {
				$thisNum++;
				if ($thisNum > 1) {
					$isAlt = true;
					break;
				}
			}
		}
		return $isAlt;
    }
	
	
	public function __construct()
	{
		$this->contenu = '';
		$this->type = 'expression exacte';
	}
}