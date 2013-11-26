<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rep
 *
 * @ORM\Table(name="rep")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\RepRepository")
 */
class Rep
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
     * @ORM\Column(name="date_enregistrement", type="datetime")
     */
    private $dateEnregistrement;

    /**
     * @var string
     *
     * @ORM\Column(name="valeur", type="boolean")
     */
    private $valeur;
	
	/**
     * @var text
     *
     * @ORM\Column(name="commentaire", type="text")
     */
    private $commentaire;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Question", inversedBy="reps")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $question;
	
	/**
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\MicroRep", mappedBy="rep", cascade={"persist", "remove"})
	*/
	private $micro_reps;


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
     * Set dateEnregistrement
     *
     * @param \DateTime $dateEnregistrement
     * @return Rep
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
     * Set valeur
     *
     * @param string $valeur
     * @return Rep
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;
    
        return $this;
    }

    /**
     * Get valeur
     *
     * @return string 
     */
    public function getValeur()
    {
        return $this->valeur;
    }
	
	/**
     * Set commentaire
     *
     * @param string $commentaire
     * @return Rep
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
    
        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string 
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }
	
	/**
     * Set question
     *
     * @param \Majordesk\AppBundle\Entity\Question $question
     * @return Reponse
     */
    public function setQuestion(\Majordesk\AppBundle\Entity\Question $question)
    {
        $this->question = $question;
    
        return $this;
    }

    /**
     * Get question
     *
     * @return \Majordesk\AppBundle\Entity\Question 
     */
    public function getQuestion()
    {
        return $this->question;
    }
	
	/**
     * Add micro_rep
     *
     * @param \Majordesk\AppBundle\Entity\MicroRep $micro_rep
     * @return Question
     */
    public function addMicroRep(\Majordesk\AppBundle\Entity\MicroRep $micro_rep)
    {
        $this->micro_reps[] = $micro_rep;
		$micro_rep->setRep($this);
    
        return $this;
    }

    /**
     * Remove rep
     *
     * @param \Majordesk\AppBundle\Entity\MicroRep $micro_rep
     */
    public function removeMicroRep(\Majordesk\AppBundle\Entity\MicroRep $micro_rep)
    {
        $this->micro_reps->removeElement($micro_rep);
    }
	
	/**
     * Get reponse
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function selectLastMicroRepByNumero($numero)
    {
		foreach($this->micro_reps as $micro_rep) {
			if ($micro_rep->getNumero() == $numero) {
				$last_reponse = $micro_rep;
			}
		}
		if (isset($last_reponse)) {
			return $last_reponse;
		}
		else {
			return null;
		}		
    }
	
	/**
     * Get micro_reps
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMicroReps()
    {
        return $this->micro_reps;
    }
	
	/**
     * Constructor
     */
    public function __construct()
    {
		$this->dateEnregistrement = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
    }
}
