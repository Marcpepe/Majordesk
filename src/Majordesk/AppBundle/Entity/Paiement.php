<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Paiement
 *
 * @ORM\Table(name="paiement")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\PaiementRepository")
 */
class Paiement
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
     * @ORM\Column(name="date_paiement", type="datetime")
     */
    private $datePaiement;

    /**
     * @var integer
     *
     * @ORM\Column(name="pack", type="smallint")
     */
    private $pack;

    /**
     * @var integer
     *
     * @ORM\Column(name="montant", type="integer")
     */
    private $montant;

    /**
     * @var string
     *
     * @ORM\Column(name="transaction", type="string", length=255)
     */
    private $transaction;
	
	/**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Famille", inversedBy="paiements")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $famille;
	
	/**
	* @ORM\OneToOne(targetEntity="Majordesk\AppBundle\Entity\Ticket", cascade={"remove"})
	*/
	private $ticket;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\EleveMatiere", inversedBy="paiements", cascade={"persist"})
	*/
	private $eleve_matiere;


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
     * Set datePaiement
     *
     * @param \DateTime $datePaiement
     * @return Paiement
     */
    public function setDatePaiement($datePaiement)
    {
        $this->datePaiement = $datePaiement;
    
        return $this;
    }

    /**
     * Get datePaiement
     *
     * @return \DateTime 
     */
    public function getDatePaiement()
    {
        return $this->datePaiement;
    }

    /**
     * Set pack
     *
     * @param integer $pack
     * @return Paiement
     */
    public function setPack($pack)
    {
        $this->pack = $pack;
    
        return $this;
    }

    /**
     * Get pack
     *
     * @return integer 
     */
    public function getPack()
    {
        return $this->pack;
    }

    /**
     * Set montant
     *
     * @param integer $montant
     * @return Paiement
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;
    
        return $this;
    }

    /**
     * Get montant
     *
     * @return integer 
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set transaction
     *
     * @param string $transaction
     * @return Paiement
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
    
        return $this;
    }

    /**
     * Get transaction
     *
     * @return string 
     */
    public function getTransaction()
    {
        return $this->transaction;
    }
	
	/**
     * Set description
     *
     * @param string $description
     * @return Paiement
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
	
	/**
     * Set famille
     *
     * @param \Majordesk\AppBundle\Entity\Famille $famille
     * @return Paiement
     */
    public function setFamille(\Majordesk\AppBundle\Entity\Famille $famille)
    {
        $this->famille = $famille;
    
        return $this;
    }

    /**
     * Get famille
     *
     * @return \Majordesk\AppBundle\Entity\Famille 
     */
    public function getFamille()
    {
        return $this->famille;
    }
	
	/**
     * Set eleve_matiere
     *
     * @param \Majordesk\AppBundle\Entity\EleveMatiere $eleve_matiere
     * @return Paiement
     */
    public function setEleveMatiere(\Majordesk\AppBundle\Entity\EleveMatiere $eleve_matiere)
    {
        $this->eleve_matiere = $eleve_matiere;
		$eleve_matiere->addPaiement($this);
    
        return $this;
    }

    /**
     * Get eleve_matiere
     *
     * @return \Majordesk\AppBundle\Entity\EleveMatiere 
     */
    public function getEleveMatiere()
    {
        return $this->eleve_matiere;
    }
	
	/**
     * Set ticket
     *
     * @param \Majordesk\AppBundle\Entity\Ticket $ticket
     * @return Eleve
     */
    public function setTicket(\Majordesk\AppBundle\Entity\Ticket $ticket)
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
	
	public function __construct()
	{
		$this->datePaiement = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
		$this->description = null;
	}
}
