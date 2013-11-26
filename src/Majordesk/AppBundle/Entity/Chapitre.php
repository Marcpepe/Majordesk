<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Chapitre
 *
 * @ORM\Table(name="chapitre")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\ChapitreRepository")
 */
class Chapitre
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="numero", type="smallint")
     */
    private $numero;
	
	/**
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\ModExercice", mappedBy="chapitre", cascade={"remove"})
	*/
	private $mod_exercices;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Programme", inversedBy="chapitres")
	*/
	private $programme;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Matiere")
	*/
	private $matiere;
	
	/**
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\Partie", mappedBy="chapitre", cascade={"persist", "remove"})
	*/
	private $parties;


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
     * @return Chapitre
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
     * @return Chapitre
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    
        return $this;
    }
    
    /**
     * Add mod_exercices
     *
     * @param \Majordesk\AppBundle\Entity\ModExercice $modExercice
     * @return Chapitre
     */
    public function addModExercice(\Majordesk\AppBundle\Entity\ModExercice $modExercice)
    {
        $this->mod_exercices[] = $modExercice;
    
        return $this;
    }

    /**
     * Remove mod_exercices
     *
     * @param \Majordesk\AppBundle\Entity\ModExercice $modExercice
     */
    public function removeModExercice(\Majordesk\AppBundle\Entity\ModExercice $modExercice)
    {
        $this->mod_exercices->removeElement($modExercice);
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

    /**
     * Set programme
     *
     * @param \Majordesk\AppBundle\Entity\Programme $programme
     * @return Chapitre
     */
    public function setProgramme(\Majordesk\AppBundle\Entity\Programme $programme = null)
    {
        $this->programme = $programme;
    
        return $this;
    }

    /**
     * Get programme
     *
     * @return \Majordesk\AppBundle\Entity\Programme 
     */
    public function getProgramme()
    {
        return $this->programme;
    }

    /**
     * Set matiere
     *
     * @param \Majordesk\AppBundle\Entity\Matiere $matiere
     * @return Chapitre
     */
    public function setMatiere(\Majordesk\AppBundle\Entity\Matiere $matiere = null)
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
     * Add parties
     *
     * @param \Majordesk\AppBundle\Entity\Partie $partie
     * @return Chapitre
     */
    public function addParty(\Majordesk\AppBundle\Entity\Partie $partie)
    {
        $this->parties[] = $partie;
		$partie->setChapitre($this);
    
        return $this;
    }

    /**
     * Remove parties
     *
     * @param \Majordesk\AppBundle\Entity\Partie $partie
     */
    public function removeParty(\Majordesk\AppBundle\Entity\Partie $partie)
    {
        $this->parties->removeElement($partie);
    }

    /**
     * Get parties
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getParties()
    {
        return $this->parties;
    }
	
	/**
     * Constructor
     */
    public function __construct()
    {
        $this->mod_exercices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->parties = new \Doctrine\Common\Collections\ArrayCollection();
    }
}