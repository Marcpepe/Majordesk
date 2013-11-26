<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Matiere
 *
 * @ORM\Table(name="matiere")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\MatiereRepository")
 */
class Matiere
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
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\ModExercice", mappedBy="matiere")
	*/
	private $mod_exercices;
	
	/**
	 * @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\EleveMatiere", mappedBy="matiere", cascade={"persist"})
	 */
	private $eleve_matieres;
	
	/**
	* @ORM\ManyToMany(targetEntity="Majordesk\AppBundle\Entity\Professeur", mappedBy="matieres")
	*/
	private $professeurs;
	
	/**
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\Tag", mappedBy="matiere", cascade={"persist"})
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
     * Set nom
     *
     * @param string $nom
     * @return Matiere
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
     * Add mod_exercices
     *
     * @param \Majordesk\AppBundle\Entity\ModExercice $modExercice
     * @return Matiere
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
     * Add eleve_matiere
     *
     * @param \Majordesk\AppBundle\Entity\EleveMatiere $eleve_matiere
     * @return Eleve
     */
    public function addEleveMatiere(\Majordesk\AppBundle\Entity\EleveMatiere $eleve_matiere)
    {
		$this->eleve_matieres[] = $eleve_matiere;
		$eleve_matiere->setMatiere($this);
			
        return $this;
    }

    /**
     * Remove eleve_matiere
     *
     * @param \Majordesk\AppBundle\Entity\EleveMatiere $eleve_matiere
     */
    public function removeEleveMatiere(\Majordesk\AppBundle\Entity\EleveMatiere $eleve_matiere)
    {
        $this->matieres->removeElement($eleve_matiere);
    }

    /**
     * Get matieres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEleveMatieres()
    {
        return $this->eleve_matieres;
    }
	
	/**
     * Get eleves
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEleves()
    {
		$eleves = array();
		foreach($this->eleve_matieres as $eleve_matiere) {
			$eleves[] = $eleve_matiere->getEleve();
		}
        return $eleves;
    }
	
	/**
     * Add professeur
     *
     * @param \Majordesk\AppBundle\Entity\Professeur $professeur
     * @return Matiere
     */
    public function addProfesseur(\Majordesk\AppBundle\Entity\Professeur $professeur)
    {
        $this->professeurs[] = $professeur;
    
        return $this;
    }

    /**
     * Remove professeurs
     *
     * @param \Majordesk\AppBundle\Entity\Professeur $professeur
     */
    public function removeProfesseur(\Majordesk\AppBundle\Entity\Professeur $professeur)
    {
        $this->professeurs->removeElement($professeur);
    }

    /**
     * Get professeurs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProfesseurs()
    {
        return $this->professeurs;
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
     * Constructor
     */
    public function __construct()
    {
        $this->mod_exercices = new \Doctrine\Common\Collections\ArrayCollection();
    }
}