<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Disponibilite
 *
 * @ORM\Table(name="disponibilite")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\DisponibiliteRepository")
 */
class Disponibilite
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
     * @ORM\Column(name="jour", type="string", length=15)
	 * @Assert\Choice(choices = {"Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"}, message = "Le jour choisi n'est pas valide.")
     */
    private $jour;

    /**
     * @var string
     *
     * @ORM\Column(name="heure_debut", type="string", length=15, nullable=true)
     */
    private $heureDebut;

    /**
     * @var string
     *
     * @ORM\Column(name="heure_fin", type="string", length=15, nullable=true)
     */
    private $heureFin;

    /**
     * @var string
     *
     * @ORM\Column(name="actif", type="string", length=1)
     */
    private $actif;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Professeur", inversedBy="disponibilites")
	*/
	private $professeur;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Eleve", inversedBy="disponibilites")
	*/
	private $eleve;
	

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
     * Set jour
     *
     * @param string $jour
     * @return Disponibilite
     */
    public function setJour($jour)
    {
        $this->jour = $jour;
    
        return $this;
    }

    /**
     * Get jour
     *
     * @return string 
     */
    public function getJour()
    {
        return $this->jour;
    }

    /**
     * Set heureDebut
     *
     * @param string $heureDebut
     * @return Disponibilite
     */
    public function setHeureDebut($heureDebut)
    {
        $this->heureDebut = $heureDebut;
    
        return $this;
    }

    /**
     * Get heureDebut
     *
     * @return string 
     */
    public function getHeureDebut()
    {
        return $this->heureDebut;
    }

    /**
     * Set heureFin
     *
     * @param string $heureFin
     * @return Disponibilite
     */
    public function setHeureFin($heureFin)
    {
        $this->heureFin = $heureFin;
    
        return $this;
    }

    /**
     * Get heureFin
     *
     * @return string
     */
    public function getHeureFin()
    {
        return $this->heureFin;
    }

    /**
     * Set actif
     *
     * @param string $actif
     * @return Disponibilite
     */
    public function setActif($actif)
    {
        $this->actif = $actif;
    
        return $this;
    }

    /**
     * Get actif
     *
     * @return boolean 
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * Set professeur
     *
     * @param \Majordesk\AppBundle\Entity\Professeur $professeur
     * @return Disponibilite
     */
    public function setProfesseur(\Majordesk\AppBundle\Entity\Professeur $professeur = null)
    {
        $this->professeur = $professeur;
    
        return $this;
    }

    /**
     * Get professeur
     *
     * @return \Majordesk\AppBundle\Entity\Professeur 
     */
    public function getProfesseur()
    {
        return $this->professeur;
    }

    /**
     * Set eleve
     *
     * @param \Majordesk\AppBundle\Entity\Eleve $eleve
     * @return Disponibilite
     */
    public function setEleve(\Majordesk\AppBundle\Entity\Eleve $eleve = null)
    {
        $this->eleve = $eleve;
    
        return $this;
    }

    /**
     * Get eleve
     *
     * @return \Majordesk\AppBundle\Entity\Eleve 
     */
    public function getEleve()
    {
        return $this->eleve;
    }
	
	public function __construct()
	{
		$this->actif = '1';
	}
}