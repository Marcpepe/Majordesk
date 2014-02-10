<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\TicketRepository")
 */
class Ticket
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_cours", type="datetime")
     */
    private $date_cours;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_ticket", type="datetime")
     */
    private $date_ticket;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=1)
     */
    private $statut;
	
	/**
     * @var string
     *
     * @ORM\Column(name="regle", type="boolean")
     */
    private $regle;
	
	 /**
     * @var smallint
     *
     * @ORM\Column(name="quantite", type="smallint")
     */
    private $quantite;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Eleve", inversedBy="tickets")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $eleve;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Professeur", inversedBy="tickets")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $professeur;
	
	/**
	* @ORM\OneToOne(targetEntity="Majordesk\AppBundle\Entity\Paiement", mappedBy="ticket", cascade={"remove"})
	*/
	private $paiement;
	
	/**
	* @ORM\OneToOne(targetEntity="Majordesk\AppBundle\Entity\CalEvent", mappedBy="ticket")
	*/
	private $cal_event;
	


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
     * Set date_cours
     *
     * @param \DateTime $dateCours
     * @return Ticket
     */
    public function setDateCours($dateCours)
    {
        $this->date_cours = $dateCours;
    
        return $this;
    }

    /**
     * Get date_cours
     *
     * @return \DateTime 
     */
    public function getDateCours()
    {
        return $this->date_cours;
    }

    /**
     * Set date_ticket
     *
     * @param \DateTime $dateTicket
     * @return Ticket
     */
    public function setDateTicket($dateTicket)
    {
        $this->date_ticket = $dateTicket;
    
        return $this;
    }

    /**
     * Get date_ticket
     *
     * @return \DateTime 
     */
    public function getDateTicket()
    {
        return $this->date_ticket;
    }

    /**
     * Set statut
     *
     * @param string $statut
     * @return Ticket
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
     * Set regle
     *
     * @param string $regle
     * @return Ticket
     */
    public function setRegle($regle)
    {
        $this->regle = $regle;
    
        return $this;
    }

    /**
     * Get regle
     *
     * @return string 
     */
    public function getRegle()
    {
        return $this->regle;
    }
	
	/**
     * Set quantite
     *
     * @param string $quantite
     * @return Ticket
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;
    
        return $this;
    }

    /**
     * Get quantite
     *
     * @return string 
     */
    public function getQuantite()
    {
        return $this->quantite;
    }
	
	/**
     * Get montant
     *
     * @return string 
     */
    public function getMontant()
    {
        return $this->quantite * 5.99;
    }
	
	/**
     * Get montant
     *
     * @return string 
     */
    public function getMontantProfesseur()
    {
        return $this->quantite * 2.5;
    }

    /**
     * Set eleve
     *
     * @param \Majordesk\AppBundle\Entity\Eleve $eleve
     * @return Ticket
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
     * Set cal_event
     *
     * @param \Majordesk\AppBundle\Entity\CalEvent $cal_event
     * @return Ticket
     */
    public function setCalEvent(\Majordesk\AppBundle\Entity\CalEvent $cal_event)
    {
        $this->cal_event = $cal_event;
		$cal_event->setTicket($this);
    
        return $this;
    }

    /**
     * Get cal_event
     *
     * @return \Majordesk\AppBundle\Entity\CalEvent 
     */
    public function getCalEvent()
    {
        return $this->cal_event;
    }
	
	/**
     * Set paiement
     *
     * @param \Majordesk\AppBundle\Entity\Paiement $paiement
     * @return Eleve
     */
    public function setPaiement(\Majordesk\AppBundle\Entity\Paiement $paiement)
    {
        $this->paiement = $paiement;
    
        return $this;
    }

    /**
     * Get paiement
     *
     * @return \Majordesk\AppBundle\Entity\paiement 
     */
    public function getPaiement()
    {
        return $this->paiement;
    }

    /**
     * Set professeur
     *
     * @param \Majordesk\AppBundle\Entity\Professeur $professeur
     * @return Ticket
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
	
	
	public function __construct()
	{
		$this->date_cours = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
		$this->date_ticket = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
		$this->regle = false;
		$this->statut = '1'; // NON UTILISE. 1: en attente de confirmation du professeur, 2: validé par le professeur, 0: annuler par la famille
	}
}