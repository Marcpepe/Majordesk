<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MicroRep
 *
 * @ORM\Table(name="microrep")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\MicroRepRepository")
 */
class MicroRep
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
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;
	
	/**
     * @var smallint
     *
     * @ORM\Column(name="numero", type="smallint")
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="valeur", type="boolean")
     */
    private $valeur;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Rep", inversedBy="micro_reps")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $rep;


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
     * Set contenu
     *
     * @param string $contenu
     * @return MicroRep
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
    
        return $this;
    }

    /**
     * Get contenu
     *
     * @return string 
     */
    public function getContenu()
    {
        return $this->contenu;
    }
	
	/**
     * Set numero
     *
     * @param smallint $numero
     * @return MicroRep
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    
        return $this;
    }
	
	/**
     * Get numero
     *
     * @return string 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set valeur
     *
     * @param string $valeur
     * @return MicroRep
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
     * Set rep
     *
     * @param string $rep
     * @return MicroRep
     */
    public function setRep($rep)
    {
        $this->rep = $rep;
    
        return $this;
    }

    /**
     * Get rep
     *
     * @return string 
     */
    public function getRep()
    {
        return $this->rep;
    }
}
