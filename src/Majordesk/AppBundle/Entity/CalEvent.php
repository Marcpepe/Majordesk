<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CalEvent
 *
 * @ORM\Table(name="calevent")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\CalEventRepository")
 */
class CalEvent
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
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_enregistrement", type="datetime")
     */
    private $dateEnregistrement;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_cours", type="date")
     */
    private $dateCours;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="heure_debut", type="string", length=15)
     */
    private $heureDebut;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="heure_fin", type="string", length=15)
     */
    private $heureFin;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=1)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="reservation", type="string", length=1)
     */
    private $reservation;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=1)
     */
    private $statut;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Professeur", inversedBy="cal_events")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $professeur;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Eleve", inversedBy="cal_events")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $eleve;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Matiere")
	*/
	private $matiere;
	
	/**
	* @ORM\ManyToMany(targetEntity="Majordesk\AppBundle\Entity\Chapitre")
	*/
	private $chapitres;
	
	/**
	* @ORM\OneToOne(targetEntity="Majordesk\AppBundle\Entity\Ticket", inversedBy="cal_event")
	*/
	private $ticket;


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
     * Set titre
     *
     * @param string $titre
     * @return CalEvent
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    
        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set dateEnregistrement
     *
     * @param \DateTime $dateEnregistrement
     * @return CalEvent
     */
    public function setDateEnregistrement($dateEnregistrement)
    {
        $this->dateEnregistrement = $dateEnregistrement;
    
        return $this;
    }

    /**
     * Get dateEnregistrement
     *
     * @return \DateTime 
     */
    public function getDateEnregistrement()
    {
        return $this->dateEnregistrement;
    }
	
	/**
     * Set dateCours
     *
     * @param \DateTime $dateCours
     * @return CalEvent
     */
    public function setDateCours($dateCours)
    {
        $this->dateCours = $dateCours;
    
        return $this;
    }

    /**
     * Get dateCours
     *
     * @return \DateTime 
     */
    public function getDateCours()
    {
        return $this->dateCours;
    }

    /**
     * Set heureDebut
     *
     * @param \DateTime $heureDebut
     * @return CalEvent
     */
    public function setHeureDebut($heureDebut)
    {
        $this->heureDebut = $heureDebut;
    
        return $this;
    }

    /**
     * Get heureDebut
     *
     * @return \DateTime 
     */
    public function getHeureDebut()
    {
        return $this->heureDebut;
    }
	
	/**
     * Set heureFin
     *
     * @param \DateTime $heureFin
     * @return CalEvent
     */
    public function setHeureFin($heureFin)
    {
        $this->heureFin = $heureFin;
    
        return $this;
    }

    /**
     * Get heureFin
     *
     * @return \DateTime 
     */
    public function getHeureFin()
    {
        return $this->heureFin;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return CalEvent
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
     * Set reservation
     *
     * @param string $reservation
     * @return CalEvent
     */
    public function setReservation($reservation)
    {
        $this->reservation = $reservation;
    
        return $this;
    }

    /**
     * Get reservation
     *
     * @return string 
     */
    public function getReservation()
    {
        return $this->reservation;
    }

    /**
     * Set statut
     *
     * @param string $statut
     * @return CalEvent
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    
        return $this;
    }

    /**
     * Get statut
     *
     * @return string 
     */
    public function getStatut()
    {
        return $this->statut;
    }
    
    /**
     * Set professeur
     *
     * @param \Majordesk\AppBundle\Entity\Professeur $professeur
     * @return CalEvent
     */
    public function setProfesseur(\Majordesk\AppBundle\Entity\Professeur $professeur)
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
     * @return CalEvent
     */
    public function setEleve(\Majordesk\AppBundle\Entity\Eleve $eleve)
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

    /**
     * Set matiere
     *
     * @param \Majordesk\AppBundle\Entity\Matiere $matiere
     * @return CalEvent
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
     * Add chapitres
     *
     * @param \Majordesk\AppBundle\Entity\Chapitre $chapitres
     * @return CalEvent
     */
    public function addChapitre(\Majordesk\AppBundle\Entity\Chapitre $chapitre)
    {
        $this->chapitres[] = $chapitre;
    
        return $this;
    }

    /**
     * Remove chapitres
     *
     * @param \Majordesk\AppBundle\Entity\Chapitre $chapitres
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
     * Set ticket
     *
     * @param \Majordesk\AppBundle\Entity\Ticket $ticket
     * @return CalEvent
     */
    public function setTicket(\Majordesk\AppBundle\Entity\Ticket $ticket = null)
    {
        $this->ticket = $ticket;
    
        return $this;
    }

    /**
     * Get ticket
     *
     * @return \Majordesk\AppBundle\Entity\Ticket 
     */
    public function getTicket()
    {
        return $this->ticket;
    }
	
	/**
     * Constructor
     */
    public function __construct()
    {
        $this->chapitres = new \Doctrine\Common\Collections\ArrayCollection();
		$this->dateEnregistrement = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
		// type: 1=cours, 2=contrôle
		$this->type = 1;
		// reservation: 1=réservé(DEBITE), 0=annulé par le client, 2:confirmé par le professeur(DEBITE), 3:refusé par le professeur
		$this->reservation = 1;
		// statut: 1=pas eu lieu (à venir ou passé), 2=a eu lieu
		$this->statut = 1;
    }
}