<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Partie
 *
 * @ORM\Table(name="partie")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\PartieRepository")
 */
class Partie
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
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\ModExercice", mappedBy="partie", cascade={"remove"})
	*/
	private $mod_exercices;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Chapitre", inversedBy="parties")
	*/
	private $chapitre;


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
     * @return Partie
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
     * @return Partie
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
     * Set chapitre
     *
     * @param \Majordesk\AppBundle\Entity\Chapitre $chapitre
     * @return Partie
     */
    public function setChapitre(\Majordesk\AppBundle\Entity\Chapitre $chapitre = null)
    {
        $this->chapitre = $chapitre;
    
        return $this;
    }

    /**
     * Get chapitre
     *
     * @return \Majordesk\AppBundle\Entity\Chapitre 
     */
    public function getChapitre()
    {
        return $this->chapitre;
    }
	
	/**
     * Constructor
     */
    public function __construct()
    {
        $this->mod_exercices = new \Doctrine\Common\Collections\ArrayCollection();
    }
}