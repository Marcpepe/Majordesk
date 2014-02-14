<?php

namespace Majordesk\AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * Casier
 *
 * @ORM\Table(name="casier")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\CasierRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Casier
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
     * @ORM\Column(name="chemin", type="string", length=255)
     */
    private $chemin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEnregistrement", type="datetime")
     */
    private $dateEnregistrement;
	
	/**
	 * @Assert\File(
	 *     maxSize = "5M",
	 *     mimeTypes = {"application/pdf", "application/x-pdf", "image/jpeg", "image/jpg", "image/gif", "image/png"},
	 *     mimeTypesMessage = "Le fichier doit être un PDF ou une image de type jpeg, png ou gif."
	 * )
	 */
	private $file;
	
	/**
	 * @ORM\OneToOne(targetEntity="Majordesk\AppBundle\Entity\Professeur", inversedBy="casier")
	 */
	private $professeur;


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
     * Set chemin
     *
     * @param string $chemin
     * @return Casier
     */
    public function setChemin($chemin)
    {
        $this->chemin = $chemin;
    
        return $this;
    }

    /**
     * Get chemin
     *
     * @return string 
     */
    public function getChemin()
    {
        return $this->chemin;
    }

    /**
     * Set dateEnregistrement
     *
     * @param \DateTime $dateEnregistrement
     * @return Casier
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
     * Set file
     *
     * @param 
     * @return 
     */
    public function setFile($file)
    {
        $this->file = $file;
    
        return $this;
    }

    /**
     * Get dateEnregistrement
     *
     * @return 
     */
    public function getFile()
    {
        return $this->file;
    }
	
	/**
     * Set professeur
     *
     * @param \Majordesk\AppBundle\Entity\Professeur $professeur
     * @return Ticket
     */
    public function setProfesseur(\Majordesk\AppBundle\Entity\Professeur $professeur)
    {
        $this->professeur = $professeur;
    
        return $this;
    }

    /**
     * Get professeur
     *
     * @return \Majordesk\AppBundle\Entity\Professeur 
     */
    public function getProfesseur()
    {
        return $this->professeur;
    }
	
	
	
	
	public function getAbsolutePath()
    {
        return null === $this->chemin ? null : $this->getUploadRootDir().'/'.$this->chemin;
    }

    public function getWebPath()
    {
        return null === $this->chemin ? null : $this->getUploadDir().'/'.$this->chemin;
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'uploads';
    }
	
	
	
	
	/**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            // faites ce que vous voulez pour générer un nom unique
            $this->chemin = sha1(uniqid(mt_rand(), true)).'.'.$this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        // s'il y a une erreur lors du déplacement du fichier, une exception
        // va automatiquement être lancée par la méthode move(). Cela va empêcher
        // proprement l'entité d'être persistée dans la base de données si
        // erreur il y a
        $this->file->move($this->getUploadRootDir(), $this->chemin);

        unset($this->file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }
	
	
	public function __construct()
	{
		$this->dateEnregistrement = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
	}
}
