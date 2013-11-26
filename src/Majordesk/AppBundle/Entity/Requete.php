<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Requete
 *
 * @ORM\Table(name="requete")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\RequeteRepository")
 */
class Requete
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
     * @ORM\Column(name="date_envoi", type="datetime")
     */
    private $date_envoi;

    /**
     * @var integer
     *
     * @ORM\Column(name="numero_fichier", type="smallint")
     */
    private $numero_fichier;

    /**
     * @var string
     *
     * @ORM\Column(name="statut_envoi", type="string", length=1)
     */
    private $statut_envoi;

    /**
     * @var string
     *
     * @ORM\Column(name="statut_reception", type="string", length=1)
     */
    private $statut_reception;


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
     * Set dateEnvoi
     *
     * @param \DateTime $dateEnvoi
     * @return Requete
     */
    public function setDateEnvoi($date_envoi)
    {
        $this->date_envoi = $date_envoi;
    
        return $this;
    }

    /**
     * Get dateEnvoi
     *
     * @return \DateTime 
     */
    public function getDateEnvoi()
    {
        return $this->date_envoi;
    }

    /**
     * Set numeroFichier
     *
     * @param integer $numeroFichier
     * @return Requete
     */
    public function setNumeroFichier($numero_fichier)
    {
        $this->numero_fichier = $numero_fichier;
    
        return $this;
    }

    /**
     * Get numeroFichier
     *
     * @return integer 
     */
    public function getNumeroFichier()
    {
        return $this->numero_fichier;
    }

    /**
     * Set statutEnvoi
     *
     * @param string $statutEnvoi
     * @return Requete
     */
    public function setStatutEnvoi($statut_envoi)
    {
        $this->statut_envoi = $statut_envoi;
    
        return $this;
    }

    /**
     * Get statutEnvoi
     *
     * @return string 
     */
    public function getStatutEnvoi()
    {
        return $this->statut_envoi;
    }

    /**
     * Set statutReception
     *
     * @param string $statutReception
     * @return Requete
     */
    public function setStatutReception($statut_reception)
    {
        $this->statut_reception = $statut_reception;
    
        return $this;
    }

    /**
     * Get statutReception
     *
     * @return string 
     */
    public function getStatutReception()
    {
        return $this->statut_reception;
    }
	
	public function __construct()
	{
		$this->date_envoi = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
		$this->statut_envoi = '0'; // 0: non envoyé, 1: envoyé
		$this->statut_reception = '0'; // 0: pas de réponse, 1: réponse reçue
	}
}
