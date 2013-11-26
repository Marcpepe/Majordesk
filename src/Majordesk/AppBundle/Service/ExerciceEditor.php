<?php

namespace Majordesk\AppBundle\Service;

use Majordesk\AppBundle\Entity\ModExercice;
use Majordesk\AppBundle\Entity\ModQuestion;
use Majordesk\AppBundle\Entity\ModMacro;
use Majordesk\AppBundle\Entity\ModElement;
use Majordesk\AppBundle\Form\Model\ExerciceFormulaire;
use Doctrine\ORM\EntityManager;
 
class ExerciceEditor
{
	protected $em;
	protected $statut_en_edition;
	protected $macro_type_normal;
	protected $macro_type_erreur;
	protected $macro_type_indice;
	protected $macro_type_info;
	protected $macro_type_correction;
	protected $element_type_text;

	public function __construct(EntityManager $em, $statut_en_edition, $macro_type_normal, $macro_type_erreur, $macro_type_indice, $macro_type_info, $macro_type_correction, $element_type_text)
	{
		$this->em = $em;
		$this->statut_en_edition     = $statut_en_edition;
		$this->macro_type_normal     = $macro_type_normal;
		$this->macro_type_erreur     = $macro_type_erreur;
		$this->macro_type_indice     = $macro_type_indice;
		$this->macro_type_info       = $macro_type_info;
		$this->macro_type_correction = $macro_type_correction;
		$this->element_type_text     = $element_type_text;
	}

	/**
     * @info 
     */
	public function linkNewEntities($mod_exercice, $mod_questions)
	{
		$mod_macros = $mod_exercice->getModMacros();
		foreach( $mod_macros as $mod_macro)
		{
			$mod_exercice->addModMacro($mod_macro);
			$mod_elements = $mod_macro->getModElements();
			
			foreach( $mod_elements as $mod_element)
			{
				$mod_macro->addModElement($mod_element);
			}
		}
		
		foreach( $mod_questions as $mod_question)
		{
			$mod_macros = $mod_question->getModMacros();
			$mod_mappings = $mod_question->getModMappings();

			foreach( $mod_macros as $mod_macro)
			{
				$mod_question->addModMacro($mod_macro);
				$mod_elements = $mod_macro->getModElements();
				foreach( $mod_elements as $mod_element)
				{
					$mod_macro->addModElement($mod_element);
				}
			}
			
			foreach( $mod_mappings as $mod_mapping)
			{
				$mod_question->addModMapping($mod_mapping);
				$mod_reponses = $mod_mapping->getModReponses();
				foreach( $mod_reponses as $mod_reponse)
				{
					$mod_mapping->addModReponse($mod_reponse);
				}
			}	
			
			$mod_exercice->addModQuestion($mod_question);
		}
	}
	
	/**
     * @info Initialise un nouvel exercice
     */
	// public function initializeAdd(ExerciceFormulaire $exercice_formulaire, $niveau)
	// {
		// //Initilisation ModExercice
		// $mod_exercice = new ModExercice();
		// $mod_exercice->updateDateEnregistrementToCurrentDate();
		// $mod_exercice->setStatut($this->statut_en_edition);

		// if ($niveau == 0) {
				// $mod_macro = new ModMacro();
				// $mod_macro->setNumero(1);
				// $mod_macro->setType($this->macro_type_normal);
				// $mod_macro->setFond(1); //tutocours
				
					// $mod_element = new ModElement();
					// $mod_element->setNumero(1);
					// $mod_element->setType($this->element_type_text);
					
				// $mod_macro->addModElement($mod_element);
				
			// $mod_exercice->addModMacro($mod_macro);
			// $mod_exercice->setNiveau(0);
		// }
		// else {
				// $mod_macro = new ModMacro();
				// $mod_macro->setNumero(1);
				// $mod_macro->setType($this->macro_type_normal);
				// $mod_macro->setFond(2); //entête exercice
				
					// $mod_element = new ModElement();
					// $mod_element->setNumero(1);
					// $mod_element->setType($this->element_type_text);
					
				// $mod_macro->addModElement($mod_element);
				
			// $mod_exercice->addModMacro($mod_macro);
			// $mod_exercice->setNiveau(1);
		// }
		// //Initilisation ModQuestion
		// $mod_question = new ModQuestion();
		// $mod_question->setNumero(1);
		
			// $mod_macro = new ModMacro();
			// $mod_macro->setNumero(1);
			// $mod_macro->setType($this->macro_type_normal);
			
