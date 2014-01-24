<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
// use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\ClientRepository")
 * @UniqueEntity(fields="mail", message="Cette adresse mail est déjà utilisée.")
 */
class Client implements AdvancedUserInterface, \Serializable
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
	 *     minMessage = "Le nom doit faire au moins 2 caractères.",
	 *     maxMessage = "Le nom doit faire au plus 50 caractères."
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
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=1)
     */
    private $gender;

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
	 * @Assert\Length(
	 *     min = "10",
	 *     max = "25",
	 *     minMessage = "Le numéro de téléphone doit contenir au moins 10 numéros.",
	 *     maxMessage = "Le numéro de téléphone doit contenir au plus 25 numéros."
	 *     )
     */
    private $telephone;

    /**
     * @var boolean
     *
     * @ORM\Column(name="newsletter", type="boolean")
     */
    private $newsletter;

    /**
     * @var string
     *
     * @ORM\Column(name="alertes", type="string", length=1)
     */
    private $alertes;

    /**
     * @var \DateTime
     * @ORM\Column(name="heure_alertes", type="string", length=15, nullable=true)
     */
    private $heure_alertes;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_inscription", type="date")
	 * @Assert\Date(message="La date entrée n'est pas valide.")
     */
    private $date_inscription;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Famille", inversedBy="clients", cascade={"persist"})
	* @ORM\JoinColumn(nullable=false)
	*/
	private $famille;


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
     * @return Client
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
     * @return Client
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
     * @return Client
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
     * @return Client
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
     * @return Client
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
     * @return Client
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
     * Set gender
     *
     * @param string $gender
     * @return Client
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    
        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
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
     * Set newsletter
     *
     * @param boolean $newsletter
     * @return Client
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;
    
        return $this;
    }

    /**
     * Get newsletter
     *
     * @return boolean 
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * Set alertes
     *
     * @param string $alertes
     * @return Client
     */
    public function setAlertes($alertes)
    {
        $this->alertes = $alertes;
    
        return $this;
    }

    /**
     * Get alertes
     *
     * @return string 
     */
    public function getAlertes()
    {
        return $this->alertes;
    }

    /**
     * Set heure_alertes
     *
     * @param \DateTime $heureAlertes
     * @return Client
     */
    public function setHeureAlertes($heureAlertes)
    {
        $this->heure_alertes = $heureAlertes;
    
        return $this;
    }

    /**
     * Get heure_alertes
     *
     * @return \DateTime 
     */
    public function getHeureAlertes()
    {
        return $this->heure_alertes;
    }

    /**
     * Set date_inscription
     *
     * @param \DateTime $dateInscription
     * @return Client
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
     * @return Client
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
		$this->date_inscription = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
		$this->actif = true;
		$this->flag = false;
		$this->newsletter = '1';
		$this->alertes = '1';
		$this->roles = array("ROLE_PARENTS");
	}
}
