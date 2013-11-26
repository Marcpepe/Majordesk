<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
// use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Majordesk\AppBundle\Entity\Matiere;
use Majordesk\AppBundle\Entity\EleveMatiere;

/**
 * Eleve
 *
 * @ORM\Table(name="eleve")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\EleveRepository")
 * @UniqueEntity(fields="mail", message="Cette adresse mail est déjà utilisée.")
 */
class Eleve implements AdvancedUserInterface, \Serializable
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
     * @ORM\Column(name="mail", type="string", length=255, unique=true)
	 * @Assert\NotBlank()
	 * @Assert\Email(
     *     message = "'{{ value }}' n'est pas un email valide.",
     *     checkMX = true
     * )
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
	 * @Assert\NotBlank()
	 * @Assert\Length(
	 *     min = "6",
	 *     minMessage = "Le mot de passe doit faire au moins 6 caractères."
	 *     )
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     */
    private $roles;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
	 * @Assert\NotBlank()
	 * @Assert\Length(
	 *     min = "2",
	 *     max = "50",
	 *     minMessage = "Le prénom doit faire au moins 2 caractères.",
	 *     maxMessage = "Le prénom doit faire au plus 50 caractères."
	 *     )
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
	 * @Assert\NotBlank()
	 * @Assert\Length(
	 *     min = "2",
	 *     max = "50",
	 *     minMessage = "Le prénom doit faire au moins 2 caractères.",
	 *     maxMessage = "Le prénom doit faire au plus 50 caractères."
	 *     )
     */
    private $nom;

    /**
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean")
	 * @Assert\Type(type="bool")
     */
    private $actif;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="flag", type="boolean")
	 * @Assert\Type(type="bool")
     */
    private $flag;

    /**
     * @var string
     *
     * @ORM\Column(name="lycee", type="string", length=255, nullable=true)
	 * @Assert\Length(
	 *     min = "2",
	 *     max = "100",
	 *     minMessage = "Le nom du Lycée ne peut pas contenir moins de 2 caractères.",
	 *     maxMessage = "Le nom du Lycée ne peut pas contenir plus de 100 caractères."
	 *     )
     */
    private $lycee;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=25, nullable=true)
	 * @Assert\Length(
	 *     min = "10",
	 *     max = "25",
	 *     minMessage = "Le numéro de téléphone doit contenir au moins 10 numéros.",
	 *     maxMessage = "Le numéro de téléphone doit contenir au plus 25 numéros."
	 *     )
     */
    private $telephone;

    /**
     * @var integer
     *
     * @ORM\Column(name="heures_prises", type="smallint")
	 * @Assert\Range(
	 *     min = "0",
	 *     max = "9999",
	 *     minMessage = "Le nombre d'heures prises ne peut pas être négatif.",
	 *     maxMessage = "Le nombre d'heures prises ne peut pas excéder 9 999.",
	 *     invalidMessage = "La valeur entrée (Nombre d'heures prises) doit être un nombre."
	 *     )
     */
    private $heures_prises;

    /**
     * @var integer
     *
     * @ORM\Column(name="notifications", type="smallint")
	 * @Assert\Range(
	 *     min = "0",
	 *     max = "99",
	 *     minMessage = "Le nombre de notifications ne peut pas être négatif.",
	 *     maxMessage = "Le nombre de notifications ne peut pas excéder 99.",
	 *     invalidMessage = "La valeur entrée (Nombre de notifications) doit être un nombre."
	 *     )
     */
    private $notifications;

    /**
     * @var integer
     *
     * @ORM\Column(name="rythme", type="smallint")
	 * @Assert\Range(
	 *     min = "0",
	 *     max = "5",
	 *     minMessage = "Le rythme d'exercices ne peut pas être négatif.",
	 *     maxMessage = "Le rythme d'exercices ne peut pas excéder 5.",
	 *     invalidMessage = "La valeur entrée (Rythme d'exercices) doit être un nombre."
	 *     )
     */
    private $rythme;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_inscription", type="date")
	 * @Assert\NotBlank()
	 * @Assert\Date(message="La date entrée n'est pas valide.")
     */
    private $date_inscription;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Famille", inversedBy="eleves")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $famille;
	
	/**
	* @ORM\ManyToMany(targetEntity="Majordesk\AppBundle\Entity\Professeur", inversedBy="eleves")
	*/
	private $professeurs;
	
	/**
	 * @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\Disponibilite", mappedBy="eleve", cascade={"persist", "remove"})
	 * @Assert\Valid()
	 */
	private $disponibilites;
	
	/**
	 * @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\CalEvent", mappedBy="eleve", cascade={"remove"})
	 * @Assert\Valid()
	 */
	private $cal_events;
	
	/**
	 * @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\Exercice", mappedBy="eleve", cascade={"remove"})
	 */
	private $exercices;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Programme", inversedBy="eleves")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $programme;
	
	/**
	 * @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\EleveMatiere", mappedBy="eleve", cascade={"persist", "remove"})
	 */
	private $eleve_matieres;


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
     * Set mail
     *
     * @param string $mail
     * @return Eleve
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
     * Set password
     *
     * @param string $password
     * @return Eleve
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Eleve
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set roles
     *
     * @param array $roles
     * @return Eleve
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    
        return $this;
    }

    /**
     * Get roles
     *
     * @return array 
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Eleve
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Eleve
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
     * Set actif
     *
     * @param boolean $actif
     * @return Eleve
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
     * Set flag
     *
     * @param boolean $flag
     * @return Eleve
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;
    
        return $this;
    }

    /**
     * Get flag
     *
     * @return boolean 
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * Set programme
     *
     * @param \Majordesk\AppBundle\Entity\Programme $programme
     * @return Eleve
     */
    public function setProgramme(\Majordesk\AppBundle\Entity\Programme $programme)
    {
        $this->programme = $programme;
    
        return $this;
    }

    /**
     * Get programme
     *
     * @return string 
     */
    public function getProgramme()
    {
        return $this->programme;
    }

    /**
     * Set lycee
     *
     * @param string $lycee
     * @return Eleve
     */
    public function setLycee($lycee)
    {
        $this->lycee = $lycee;
    
        return $this;
    }

    /**
     * Get lycee
     *
     * @return string 
     */
    public function getLycee()
    {
        return $this->lycee;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return Eleve
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    
        return $this;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
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
     * Set notifications
     *
     * @param integer $notifications
     * @return Eleve
     */
    public function setNotifications($notifications)
    {
        $this->notifications = $notifications;
    
        return $this;
    }

    /**
     * Get notifications
     *
     * @return integer 
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Set rythme
     *
     * @param integer $rythme
     * @return Eleve
     */
    public function setRythme($rythme)
    {
        $this->rythme = $rythme;
    
        return $this;
    }

    /**
     * Get rythme
     *
     * @return integer 
     */
    public function getRythme()
    {
        return $this->rythme;
    }

    /**
     * Set date_inscription
     *
     * @param \DateTime $dateInscription
     * @return Eleve
     */
    public function setDateInscription($dateInscription)
    {
        $this->date_inscription = $dateInscription;
    
        return $this;
    }

    /**
     * Get date_inscription
     *
     * @return \DateTime 
     */
    public function getDateInscription()
    {
        return $this->date_inscription;
    }

    /**
     * Set famille
     *
     * @param \Majordesk\AppBundle\Entity\Famille $famille
     * @return Eleve
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
     * Get nomEntier
     *
     * @return un string contenant le nom et le prénom
     */
	public function getNomEntier()
	{
		return sprintf('%s %s', $this->nom, $this->username);
	}
	
	/**
     * Get initiale
     *
     * @return un string contenant la première initiale
     */
	public function getInitiale()
	{
		return sprintf('%s ______________________', substr($this->nom, 0, 1));
	}
	
	/**
     * Is Assigned ( c-a-d a au moins un professeur attribué )
     *
     * @return un bool qui est true si l'élève a au moins un professeur
     */
	public function isAssigned()
	{
		return !($this->professeurs->isEmpty());
	}
	
	/**
     * Get NumberOfProfesseursAssigned
     *
     * @return integer le nombre de professeur(s)
     */
	public function getNumberOfProfesseursAssigned()
	{
		if (count($this->professeurs) == 0)
		{
			return 'Aucun Professeur';
		}
		else if (count($this->professeurs) == 1)
		{
			return  '1 Professeur';
		}
		else
		{
			return count($this->professeurs).' professeurs';
		}
		// return $this->professeurs === array();
	}
	
	/**
     * Add professeur
     *
     * @param \Majordesk\AppBundle\Entity\Professeur $professeur
     * @return Eleve
     */
    public function addProfesseur(\Majordesk\AppBundle\Entity\Professeur $professeur)
    {
		$this->professeurs[] = $professeur;
			
        return $this;
    }

    /**
     * Remove professeur
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
     * Add eleve_matiere
     *
     * @param \Majordesk\AppBundle\Entity\EleveMatiere $eleve_matiere
     * @return Eleve
     */
    public function addEleveMatiere(\Majordesk\AppBundle\Entity\EleveMatiere $eleve_matiere)
    {
		$this->eleve_matieres[] = $eleve_matiere;
		$eleve_matiere->setEleve($this);
			
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
     * Get matieres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMatieres()
    {
		$matieres = array();
		foreach($this->eleve_matieres as $eleve_matiere) {
			$matieres[] = $eleve_matiere->getMatiere();
		}
        return $matieres;
    }
	
	/**
     * Get matieres actives
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMatieresActives()
    {
		$matieres = array();
		foreach($this->eleve_matieres as $eleve_matiere) {
			if ($eleve_matiere->isActif()) {
				$matieres[] = $eleve_matiere->getMatiere();
			}
		}
        return $matieres;
    }
	
	/**
     * Has Plateforme
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function hasPlateforme()
    {
		$hasPlateforme = 0;
		foreach($this->eleve_matieres as $eleve_matiere) {
			if ($eleve_matiere->isActif()) {
				$hasPlateforme = 1;
			}
		}
        return $hasPlateforme;
    }
	
	/**
     * Has Cours
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function hasCours()
    {
		$hasCours = 0;
		foreach($this->eleve_matieres as $eleve_matiere) {
			if ($eleve_matiere->getCours() == 1) {
				$hasCours = 1;
			}
		}
        return $hasCours;
    }
	
	/**
     * Has Plateforme
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function hasAutorisationPrelevement()
    {
		$hasAutorisationPrelevement = 0;
		foreach($this->eleve_matieres as $eleve_matiere) {
			if ($eleve_matiere->getPrelevementPlateforme() == 1) {
				$hasAutorisationPrelevement = 1;
			}
		}
        return $hasAutorisationPrelevement;
    }
	
	/**
     * Add disponibilite
     *
     * @param \Majordesk\AppBundle\Entity\Disponibilite $disponibilite
     * @return Eleve
     */
    public function addDisponibilite(\Majordesk\AppBundle\Entity\Disponibilite $disponibilite)
    {
		$this->disponibilites[] = $disponibilite;
		$disponibilite->setEleve($this);
			
        return $this;
    }

    /**
     * Remove disponibilite
     *
     * @param \Majordesk\AppBundle\Entity\Disponibilite $disponibilite
     */
    public function removeDisponibilite(\Majordesk\AppBundle\Entity\Disponibilite $disponibilite)
    {
        $this->disponibilites->removeElement($disponibilite);
    }

    /**
     * Get disponibilites
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDisponibilites()
    {
        return $this->disponibilites;
    }
	
	/**
     * Add cal_event
     *
     * @param \Majordesk\AppBundle\Entity\CalEvent $cal_event
     * @return Eleve
     */
    public function addCalEvent(\Majordesk\AppBundle\Entity\CalEvent $cal_event)
    {
		$this->cal_events[] = $cal_event;
		$cal_event->setEleve($this);
			
        return $this;
    }

    /**
     * Remove cal_event
     *
     * @param \Majordesk\AppBundle\Entity\CalEvent $cal_event
     */
    public function removeCalEvent(\Majordesk\AppBundle\Entity\CalEvent $cal_event)
    {
        $this->cal_events->removeElement($cal_event);
		// $cal_event->setEleve(null);
    }

    /**
     * Get cal_events
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCalEvents()
    {
        return $this->cal_events;
    }
	
	public function eraseCredentials()
	{
	}
	
	public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->actif;
    }

	
	/**
     * Serializes the user.
     *
     * The serialized data have to contain the fields used by the equals method and the username.
     *
     * @return string
     */
    public function serialize()
    {
        return serialize(array(
            $this->password,
            $this->salt,
            $this->id,
        ));
    }

    /**
     * Unserializes the user.
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        // add a few extra elements in the array to ensure that we have enough keys when unserializing
        // older data which does not include all properties.
        $data = array_merge($data, array_fill(0, 2, null));

        list(
            $this->password,
            $this->salt,
            $this->id
        ) = $data;
    }
	
	public function __construct()
	{
		$this->heures_prises = 0;
		$this->notifications = 0;
		$this->rythme = 1;
		$this->date_inscription = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
		$this->actif = true;
		$this->flag = false;
		$this->roles = array("ROLE_ELEVE");
	}
}