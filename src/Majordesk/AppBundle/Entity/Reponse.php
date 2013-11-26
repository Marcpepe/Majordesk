<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reponse
 *
 * @ORM\Table(name="reponse")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\ReponseRepository")
 */
class Reponse
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
     * @var text
     *
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;

    /**
     * @var string
     *
     * @ORM\Column(name="valeur", type="string", length=1)
     */
    private $valeur;
	
	 /**
     * @var integer
     *
     * @ORM\Column(name="numero", type="smallint")
     */
    private $numero;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Question", inversedBy="reponses")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $question;


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
     * @return Reponse
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
     * Set valeur
     *
     * @param string $valeur
     * @return Reponse
     */
    public function setValeur($valeur)
    {
		if ($valeur === true) {
			$this->valeur = 1;
		}
		else {
			$this->valeur = 0;
		}
    
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
     * Set numero
     *
     * @param integer $numero
     * @return Reponse
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    
        return $this;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set question
     *
     * @param \Majordesk\AppBundle\Entity\Question $question
     * @return Reponse
     */
    public function setQuestion(\Majordesk\AppBundle\Entity\Question $question)
    {
        $this->question = $question;
    
        return $this;
    }

    /**
     * Get question
     *
     * @return \Majordesk\AppBundle\Entity\Question 
     */
    public function getQuestion()
    {
        return $this->question;
    }
	
	
	public function __construct()
	{
		$this->valeur = false;
	}
}