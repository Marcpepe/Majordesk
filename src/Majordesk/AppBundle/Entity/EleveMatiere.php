<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EleveMatiere
 *
 * @ORM\Table(name="elevematiere")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\EleveMatiereRepository")
 */
class EleveMatiere
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
     * @ORM\Column(name="plateforme", type="string", length=1)
     */
    private $plateforme;

    /**
     * @var string
     *
     * @ORM\Column(name="cours", type="string", length=1)
     */
    private $cours;

    /**
     * @var string
     *
     * @ORM\Column(name="prelevement_plateforme", type="string", length=1)
     */
    private $prelevement_plateforme;

    /**
     * @var string
     *
     * @ORM\Column(name="prelevement_cours", type="string", length=1)
     */
    private $prelevement_cours;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_abonnement", type="date", nullable=true)
     */
    private $date_abonnement;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="heures_prises", type="smallint")
     */
    private $heures_prises;

    /**
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean")
     */
    private $actif;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Eleve", inversedBy="eleve_matieres")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $eleve;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Matiere", inversedBy="eleve_matieres")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $matiere;
	
	/**
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\Paiement", mappedBy="eleve_matiere", cascade={"persist"})
	*/
	private $paiements;


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
     * Set plateforme
     *
     * @param string $plateforme
     * @return EleveMatiere
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
     * Set cours
     *
     * @param string $cours
     * @return EleveMatiere
     */
    public function setCours($cours)
    {
        $this->cours = $cours;
    
        return $this;
    }

    /**
     * Get cours
     *
     * @return string 
     */
    public function getCours()
    {
        return $this->cours;
    }

    /**
     * Set prelevement_plateforme
     *
     * @param string $prelevement_plateforme
     * @return EleveMatiere
     */
    public function setPrelevementPlateforme($prelevement_plateforme)
    {
        $this->prelevement_plateforme = $prelevement_plateforme;
    
        return $this;
    }

    /**
     * Get prelevement_plateforme
     *
     * @return string 
     */
    public function getPrelevementPlateforme()
    {
        return $this->prelevement_plateforme;
    }

    /**
     * Set prelevement_cours
     *
     * @param string $prelevement_cours
     * @return EleveMatiere
     */
    public function setPrelevementCours($prelevement_cours)
    {
        $this->prelevement_cours = $prelevement_cours;
    
        return $this;
    }

    /**
     * Get prelevement_cours
     *
     * @return string 
     */
    public function getPrelevementCours()
    {
        return $this->prelevement_cours;
    }

    /**
     * Set date_abonnement
     *
     * @param \DateTime $date_abonnement
     * @return EleveMatiere
     */
    public function setDateAbonnement($date_abonnement)
    {
        $this->date_abonnement = $date_abonnement;
    
        return $this;
    }

    /**
     * Get date_abonnement
     *
     * @return \DateTime 
     */
    public function getDateAbonnement()
    {
        return $this->date_abonnement;
    }
	
	/**
     * Set heures_prises
     *
     * @param integer $heuresPrises
     * @return Eleve
     */
    public function setHeuresPrises($heuresPrises)
    {
        $this->heures_prises = $heuresPrises;
    
        return $this;
    }

    /**
     * Get heures_prises
     *
     * @return integer 
     */
    public function getHeuresPrises()
    {
        return $this->heures_prises;
    }
	
	/**
     * Set actif
     *
     * @param boolean $actif
     * @return EleveMatiere
     */
    public function isActif()
    {
		$today = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
        if ($this->prelevement_plateforme == 1 && $this->date_abonnement >= $today) {
				return true;
		}
		else {
			return false;
		}
    }

    /**
     * Set actif
     *
     * @param boolean $actif
     * @return EleveMatiere
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
     * Set actif
     *
     * @param boolean $actif
     * @return EleveMatiere
     */
    public function setEleve($eleve)
    {
        $this->eleve = $eleve;
    
        return $this;
    }

    /**
     * Get actif
     *
     * @return boolean 
     */
    public function getEleve()
    {
        return $this->eleve;
    }
	
	/**
     * Set actif
     *
     * @param boolean $actif
     * @return EleveMatiere
     */
    public function setMatiere($matiere)
    {
        $this->matiere = $matiere;
    
        return $this;
    }

    /**
     * Get actif
     *
     * @return boolean 
     */
    public function getMatiere()
    {
        return $this->matiere;
    }
	
	/**
     * Add paiement
     *
     * @param \Majordesk\AppBundle\Entity\Paiement $paiement
     * @return EleveMatiere
     */
    public function addPaiement(\Majordesk\AppBundle\Entity\Paiement $paiement)
    {
        $this->paiements[] = $paiement;
    
        return $this;
    }

    /**
     * Remove paiement
     *
     * @param \Majordesk\AppBundle\Entity\Paiement $paiement
     */
    public function removePaiement(\Majordesk\AppBundle\Entity\Paiement $paiement)
    {
        $this->paiements->removeElement($paiement);
    }

    /**
     * Get paiements
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPaiements()
    {
        return $this->paiements;
    }
	
	public function __construct()
	{
		$this->plateforme = 0;
		$this->cours = 0;
		$this->prelevement_plateforme = 0;
		$this->prelevement_cours = 0;
		$this->heures_prises = 0;
		$this->date_abonnement = null;
		$this->actif = true; // non utilisé
	}
}
