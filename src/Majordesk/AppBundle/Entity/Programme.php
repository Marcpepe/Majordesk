<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Programme
 *
 * @ORM\Table(name="programme")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\ProgrammeRepository")
 */
class Programme
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
     * @var smallint
     *
     * @ORM\Column(name="numero", type="smallint")
     */
    private $numero;
	
	/**
     * @var string
     *
     * @ORM\Column(name="categorie", type="string", length=1)
     */
    private $categorie;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="plateforme", type="boolean")
     */
    private $plateforme;
	
	/**
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\ModExercice", mappedBy="programme")
	*/
	private $mod_exercices;
	
	/**
	* @ORM\ManyToMany(targetEntity="Majordesk\AppBundle\Entity\Matiere")
	*/
	private $matieres;
	
	/**
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\Eleve", mappedBy="programme")
	*/
	private $eleves;
	
	/**
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\Chapitre", mappedBy="programme", cascade={"persist"})
	*/
	private $chapitres;


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
     * @return Programme
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
     * Set numero
     *
     * @param string $numero
     * @return Programme
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
     * Set categorie
     *
     * @param string $categorie
     * @return Programme
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
    
        return $this;
    }

    /**
     * Get categorie
     *
     * @return string 
     */
    public function getCategorie()
    {
        return $this->categorie;
    }
	
	/**
     * Set plateforme
     *
     * @param string $plateforme
     * @return Programme
     */
    public function setPlateforme($plateforme)
    {
        $this->plateforme = $plateforme;
    
        return $this;
    }

    /**
     * Get plateforme
     *
     * @return string 
     */
    public function getPlateforme()
    {
        return $this->plateforme;
    }
    
    /**
     * Add mod_exercices
     *
     * @param \Majordesk\AppBundle\Entity\ModExercice $modExercice
     * @return Programme
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
     * Add chapitres
     *
     * @param \Majordesk\AppBundle\Entity\Chapitre $chapitre
     * @return Programme
     */
    public function addChapitre(\Majordesk\AppBundle\Entity\Chapitre $chapitre)
    {
        $this->chapitres[] = $chapitre;
		$chapitre->setProgramme($this);
    
        return $this;
    }

    /**
     * Remove chapitres
     *
     * @param \Majordesk\AppBundle\Entity\Chapitre $chapitre
     */
    public function removeChapitre(\Majordesk\AppBundle\Entity\Chapitre $chapitre)
    {
        $this->chapitres->removeElement($chapitre);
    }

    /**
     * Get chapitres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChapitres()
    {
        return $this->chapitres;
    }

    /**
     * Add matieres
     *
     * @param \Majordesk\AppBundle\Entity\Matiere $matieres
     * @return Programme
     */
    public function addMatiere(\Majordesk\AppBundle\Entity\Matiere $matieres)
    {
        $this->matieres[] = $matieres;
    
        return $this;
    }

    /**
     * Remove matieres
     *
     * @param \Majordesk\AppBundle\Entity\Matiere $matieres
     */
    public function removeMatiere(\Majordesk\AppBundle\Entity\Matiere $matieres)
    {
        $this->matieres->removeElement($matieres);
    }

    /**
     * Get matieres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMatieres()
    {
        return $this->matieres;
    }
	
	/**
     * Add eleves
     *
     * @param \Majordesk\AppBundle\Entity\Eleve $eleve
     * @return Matiere
     */
    public function addEleve(\Majordesk\AppBundle\Entity\Eleve $eleve)
    {
        $this->eleves[] = $eleve;
    
        return $this;
    }

    /**
     * Remove eleves
     *
     * @param \Majordesk\AppBundle\Entity\Eleve $eleve
     */
    public function removeEleve(\Majordesk\AppBundle\Entity\Eleve $eleve)
    {
        $this->eleves->removeElement($eleve);
    }

    /**
     * Get eleves
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEleves()
    {
        return $this->eleves;
    }
	
	/**
     * Constructor
     */
    public function __construct()
    {
        $this->mod_exercices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->matieres = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categorie = 0;
        $this->plateforme = 0;
    }
}