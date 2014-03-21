<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Famille
 *
 * @ORM\Table(name="famille")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\FamilleRepository")
 */
class Famille
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
     * @ORM\Column(name="heures_achetees", type="smallint")
	 * @Assert\Range(
	 *     min = "0",
	 *     max = "4999",
	 *     minMessage = "Le nombre d'heures achetées ne peut pas être négatif.",
	 *     maxMessage = "Le nombre d'heures achetées ne peut pas excéder 4999.",
	 *     invalidMessage = "La valeur entrée (Nombre d'heures achetées) doit être un nombre."
	 *     )
     */
    private $heures_achetees;

    /**
     * @var integer
     *
     * @ORM\Column(name="heures_restantes", type="smallint")
	 * @Assert\Range(
	 *     min = "0",
	 *     max = "4999",
	 *     minMessage = "Le nombre d'heures restantes ne peut pas être négatif.",
	 *     maxMessage = "Le nombre d'heures restantes ne peut pas excéder 4999.",
	 *     invalidMessage = "La valeur entrée (Nombre d'heures restantes) doit être un nombre."
	 *     )
     */
    private $heures_restantes;

    /**
     * @var integer
     *
     * @ORM\Column(name="alerte_heures", type="string", length=1)
     */
    private $alerte_heures;

    /**
     * @var string
     *
     * @ORM\Column(name="immatriculation_urssaf", type="string", length=255, nullable=true)
	 * @Assert\Length(
	 *     min = "14",
	 *     max = "14",
	 *     exactMessage = "Le numéro d'immatriculation Urssaf doit contenir 14 caractères exactement."
	 *     )
     */
    private $immatriculation_urssaf;

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
     * @var integer
     *
     * @ORM\Column(name="heures_prises", type="smallint")
	 * @Assert\Range(
	 *     min = "0",
	 *     max = "99999",
	 *     minMessage = "Le nombre d'heures restantes ne peut pas être négatif.",
	 *     maxMessage = "Le nombre d'heures restantes ne peut pas excéder 99 999.",
	 *     invalidMessage = "La valeur entrée (Nombres d'heures prises) doit être un nombre."
	 *     )
     */
    private $heures_prises;
	
	/**
     * @var string
     *
     * @ORM\Column(name="abonnement", type="string", length=255, nullable=true)
     */
    private $abonnement;

    /**
     * @var string
     *
     * @ORM\Column(name="rib", type="string", length=255, nullable=true)
     */
    private $rib;

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
     * @ORM\Column(name="filtre", type="boolean")
	 * @Assert\Type(type="bool")
     */
    private $filtre;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="flag", type="boolean")
	 * @Assert\Type(type="bool")
     */
    private $flag;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="date_expiration", type="date", nullable=true)
     */
    private $date_expiration;
	
	/**
	 * @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\Client", mappedBy="famille", cascade={"persist", "remove"})
	 * @Assert\Valid()
	 */
	private $clients;
	
	/**
	 * @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\Paiement", mappedBy="famille", cascade={"persist", "remove"})
	 */
	private $paiements;
	
	/**
	 * @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\Facture", mappedBy="famille", cascade={"persist", "remove"})
	 */
	private $factures;
	
	/**
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\Eleve", mappedBy="famille", cascade={"persist", "remove"})
	* @Assert\Valid()
	*/
	private $eleves;


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
     * Set heures_achetees
     *
     * @param integer $heuresAchetees
     * @return Famille
     */
    public function setHeuresAchetees($heuresAchetees)
    {
        $this->heures_achetees = $heuresAchetees;
    
        return $this;
    }

    /**
     * Get heures_achetees
     *
     * @return integer 
     */
    public function getHeuresAchetees()
    {
        return $this->heures_achetees;
    }

    /**
     * Set heures_restantes
     *
     * @param integer $heuresRestantes
     * @return Famille
     */
    public function setHeuresRestantes($heuresRestantes)
    {
        $this->heures_restantes = $heuresRestantes;
    
        return $this;
    }

    /**
     * Get heures_restantes
     *
     * @return string 
     */
    public function getHeuresRestantes()
    {
        return $this->heures_restantes;
    }

    /**
     * Set alerte_heures
     *
     * @param string $alerteHeures
     * @return Famille
     */
    public function setAlerteHeures($alerteHeures)
    {
        $this->alerte_heures = $alerteHeures;
    
        return $this;
    }

    /**
     * Get alerte_heures
     *
     * @return integer 
     */
    public function getAlerteHeures()
    {
        return $this->alerte_heures;
    }

    /**
     * Set immatriculation_urssaf
     *
     * @param string $immatriculationUrssaf
     * @return Famille
     */
    public function setImmatriculationUrssaf($immatriculationUrssaf)
    {
        $this->immatriculation_urssaf = $immatriculationUrssaf;
    
        return $this;
    }

    /**
     * Get immatriculation_urssaf
     *
     * @return string 
     */
    public function getImmatriculationUrssaf()
    {
        return $this->immatriculation_urssaf;
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
     * Set heures_prises
     *
     * @param integer $heuresPrises
     * @return Famille
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
     * Set abonnement
     *
     * @param string $abonnement
     * @return Famille
     */
    public function setAbonnement($abonnement)
    {
        $this->abonnement = $abonnement;
    
        return $this;
    }

    /**
     * Get abonnement
     *
     * @return string 
     */
    public function getAbonnement()
    {
        return $this->abonnement;
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
     * Set actif
     *
     * @param boolean $actif
     * @return Famille
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
    public function getFiltre()
    {
        return $this->filtre;
    }
	
	/**
     * Set actif
     *
     * @param boolean $actif
     * @return Famille
     */
    public function setFiltre($filtre)
    {
        $this->filtre = $filtre;
    
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
     * @return Famille
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
     * Set date_expiration
     *
     * @param \DateTime $dateExpiration
     * @return Client
     */
    public function setDateExpiration($dateExpiration)
    {
        $this->date_expiration = $dateExpiration;
    
        return $this;
    }

    /**
     * Get date_expiration
     *
     * @return \DateTime 
     */
    public function getDateExpiration()
    {
        return $this->date_expiration;
    }
	
	
	
	/**
     * Add clients
     *
     * @param \Majordesk\AppBundle\Entity\Client $client
     * @return Matiere
     */
    public function addClient(\Majordesk\AppBundle\Entity\Client $client)
    {
        $this->clients[] = $client;
		$client->setFamille($this);
    
        return $this;
    }

    /**
     * Remove clients
     *
     * @param \Majordesk\AppBundle\Entity\Client $client
     */
    public function removeClient(\Majordesk\AppBundle\Entity\Client $client)
    {
        $this->clients->removeElement($client);
    }

    /**
     * Get clients
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getClients()
    {
        return $this->clients;
    }
	
	/**
     * Add eleves
     *
     * @param \Majordesk\AppBundle\Entity\Eleve $eleve
     * @return Matiere
     */
    public function addEleve(\Majordesk\AppBundle\Entity\Eleve $eleve)
    {
        $this->eleves[] = $eleve;
		$eleve->setFamille($this);
    
        return $this;
    }

    /**
     * Remove eleves
     *
     * @param \Majordesk\AppBundle\Entity\Eleve $eleve
     */
    public function removeEleve(\Majordesk\AppBundle\Entity\Eleve $eleve)
    {
        $this->eleves->removeElement($eleve);
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
     * Is allowed nouvelles heures
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function isAllowedNewHours()
	{
		$message = '';
		$cours = 0;
        foreach($this->eleves as $eleve) {		
			foreach($eleve->getEleveMatieres() as $eleve_matiere) {
				// if ($eleve_matiere->getPlateforme() == 1 && $eleve_matiere->getPrelevementPlateforme() == 0) {
					// $message = 'Vous devez abonner '.$eleve->getUsername().' pour débloquer l\'achat d\'heures de cours.';
				// }
				if ($eleve_matiere->getCours() == 1) {
					$cours = 1;
				}
			}
		}
		if ($cours == 0) {
			$message = 'Veuillez inscrire un enfant à un cours pour débloquer l\'achat d\'heures de cours.';
		}
		
		return $message;
    }
	
	public function hasSuivi()
	{
		$suivi = false;
        foreach($this->eleves as $eleve) {		
			foreach($eleve->getEleveMatieres() as $eleve_matiere) {
				if ($eleve_matiere->getPlateforme() == 1 && $eleve_matiere->getPrelevementPlateforme() == 1) {
					$suivi = true;
				}
			}
		}
		return $suivi;
	}
	
	/**
     * Add paiements
     *
     * @param \Majordesk\AppBundle\Entity\Paiement $paiement
     * @return Famille
     */
    public function addPaiement(\Majordesk\AppBundle\Entity\Paiement $paiement)
    {
        $this->paiements[] = $paiement;
		$paiement->setFamille($this);
    
        return $this;
    }

    /**
     * Remove paiements
     *
     * @param \Majordesk\AppBundle\Entity\Paiement $paiement
     */
    public function removePaiement(\Majordesk\AppBundle\Entity\Paiement $paiement)
    {
        $this->paiements->removeElement($paiement);
    }

    /**
     * Get paiements
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPaiements()
    {
        return $this->paiements;
    }
	
	/**
     * Add factures
     *
     * @param \Majordesk\AppBundle\Entity\Facture $facture
     * @return Famille
     */
    public function addFacture(\Majordesk\AppBundle\Entity\Facture $facture)
    {
        $this->factures[] = $facture;
		$facture->setFamille($this);
    
        return $this;
    }

    /**
     * Remove factures
     *
     * @param \Majordesk\AppBundle\Entity\Facture $facture
     */
    public function removeFacture(\Majordesk\AppBundle\Entity\Facture $facture)
    {
        $this->factures->removeElement($facture);
    }

    /**
     * Get factures
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFactures()
    {
        return $this->factures;
    }
	
	/**
     * Get professeurs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProfesseurs()
    {
		$professeurs = array();
		foreach($this->eleves as $eleve) {
			$profs = $eleve->getProfesseurs();
			foreach($profs as $prof) {
				if (!in_array($prof,$professeurs)){
					$professeurs[] = $prof;
				}
			}
		}
				
        return $professeurs;
    }
	
	/**
     * Get 
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMails()
    {
		$mails = array();
		foreach($this->clients as $client) {
			$mails[] = $client->getMail();
		}
				
        return $mails;
    }
	
	/**
     * Get 
     *
     * @return
     */
    public function getMail()
    {	
        return $this->clients[0]->getMail();
    }
	
	/**
     * Get 
     *
     * @return
     */
    public function getGender()
    {	
        return $this->clients[0]->getGender();
    }
	
	/**
     * Get 
     *
     * @return
     */
    public function getNom()
    {	
        return $this->clients[0]->getNom();
    }
	
	/**
     * Get 
     *
     * @return
     */
    public function getAdresse()
    {	
        return $this->clients[0]->getAdresse();
    }
	
	/**
     * Get 
     *
     * @return
     */
    public function getCodePostal()
    {	
        return $this->clients[0]->getCodePostal();
    }
	
	/**
     * Get 
     *
     * @return
     */
    public function getVille()
    {	
        return $this->clients[0]->getVille();
    }
	
	/**
     * Get 
     *
     * @return
     */
    public function getTelephone()
    {	
        return $this->clients[0]->getTelephone();
    }
	
	/**
     * Get 
     *
     * @return
     */
    public function getDateInscription()
    {	
        return $this->clients[0]->getDateInscription();
    }

	public function __construct()
	{
		$this->heures_achetees = 0;
		$this->heures_restantes = 0;
		$this->heures_prises = 0;
		$this->alerte_heures = 0;
		$this->abonnement = null;
		$this->immatriculation_urssaf = null;
		$this->rib = null;
		$this->filtre = false;
		$this->actif = true;
		$this->flag = false;
	}
}
