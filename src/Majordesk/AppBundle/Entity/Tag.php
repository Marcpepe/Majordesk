<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\TagRepository")
 */
class Tag
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
     * @ORM\Column(name="nom", type="string", length=255, unique=true)
     */
    private $nom;
	
	/**
	* @ORM\ManyToMany(targetEntity="Majordesk\AppBundle\Entity\ModReponse", inversedBy="tags")
	*/
	private $mod_reponses;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Matiere", inversedBy="tags")
	*/
	private $matiere;
	
	/**
     * @ORM\ManyToMany(targetEntity="Majordesk\AppBundle\Entity\Tag", mappedBy="c_tags", cascade={"persist"})
     */
    private $p_tags;

    /**
     * @ORM\ManyToMany(targetEntity="Majordesk\AppBundle\Entity\Tag", inversedBy="p_tags", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="tagmap",
     *      joinColumns={@ORM\JoinColumn(name="c_tag_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="p_tag_id", referencedColumnName="id")}
     *      )
     */
    private $c_tags;


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
     * Set nom
     *
     * @param string $nom
     * @return Tag
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }
	
	/**
     * Set matiere
     *
     * @param \Majordesk\AppBundle\Entity\Matiere $matiere
     * @return Chapitre
     */
    public function setMatiere(\Majordesk\AppBundle\Entity\Matiere $matiere)
    {
        $this->matiere = $matiere;
    
        return $this;
    }

    /**
     * Get matiere
     *
     * @return \Majordesk\AppBundle\Entity\Matiere 
     */
    public function getMatiere()
    {
        return $this->matiere;
    }
    
    /**
     * Add mod_reponses
     *
     * @param \Majordesk\AppBundle\Entity\ModReponse $modReponse
     * @return Tag
     */
    public function addModReponse(\Majordesk\AppBundle\Entity\ModReponse $modReponse)
    {
        $this->mod_reponses[] = $modReponse;
    
        return $this;
    }

    /**
     * Remove mod_reponses
     *
     * @param \Majordesk\AppBundle\Entity\ModReponse $modReponse
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
     * Add p_tags
     *
     * @param \Majordesk\AppBundle\Entity\Tag $p_tag
     * @return Tag
     */
    public function addPTag(\Majordesk\AppBundle\Entity\Tag $p_tag)
    {
        $this->p_tags[] = $p_tag;
		$p_tag->addCTag($this);
    
        return $this;
    }

    /**
     * Remove p_tags
     *
     * @param \Majordesk\AppBundle\Entity\Tag $p_tag
     */
    public function removePTag(\Majordesk\AppBundle\Entity\Tag $p_tag)
    {
        $this->p_tags->removeElement($p_tag);
		$p_tag->removeCTag($this);
    }

    /**
     * Get p_tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPTags()
    {
        return $this->p_tags;
    }
	
	/**
     * Add c_tags
     *
     * @param \Majordesk\AppBundle\Entity\Tag $c_tag
     * @return Tag
     */
    public function addCTag(\Majordesk\AppBundle\Entity\Tag $c_tag)
    {
        $this->c_tags[] = $c_tag;
		// $c_tag->addPTag($this);
    
        return $this;
    }

    /**
     * Remove c_tags
     *
     * @param \Majordesk\AppBundle\Entity\Tag $c_tag
     */
    public function removeCTag(\Majordesk\AppBundle\Entity\Tag $c_tag)
    {
        $this->c_tags->removeElement($c_tag);
    }

    /**
     * Get c_tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCTags()
    {
        return $this->c_tags;
    }
	
	/**
     * Constructor
     */
    public function __construct()
    {
        $this->p_tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->c_tags = new \Doctrine\Common\Collections\ArrayCollection();
    }
}