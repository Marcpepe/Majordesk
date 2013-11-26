<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Feedback
 *
 * @ORM\Table(name="feedback")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\FeedbackRepository")
 */
class Feedback
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
     * @ORM\Column(name="commentaire", type="text")
     */
    private $commentaire;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=1)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255, nullable=true)
     */
    private $mail;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_enregistrement", type="datetime")
     */
    private $dateEnregistrement;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=1)
     */
    private $statut;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\ModExercice", inversedBy="feedback")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $mod_exercice;


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
     * Set commentaire
     *
     * @param string $commentaire
     * @return Feedback
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
     * Set type
     *
     * @param string $type
     * @return Feedback
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
     * Set mail
     *
     * @param string $mail
     * @return Feedback
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    
        return $this;
    }

    /**
     * Get mail
     *
     * @return string 
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set dateEnregistrement
     *
     * @param \DateTime $dateEnregistrement
     * @return Feedback
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
     * Set statut
     *
     * @param string $statut
     * @return Feedback
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
     * Set mod_exercice
     *
     * @param \Majordesk\AppBundle\Entity\ModExercice $modExercice
     * @return ModMacro
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
	
	public function __construct()
	{
		$this->dateEnregistrement = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
		$this->statut = 1;
	}
}
