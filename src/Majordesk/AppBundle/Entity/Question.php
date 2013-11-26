<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\QuestionRepository")
 */
class Question
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
     * @var integer
     *
     * @ORM\Column(name="numero", type="smallint")
     */
    private $numero;

    /**
     * @var integer
     *
     * @ORM\Column(name="nombre_essais", type="smallint")
     */
    private $nombre_essais;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="date_enregistrement", type="datetime")
     */
    private $date_enregistrement;
	
	/**
     * @var text
     *
     * @ORM\Column(name="commentaire", type="text")
     */
    private $commentaire;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="couche", type="smallint")
     */
    private $couche;
	
    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=1)
     */
    private $statut;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Exercice", inversedBy="questions")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $exercice;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\ModQuestion")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $mod_question;
	
	/**
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\Reponse", mappedBy="question", cascade={"persist", "remove"})
	*/
	private $reponses;
	
	/**
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\Rep", mappedBy="question", cascade={"persist", "remove"})
	*/
	private $reps;
	

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
     * Set numero
     *
     * @param integer $numero
     * @return Question
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    
        return $this;
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
     * Is Last ?
     *
     * @return boolean 
     */
    public function isLastQuestion()
    {
        return count($this->exercice->getQuestions()) == $this->numero;
    }

    /**
     * Set nombre_essais
     *
     * @param integer $nombreEssais
     * @return Question
     */
    public function setNombreEssais($nombreEssais)
    {
        $this->nombre_essais = $nombreEssais;
    
        return $this;
    }

    /**
     * Get nombre_essais
     *
     * @return integer 
     */
    public function getNombreEssais()
    {
        return $this->nombre_essais;
    }

	/**
     * Set date_enregistrement
     *
     * @param \DateTime $date_enregistrement
     * @return Reponse
     */
    public function setDateEnregistrement($date_enregistrement)
    {
        $this->date_enregistrement = $date_enregistrement;
    
        return $this;
    }

    /**
     * Get dateEnregistrement
     *
     * @return \DateTime 
     */
    public function getDateEnregistrement()
    {
        return $this->date_enregistrement;
    }
	
	/**
     * update dateEnregistrement To Current Date
     */
    public function updateDateEnregistrementToCurrentDate()
    {
        $this->date_enregistrement = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
    }
	
	/**
     * Set commentaire
     *
     * @param text $commentaire
     * @return Question
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
    
        return $this;
    }

    /**
     * Get commentaire
     *
     * @return text
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }
	
	/**
     * Get commentaire
     *
     * @return text
     */
    public function getLatestCommentaire()
    {
		$date = null;
		$commentaire = '';
        foreach($this->reps as $rep) {
			if ($date == null) {
				$date = $rep->getDateEnregistrement();
				$commentaire = $rep->getCommentaire();
			} else {
				if ($rep->getDateEnregistrement() > $date) {
					$commentaire = $rep->getCommentaire();
				}
			}
		}
		return $commentaire;
    }
	
	/**
     * Set couche
     *
     * @param string $couche
     * @return ModElement
     */
    public function setCouche($couche)
    {
        $this->couche = $couche;
    
        return $this;
    }

    /**
     * Get couche
     *
     * @return string 
     */
    public function getCouche()
    {
        return $this->couche;
    }

    /**
     * Set statut
     *
     * @param string $statut
     * @return Question
     */
    public function setStatut($statut)
    {
		// Vars
		$statut_non_commence = 0;
		$statut_non_resolu = 1;
		$statut_resolu = 2;
	
		if ($statut === true) {
			$this->statut = $statut_resolu;
		}
		else {
			if ($this->nombre_essais > 0) {
				$this->statut = $statut_non_resolu;
			}
			else {
				$this->statut = $statut_non_commence;
			}
		}
		$this->exercice->updateStatut();
    
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
     * Set exercice
     *
     * @param \Majordesk\AppBundle\Entity\Exercice $exercice
     * @return Question
     */
    public function setExercice(\Majordesk\AppBundle\Entity\Exercice $exercice)
    {
        $this->exercice = $exercice;
    
        return $this;
    }

    /**
     * Get exercice
     *
     * @return \Majordesk\AppBundle\Entity\Exercice 
     */
    public function getExercice()
    {
        return $this->exercice;
    }
	
	/**
     * Set mod_question
     *
     * @param \Majordesk\AppBundle\Entity\ModQuestion $mod_question
     * @return Question
     */
    public function setModQuestion($mod_question)
    {
        $this->mod_question = $mod_question;
    
        return $this;
    }

    /**
     * Get mod_question
     *
     * @return string 
     */
    public function getModQuestion()
    {
        return $this->mod_question;
    }
    
    /**
     * Add reponse
     *
     * @param \Majordesk\AppBundle\Entity\Reponse $reponse
     * @return Question
     */
    public function addReponse(\Majordesk\AppBundle\Entity\Reponse $reponse)
    {
        $this->reponses[] = $reponse;
		$reponse->setQuestion($this);
    
        return $this;
    }

    /**
     * Remove reponse
     *
     * @param \Majordesk\AppBundle\Entity\Reponse $reponse
     */
    public function removeReponse(\Majordesk\AppBundle\Entity\Reponse $reponse)
    {
        $this->reponses->removeElement($reponse);
    }
	
	/**
     * Get reponse
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function selectLastReponseByNumero($numero)
    {
		foreach($this->reponses as $reponse) {
			if ($reponse->getNumero() == $numero) {
				$last_reponse = $reponse;
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
     * Get reponse
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function selectLastMicroRep($numero)
    {
		$date = null;
		foreach($this->reps as $rep) {
			if ($date != null) {
				if ($rep->getDateEnregistrement() > $date) {			
					$last_reponse = $rep->selectLastMicroRepByNumero($numero);
				}
			} else {
				$date = $rep->getDateEnregistrement();
				$last_reponse = $rep->selectLastMicroRepByNumero($numero);
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
     * Get reps
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReponses()
    {
        return $this->reponses;
    }
	
	/**
     * Add rep
     *
     * @param \Majordesk\AppBundle\Entity\Rep $rep
     * @return Question
     */
    public function addRep(\Majordesk\AppBundle\Entity\Rep $rep)
    {
        $this->reps[] = $rep;
		$rep->setQuestion($this);
    
        return $this;
    }

    /**
     * Remove rep
     *
     * @param \Majordesk\AppBundle\Entity\Rep $rep
     */
    public function removeRep(\Majordesk\AppBundle\Entity\Rep $rep)
    {
        $this->reps->removeElement($rep);
    }
	
	/**
     * Get reps
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReps()
    {
        return $this->reps;
    }
	
	/**
     * Constructor
     */
    public function __construct()
    {
		$this->nombre_essais = 0;
		$this->statut = 0;
		$this->couche = 1;
		$this->commentaire = '';
		$this->date_enregistrement = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
        $this->reponses = new \Doctrine\Common\Collections\ArrayCollection();
    }
}