<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
// use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Professeur
 *
 * @ORM\Table(name="professeur")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\ProfesseurRepository")
 * @UniqueEntity(fields="mail", message="Cette adresse mail est déjà utilisée.")
 */
class Professeur implements AdvancedUserInterface, \Serializable
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
     * @var integer
     *
     * @ORM\Column(name="heures_donnees", type="smallint")
	 * @Assert\Range(
	 *     min = "0",
	 *     max = "9999",
	 *     minMessage = "Le nombre d'heures achetées ne peut pas être négatif.",
	 *     maxMessage = "Le nombre d'heures achetées ne peut pas excéder 9 999.",
	 *     invalidMessage = "La valeur entrée (Nombre d'heures données) doit être un nombre."
	 *     )
     */
    private $heures_donnees;

    /**
     * @var string
     *
     * @ORM\Column(name="prepa", type="string", length=255)
     */
    private $prepa;

    /**
     * @var string
     *
     * @ORM\Column(name="lycee", type="string", length=255, nullable=true)
     */
    private $lycee;
	
	/**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="code_postal", type="string", length=15, nullable=true)
     */
    private $code_postal;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255, nullable=true)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=25, nullable=true)
     */
    private $telephone;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fin_dispo", type="date")
	 * @Assert\Date(message="La date entrée n'est pas valide.")
     */
    private $fin_dispo;

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
     * @var string
     *
     * @ORM\Column(name="securite_sociale", type="string", length=255, nullable=true)
	 * @Assert\Length(
	 *     min = "15",
	 *     max = "15",
	 *     exactMessage = "Le numéro d'immatriculation Urssaf doit contenir 15 caractères exactement."
	 *     )
     */
    private $securite_sociale;
	
	/**
     * @var string
     *
     * @ORM\Column(name="rib", type="string", length=255, nullable=true)
     */
    private $rib;
	
	/**
     * @var string
     *
     * @ORM\Column(name="transport", type="string", length=255)
	 * @Assert\NotBlank()
	 * @Assert\Choice(
	 *     choices = {"Transports en commun", "Voiture", "2 roues", "Vélo", "Autre"},
	 *     message = "Le moyen de transport choisi est incorrect."
	 *     )
     */
    private $transport;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="nb_heures_max", type="smallint")
	 * @Assert\Range(
	 *     min = "0",
	 *     max = "40",
	 *     minMessage = "Le nombre d'heures maximum désiré ne peut pas être négatif.",
	 *     maxMessage = "Le nombre d'heures maximum désiré ne peut pas excéder 40.",
	 *     invalidMessage = "La valeur entrée (Nombre de notifications) doit être un nombre."
	 *     )
     */
    private $nb_heures_max;
	
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_inscription", type="date")
	 * @Assert\NotBlank()
	 * @Assert\Date(message="La date entrée n'est pas valide.")
     */
    private $date_inscription;
	
	/**
	* @ORM\ManyToMany(targetEntity="Majordesk\AppBundle\Entity\Eleve", mappedBy="professeurs", cascade={"persist"})
	*/
	private $eleves;
	
	/**
	 * @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\Disponibilite", mappedBy="professeur", cascade={"persist"})
	 * @Assert\Valid()
	 */
	private $disponibilites;
	
	/**
	 * @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\CalEvent", mappedBy="professeur", cascade={"remove"})
	 * @Assert\Valid()
	 */
	private $cal_events;
	
	/**
	* @ORM\ManyToMany(targetEntity="Majordesk\AppBundle\Entity\Matiere", inversedBy="professeurs", cascade={"persist"})
	*/
	private $matieres;
	

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
     * @return Professeur
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
     * @return Professeur
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
     * @return Professeur
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
     * @return Professeur
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
     * @return Professeur
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
     * @return Professeur
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
     * @return Professeur
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
     * Set heures_donnees
     *
     * @param integer $heuresDonnees
     * @return Professeur
     */
    public function setHeuresDonnees($heuresDonnees)
    {
        $this->heures_donnees = $heuresDonnees;
    
        return $this;
    }

    /**
     * Get heures_donnees
     *
     * @return integer 
     */
    public function getHeuresDonnees()
    {
        return $this->heures_donnees;
    }

    /**
     * Set prepa
     *
     * @param string $prepa
     * @return Professeur
     */
    public function setPrepa($prepa)
    {
        $this->prepa = $prepa;
    
        return $this;
    }

    /**
     * Get prepa
     *
     * @return string 
     */
    public function getPrepa()
    {
        return $this->prepa;
    }

    /**
     * Set lycee
     *
     * @param string $lycee
     * @return Professeur
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
     * Set adresse
     *
     * @param string $adresse
     * @return Client
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    
        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set code_postal
     *
     * @param string $codePostal
     * @return Client
     */
    public function setCodePostal($codePostal)
    {
        $this->code_postal = $codePostal;
    
        return $this;
    }

    /**
     * Get code_postal
     *
     * @return string 
     */
    public function getCodePostal()
    {
        return $this->code_postal;
    }

    /**
     * Set ville
     *
     * @param string $ville
     * @return Client
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
    
        return $this;
    }

    /**
     * Get ville
     *
     * @return string 
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return Client
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
     * Set fin_dispo
     *
     * @param \DateTime $finDispo
     * @return Professeur
     */
    public function setFinDispo($finDispo)
    {
        $this->fin_dispo = $finDispo;
    
        return $this;
    }

    /**
     * Get fin_dispo
     *
     * @return \DateTime 
     */
    public function getFinDispo()
    {
        return $this->fin_dispo;
    }

    /**
     * Set notifications
     *
     * @param integer $notifications
     * @return Professeur
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
     * Set securite_sociale
     *
     * @param string $securiteSociale
     * @return Famille
     */
    public function setSecuriteSociale($securiteSociale)
    {
        $this->securite_sociale = $securiteSociale;
    
        return $this;
    }

    /**
     * Get securite_sociale
     *
     * @return string 
     */
    public function getSecuriteSociale()
    {
        return $this->securite_sociale;
    }
	
	/**
     * Set rib
     *
     * @param string $rib
     * @return Famille
     */
    public function setRib($rib)
    {
        $this->rib = $rib;
    
        return $this;
    }

    /**
     * Get rib
     *
     * @return string 
     */
    public function getRib()
    {
        return $this->rib;
    }
	
	/**
     * Set transport
     *
     * @param string $transport
     * @return Professeur
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;
    
        return $this;
    }

    /**
     * Get transport
     *
     * @return string 
     */
    public function getTransport()
    {
        return $this->transport;
    }
	
	/**
     * Set nb_heures_max
     *
     * @param string $nb_heures_max
     * @return Professeur
     */
    public function setNbHeuresMax($nb_heures_max)
    {
        $this->nb_heures_max = $nb_heures_max;
    
        return $this;
    }

    /**
     * Get nb_heures_max
     *
     * @return string 
     */
    public function getNbHeuresMax()
    {
        return $this->nb_heures_max;
    }

    /**
     * Set date_inscription
     *
     * @param \DateTime $dateInscription
     * @return Professeur
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
     * Add eleve
     *
     * @param \Majordesk\AppBundle\Entity\Eleve $eleve
     * @return Professeur
     */
    public function addElef(\Majordesk\AppBundle\Entity\Eleve $eleve)
    {
		$this->eleves[] = $eleve;
		$eleve->addProfesseur($this);
		
        return $this;
    }

    /**
     * Remove eleve
     *
     * @param \Majordesk\AppBundle\Entity\Eleve $eleve
     */
    public function removeElef(\Majordesk\AppBundle\Entity\Eleve $eleve)
    {
        $this->eleves->removeElement($eleve);
		$eleve->removeProfesseur($this);
    }

    /**
     * Get eleves
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEleves()
    {
        return $this->eleves;
    }
	
	/**
     * Add matiere
     *
     * @param \Majordesk\AppBundle\Entity\Matiere $matiere
     * @return Eleve
     */
    public function addMatiere(\Majordesk\AppBundle\Entity\Matiere $matiere)
    {
		$this->matieres[] = $matiere;
		$matiere->addProfesseur($this);
			
        return $this;
    }

    /**
     * Remove matiere
     *
     * @param \Majordesk\AppBundle\Entity\Matiere $matiere
     */
    public function removeMatiere(\Majordesk\AppBundle\Entity\Matiere $matiere)
    {
        $this->matieres->removeElement($matiere);
    }

    /**
     * Get matieres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMatieres()
    {
        return $this->matieres;
    }
	
	/**
     * Add disponibilite
     *
     * @param \Majordesk\AppBundle\Entity\Disponibilite $disponibilite
     * @return Professeur
     */
    public function addDisponibilite(\Majordesk\AppBundle\Entity\Disponibilite $disponibilite)
    {
		$this->disponibilites[] = $disponibilite;
		$disponibilite->setProfesseur($this);
			
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
		// $disponibilite->setProfesseur(null);
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
     * @return Professeur
     */
    public function addCalEvent(\Majordesk\AppBundle\Entity\CalEvent $cal_event)
    {
		$this->cal_events[] = $cal_event;
		$cal_event->setProfesseur($this);
			
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
		// $cal_event->setProfesseur(null);
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
		$this->heures_donnees = 0;
		$this->notifications = 0;
		$this->date_inscription = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
		$this->actif = true;
		$this->flag = false;
		$this->roles = array("ROLE_PROF");
	}
}