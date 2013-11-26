<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Exercice
 *
 * @ORM\Table(name="exercice")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\ExerciceRepository")
 */
class Exercice
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
     * @var boolean
     *
     * @ORM\Column(name="favoris", type="boolean")
     */
    private $favoris;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="autonomie", type="boolean")
     */
    private $autonomie;

    /**
     * @var smallint
     *
     * @ORM\Column(name="selection", type="smallint")
     */
    private $selection;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="temps", type="time")
     */
    private $temps;
	
	/**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=1)
     */
    private $statut;
	
	/**
     * @var string
     *
     * @ORM\Column(name="queue", type="string", length=1)
     */
    private $queue;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="date_queue", type="datetime")
     */
    private $date_queue;

    /**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\ModExercice", inversedBy="exercices")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $mod_exercice;

	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Eleve", inversedBy="exercices")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $eleve;
	
	/**
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\Question", mappedBy="exercice", cascade={"persist", "remove"})
	*/
	private $questions;
	

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
     * Set favoris
     *
     * @param boolean $favoris
     * @return Exercice
     */
    public function setFavoris($favoris)
    {
        $this->favoris = $favoris;
    
        return $this;
    }

    /**
     * Get favoris
     *
     * @return boolean 
     */
    public function getFavoris()
    {
        return $this->favoris;
    }
	
	/**
     * Set autonomie
     *
     * @param boolean $autonomie
     * @return Exercice
     */
    public function setAutonomie($autonomie)
    {
        $this->autonomie = $autonomie;
    
        return $this;
    }

    /**
     * Get autonomie
     *
     * @return boolean 
     */
    public function getAutonomie()
    {
        return $this->autonomie;
    }

    /**
     * Set selection
     *
     * @param boolean $selection
     * @return Exercice
     */
    public function setSelection($selection)
    {
        $this->selection = $selection;
    
        return $this;
    }

    /**
     * Get selection
     *
     * @return boolean 
     */
    public function getSelection()
    {
        return $this->selection;
    }
	
	/**
     * Set queue
     *
     * @param boolean $queue
     * @return Exercice
     */
    public function setQueue($queue)
    {
        $this->queue = $queue;
    
        return $this;
    }

    /**
     * Get queue
     *
     * @return boolean 
     */
    public function getQueue()
    {
        return $this->queue;
    }
	
	/**
     * Set date_queue
     *
     * @param boolean $date_queue
     * @return Exercice
     */
    public function setDateQueue($date_queue)
    {
        $this->date_queue = $date_queue;
    
        return $this;
    }

    /**
     * Get date_queue
     *
     * @return boolean 
     */
    public function getDateQueue()
    {
        return $this->date_queue;
    }
	
	/**
     * Get derniere date
     *
     * @return boolean 
     */
    public function getDerniereDate()
    {
        foreach( $this->getQuestions() as $question ) {
			if (!isset($derniere_date)) {
				$derniere_date = $question->getDateEnregistrement();
			}
			else {
				$derniere_date = max( $derniere_date, $question->getDateEnregistrement() );
			}
		}
		
		return $derniere_date;
    }
	
	/**
     * Get selection
     *
     * @return boolean 
     */
    // public function getTemps()
    // {
		// $temps = new \DateTime("00:00:00");
		// $heures = 0;
		// $minutes = 0;
		// $secondes = 0;
        // foreach( $this->getQuestions() as $question ) {
			// $temps_question = $question->getTemps();		
			// $heures += intval($temps_question->format('G'));
			// $minutes += intval($temps_question->format('i'));
			// $secondes += intval($temps_question->format('s'));
		// }
		
		// $temps->setTime($heures, $minutes, $secondes);
		
		// return $temps;
    // }
	
	/**
     * Set temps
     *
     * @param \DateTime $temps
     * @return Question
     */
    public function setTemps($temps)
    {
        $this->temps = $temps;
    
        return $this;
    }

    /**
     * Get temps
     *
     * @return \DateTime 
     */
    public function getTemps()
    {
        return $this->temps;
    }
	
	/**
     * Get selection
     *
     * @return boolean 
     */
    public function getNiveau()
    {
		return $this->mod_exercice->getNiveau();
    }
	
	/**
     * Get selection
     *
     * @return boolean 
     */
    public function getNombreEssais()
    {
		$essais = 0;
        foreach( $this->getQuestions() as $question ) {
			$essais += $question->getNombreEssais();
		}
		
		return $essais;
    }
	
	/**
     * Get NombreQuestions
     *
     * @return boolean 
     */
    public function getNombreQuestions()
    {
		return count($this->questions);
    }
	
	/**
     * Get NombreQuestionsCommencees
     *
     * @return boolean 
     */
    public function getNombreQuestionsCommencees($statut_non_commence)
    {
		$nb_questions_commencees = 0;
		foreach( $this->questions as $question ) {
			if ( $question->getStatut() != $statut_non_commence ) {
				$nb_questions_commencees += 1;
			}
		}
		
		return $nb_questions_commencees;
    }
	
	/**
     * Set statut
     *
     * @param string $statut
     * @return Question
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
     * Update statut
     *
     * @return boolean 
     */
    public function updateStatut()
    {
		// Vars
		$statut_non_commence = 0;
		$statut_non_resolu = 1;
		$statut_resolu = 2;
		
		$non_commence = 0;
		$non_resolu = 0;
		$resolu = 0;
		$questions = $this->getTrueQuestions();
		$nombre_questions = count($questions);
		if ($nombre_questions == 0) {  // à supprimer qd proprification
			$questions = $this->getQuestions();
			$nombre_questions = count($questions);
		}
		
        foreach( $questions as $question ) {
			if ( $question->getStatut() == $statut_resolu ) {
				$resolu++;
			}
			else if ( $question->getStatut() == $statut_non_resolu ) {
				$non_resolu++;
			}
			else {
				$non_commence++;
			}
		}
		
		if ( $resolu == $nombre_questions) {
			$this->statut = $statut_resolu;
		}
		else if ( $non_commence == $nombre_questions) {
			$this->statut = $statut_non_commence;
		}
		else {
			$this->statut = $statut_non_resolu;
		}
    }
    
    /**
     * Set mod_exercice
     *
     * @param \Majordesk\AppBundle\Entity\ModExercice $modExercice
     * @return Exercice
     */
    public function setModExercice(\Majordesk\AppBundle\Entity\ModExercice $modExercice)
    {
        $this->mod_exercice = $modExercice;
    
        return $this;
    }

    /**
     * Get mod_exercice
     *
     * @return \Majordesk\AppBundle\Entity\ModExercice 
     */
    public function getModExercice()
    {
        return $this->mod_exercice;
    }

    /**
     * Set eleve
     *
     * @param \Majordesk\AppBundle\Entity\Eleve $eleve
     * @return Exercice
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
     * Add questions
     *
     * @param \Majordesk\AppBundle\Entity\Question $questions
     * @return Exercice
     */
    public function addQuestion(\Majordesk\AppBundle\Entity\Question $question)
    {
        $this->questions[] = $question;
    
        return $this;
    }

    /**
     * Remove questions
     *
     * @param \Majordesk\AppBundle\Entity\Question $questions
     */
    public function removeQuestion(\Majordesk\AppBundle\Entity\Question $question)
    {
        $this->questions->removeElement($question);
    }
	
	/**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function selectQuestionByNumero($numero)
    {
		foreach($this->questions as $question) {
			if ($question->getNumero() == $numero) {
				return $question;
			}
		}
		return null;
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuestions()
    {
        return $this->questions;
    }
	
	/**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTrueQuestions()
    {
		$questions = array();
        foreach($this->questions as $question) {
			if($question->getModQuestion()->getType() == 'question') {
				$questions[] = $question;
			}
		}
		return $questions;
    }
	
	/**
     * Constructor
     */
    public function __construct()
    {
		$this->favoris = '0';
		$this->autonomie = true;
		$this->selection = 0;
		$this->queue = '0';
		$this->date_queue = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
		$this->temps = new \Datetime("00:00:00");
		$this->statut = '0';
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
    }
}