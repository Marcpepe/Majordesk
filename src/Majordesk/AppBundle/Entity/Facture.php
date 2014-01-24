<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Facture
 *
 * @ORM\Table(name="facture")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\FactureRepository")
 */
class Facture
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
     * @ORM\Column(name="dateEmission", type="datetime")
     */
    private $dateEmission;

    /**
     * @var integer
     *
     * @ORM\Column(name="total", type="integer")
     */
    private $total;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Famille", inversedBy="factures")
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
     * Set dateEmission
     *
     * @param \DateTime $dateEmission
     * @return Facture
     */
    public function setDateEmission($dateEmission)
    {
        $this->dateEmission = $dateEmission;
    
        return $this;
    }

    /**
     * Get dateEmission
     *
     * @return \DateTime 
     */
    public function getDateEmission()
    {
        return $this->dateEmission;
    }

    /**
     * Set total
     *
     * @param integer $total
     * @return Facture
     */
    public function setTotal($total)
    {
        $this->total = $total;
    
        return $this;
    }

    /**
     * Get total
     *
     * @return integer 
     */
    public function getTotal()
    {
        return $this->total;
    }
	
	/**
     * Set famille
     *
     * @param \Majordesk\AppBundle\Entity\Famille $famille
     * @return Paiement
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
}