				// $mod_element = new ModElement();
				// $mod_element->setNumero(1);
				// $mod_element->setType($this->element_type_text);
				
			// $mod_macro->addModElement($mod_element);

		// $mod_question->addModMacro($mod_macro);		
		
			// $mod_macro = new ModMacro();
			// $mod_macro->setNumero(2);
			// $mod_macro->setType($this->macro_type_normal);
			// $mod_macro->setFond(4); //indice
			
				// $mod_element = new ModElement();
				// $mod_element->setNumero(1);
				// $mod_element->setType($this->element_type_text);
				
			// $mod_macro->addModElement($mod_element);

		// $mod_question->addModMacro($mod_macro);	
		
			// $mod_macro = new ModMacro();
			// $mod_macro->setNumero(3);
			// $mod_macro->setType($this->macro_type_normal);
			// $mod_macro->setFond(5); //correction
			
			
				// $mod_element = new ModElement();
				// $mod_element->setNumero(1);
				// $mod_element->setType($this->element_type_text);
				
			// $mod_macro->addModElement($mod_element);

		// $mod_question->addModMacro($mod_macro);	

		
		// $exercice_formulaire->setModQuestions(array($mod_question));
		// $exercice_formulaire->setModExercice($mod_exercice);
	// }
	
	/**
     * @info Initialise un duplicatat d'un exercice déjà existant
     */
	// public function initializeDuplicate(ExerciceFormulaire $exercice_formulaire, ModExercice $mod_exercice, $mod_questions)
	// {
		// $mod_exercice_clone = clone $mod_exercice;
		// $mod_questions_clone = array();
		
		// foreach($mod_questions as $mod_question)
		// {
			// $mod_question_clone = clone $mod_question;
			// $mod_questions_clone[] = $mod_question_clone;
			
			// $mod_question_clone->sortModMacros();
			// $mod_question_clone->sortModMappingsByNumeroReponse();
		// }
		// $exercice_formulaire->setModQuestions($mod_questions_clone);
		// $mod_exercice_clone->updateDateEnregistrementToCurrentDate();
		// $exercice_formulaire->setModExercice($mod_exercice_clone);
	// }
	
	/**
     * @info Initialise un exercice déjà existant
     */
	public function initializeModify(ExerciceFormulaire $exercice_formulaire, ModExercice $mod_exercice, $mod_questions)
	{
		foreach($mod_questions as $mod_question)
		{
			$mod_question->sortModMacros();
			$mod_question->sortModMappingsByNumeroReponse();
		}
		$exercice_formulaire->setModQuestions($mod_questions);
		$mod_exercice->updateDateEnregistrementToCurrentDate();
		$exercice_formulaire->setModExercice($mod_exercice);
	}
	
	/**
     * NEW (Briques et SuperBriques)
     */
	 
	 /**
      * @info: incrémente de 'incr' le numéro de toutes les superbriques de numéro strictement supérieur à 'numéro'
      */
	public function incrementSuperBriques($id_exercice, $numero, $incr)
	{
		$this->em->getRepository('MajordeskAppBundle:ModQuestion')
		         ->incrementModQuestions($id_exercice, $numero, $incr);
	}
	
	public function incrementBriquesInSuperBrique($id_superbrique, $numero, $incr)
	{
		$this->em->getRepository('MajordeskAppBundle:ModBrique')
		         ->incrementModBriquesInSuperBrique($id_superbrique, $numero, $incr);
	}
	
	public function incrementBriquesInComplement($id_complement, $numero, $incr)
	{
		$this->em->getRepository('MajordeskAppBundle:ModBrique')
		         ->incrementModBriquesInComplement($id_complement, $numero, $incr);
	}
	
	public function incrementReponsesInQuestion($id_superbrique, $numero, $incr)
	{
		$mod_question = $this->em->getRepository('MajordeskAppBundle:ModQuestion')
		                         ->find($id_superbrique);
		$mod_mappings = $mod_question->getModMappings();						 
		foreach($mod_mappings as $mod_mapping) {
			$this->em->getRepository('MajordeskAppBundle:ModReponse')
					 ->incrementModReponsesInMapping($mod_mapping->getId(), $numero, $incr);
		}
	}
	
	public function incrementReponsesInMapping($id_mapping, $numero, $incr)
	{
		$this->em->getRepository('MajordeskAppBundle:ModReponse')
				 ->incrementModReponsesInMapping($id_mapping, $numero, $incr);
	}
}