<?php
namespace Majordesk\AppBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

use Majordesk\AppBundle\Entity\ModExercice;
use Majordesk\AppBundle\Entity\ModQuestion;

class ExerciceFormulaire
{
    /**
     * @Assert\Type(type="Majordesk\AppBundle\Entity\ModExercice")
	 * @Assert\Valid()
     */
    protected $mod_exercice;
	
	/**
	 * @Assert\Valid()
     */
    protected $mod_questions;

	
    public function setModExercice(ModExercice $mod_exercice)
    {
        $this->mod_exercice = $mod_exercice;
    }

    public function getModExercice()
    {
        return $this->mod_exercice;
    }
	
	public function setModQuestions($mod_questions)
    {
        $this->mod_questions = $mod_questions;
    }

    public function getModQuestions()
    {
        return $this->mod_questions;
    }
}