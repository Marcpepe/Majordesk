<?php

namespace Majordesk\AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Majordesk\AppBundle\Entity\Exercice;
use Majordesk\AppBundle\Entity\Question;
 
class AI
{
	protected $em;
	protected $statut_resolu;
	protected $statut_non_resolu;
	protected $statut_non_commence;
	protected $statut_en_ligne;

	public function __construct(EntityManager $em, $statut_resolu, $statut_non_resolu, $statut_non_commence, $statut_en_ligne)
	{
		$this->em = $em;
		$this->statut_resolu = $statut_resolu;
		$this->statut_non_resolu = $statut_non_resolu;
		$this->statut_non_commence = $statut_non_commence;
		$this->statut_en_ligne = $statut_en_ligne;
	}
	
	/**
     * @info Sélectionne un exercice parmi ceux sélectionné par le professeur
     */
	public function addFromSelectedInChapitreToQueue($user, $id_chapitre)
	{
		$exercice = $this->em->getRepository('MajordeskAppBundle:Exercice')
							 ->getSelectedExerciceResoluByChapitre($user, $id_chapitre, $this->statut_resolu);
		
		if ($exercice != null) {
			$selection = $exercice->getSelection();
			if ($selection > 1) {
				$exercice->setSelection(1);
				$new_exercice = $this->createRandomInPartie($user, $exercice->getModExercice()->getPartie()->getId());
				$new_exercice->setSelection($selection-1);
			}
			else if ($selection < -1) {
				$exercice->setSelection(-1);
				$new_exercice = $this->addRandomInChapitreByMatiereToQueue($user, $id_chapitre);
				$new_exercice->setSelection($selection+1);
			}
			else {
				throw new \Exception('la variable $selection ne devrait pas avoir cette valeur.');
			}
			$this->em->persist($exercice);
			if ($new_exercice != null) {
				$this->em->persist($new_exercice);
			}
			$this->em->flush();
			if ($new_exercice != null) {
				return $new_exercice;
			}
		}
		return null;
	}
	
	/**
     * @info Sélectionne un exercice parmi ceux sélectionné par le professeur
     */
	public function addFromSelectedToQueue($user, $id_matiere)
	{
		$exercice = $this->em->getRepository('MajordeskAppBundle:Exercice')
							 ->getSelectedExerciceResoluByMatiere($user, $id_matiere, $this->statut_resolu);
	
		if ($exercice != null) {
			$id_chapitre = $exercice->getModExercice()->getChapitre()->getId();
			$selection = $exercice->getSelection();
			if ($selection > 1) {
				$exercice->setSelection(1);
				$new_exercice = $this->createRandomInPartie($user, $exercice->getModExercice()->getPartie()->getId());
				if ($new_exercice != null) {
					$new_exercice->setSelection($selection-1);
				}
			}
			else if ($selection < -1) {
				$exercice->setSelection(-1);
				$new_exercice = $this->addRandomInChapitreByMatiereToQueue($user, $id_chapitre);
				if ($new_exercice != null) {
					$new_exercice->setSelection($selection+1);
				}
			}
			else {
				throw new \Exception('la variable $selection ne devrait pas avoir cette valeur.');
			}
			$this->em->persist($exercice);
			if ($new_exercice != null) {
				$this->em->persist($new_exercice);
			}
			$this->em->flush();
			if ($new_exercice != null) {
				return $new_exercice;
			}
		}
		return null;
	}
	
	/**
     * @info Sélectionne un exercice
     */
	public function addRandomByMatiereToQueue($user, $id_matiere)
	{
		$exercice = null;
		$last_exercice = $this->em->getRepository('MajordeskAppBundle:Exercice')
			     	              ->getLastExerciceByMatiere($user, $id_matiere);
		
		if ($last_exercice != null) {
			$chapitre = $last_exercice->getModExercice()->getChapitre();
			$partie = $last_exercice->getModExercice()->getPartie();
			
			$numero_partie = $partie->getNumero();
			$numero_partie_init = $partie->getNumero();
			$numero_partie_max = count($chapitre->getParties());
			
			//---// Commenter cette section pour désactiver l'analyse de passage automatique à la partie suivante
			$limit_ = 6;
			$limit_justes = 4;
			$limit_justes_affile = 2;
			$nb_ = 0;
			$nb_justes = 0;
			$nb_justes_affile = 0;
			
			$exercices = $this->em->getRepository('MajordeskAppBundle:Exercice')
					              ->getLastExercicesByChapitre($user, $chapitre->getId(), $limit_);
								  
			foreach($exercices as $exo) {
				if ($exo->getModExercice()->getPartie()->getNumero() == $numero_partie_init) {
					$nb_++;
					if ($exo->getStatut() == $this->statut_resolu && $exo->getNombreEssais() == $exo->getNombreQuestions()) {
						$nb_justes++;
						$nb_justes_affile++;
					}
					else {
						$nb_justes_affile = 0;
					}
				}
				else {
					break;
				}
			}
			
			if ($nb_ >= $limit_ || $nb_justes >= $limit_justes || $nb_justes_affile >= $limit_justes_affile) {
				if ($numero_partie_init < $numero_partie_max) {
					$numero_partie_init++;
				}
				else {
					$numero_partie_init = 1;
				}
				$partie = $this->em->getRepository('MajordeskAppBundle:Partie')
								   ->getPartieInChapitreByNumero($chapitre->getId(), $numero_partie_init);
			}
			//---//
			
			while( $exercice == null && $numero_partie <= $numero_partie_max ) {
				$exercice = $this->createRandomInPartie($user, $partie->getId());
				if ($exercice == null) {
					$numero_partie = $partie->getNumero() + 1;
					$partie = $this->em->getRepository('MajordeskAppBundle:Partie')
									   ->getPartieInChapitreByNumero($chapitre->getId(), $numero_partie);
				}
			}
			if ($exercice == null && $numero_partie_init > 1) {
				$numero_partie = $numero_partie_init - 1;
				$partie = $this->em->getRepository('MajordeskAppBundle:Partie')
								   ->getPartieInChapitreByNumero($chapitre->getId(), $numero_partie);
				while( $exercice == null && $numero_partie >= 1 ) {
					$exercice = $this->createRandomInPartie($user, $partie->getId());
					if ($exercice == null) {
						$numero_partie = $partie->getNumero() - 1;
						$partie = $this->em->getRepository('MajordeskAppBundle:Partie')
										   ->getPartieInChapitreByNumero($chapitre->getId(), $numero_partie);
					}
				}
			}
		}
		return $exercice;
	}
	
	/**
     * @info Sélectionne un exercice dans un chapitre donné
     */
	public function addRandomInChapitreByMatiereToQueue($user, $id_chapitre, $selection = 0)
	{
		$exercice = null;
		$last_exercice = $this->em->getRepository('MajordeskAppBundle:Exercice')
			     	              ->getLastExerciceInChapitreByMatiere($user, $id_chapitre);
		
		if ($last_exercice != null) {
			$chapitre = $last_exercice->getModExercice()->getChapitre();
			$partie = $last_exercice->getModExercice()->getPartie();
			$numero_partie_init = $partie->getNumero();
			$numero_partie_max = count($chapitre->getParties());
			
			//---// Commenter cette section pour désactiver l'analyse de passage automatique à la partie suivante
			$limit_ = 6;
			$limit_justes = 4;
			$limit_justes_affile = 2;
			$nb_ = 0;
			$nb_justes = 0;
			$nb_justes_affile = 0;
			
			$exercices = $this->em->getRepository('MajordeskAppBundle:Exercice')
					              ->getLastExercicesByChapitre($user, $chapitre->getId(), $limit_);
								  
			foreach($exercices as $exo) {
				if ($exo->getModExercice()->getPartie()->getNumero() == $numero_partie_init) {
					$nb_++;
					if ($exo->getStatut() == $this->statut_resolu && $exo->getNombreEssais() == $exo->getNombreQuestions()) {
						$nb_justes++;
						$nb_justes_affile++;
					}
					else {
						$nb_justes_affile = 0;
					}
				}
				else {
					break;
				}
			}
			
			if ($nb_ == $limit_ || $nb_justes >= $limit_justes || $nb_justes_affile >= $limit_justes_affile) {
				if ($numero_partie_init < $numero_partie_max) {
					$numero_partie_init++;
				}
				else {
					$numero_partie_init = 1;
				}
				$partie = $this->em->getRepository('MajordeskAppBundle:Partie')
								   ->getPartieInChapitreByNumero($chapitre->getId(), $numero_partie_init);
				$numero_partie = $partie->getNumero();
			}
			else {
				$numero_partie = $numero_partie_init;
			}
			//---// 
			
			
			while( $exercice == null && $numero_partie <= $numero_partie_max ) {
				$exercice = $this->createRandomInPartie($user, $partie->getId(), $selection);
				if ($exercice == null) {
					$numero_partie = $partie->getNumero() + 1;
					$partie = $this->em->getRepository('MajordeskAppBundle:Partie')
									   ->getPartieInChapitreByNumero($chapitre->getId(), $numero_partie);
				}
			}
			if ($exercice == null && $numero_partie_init > 1) {
				$numero_partie = $numero_partie_init - 1;
				$partie = $this->em->getRepository('MajordeskAppBundle:Partie')
								   ->getPartieInChapitreByNumero($chapitre->getId(), $numero_partie);
				while( $exercice == null && $numero_partie >= 1 ) {
					$exercice = $this->createRandomInPartie($user, $partie->getId(), $selection);
					if ($exercice == null) {
						$numero_partie = $partie->getNumero() - 1;
						$partie = $this->em->getRepository('MajordeskAppBundle:Partie')
										   ->getPartieInChapitreByNumero($chapitre->getId(), $numero_partie);
					}
				}
			}
		}
		else {	
			$partie = $this->em->getRepository('MajordeskAppBundle:Partie')
							   ->getPartieInChapitreByNumero($id_chapitre, 1);
			
			while( $exercice == null && $partie != null ) 
			{
				$exercice = $this->createRandomInPartie($user, $partie->getId(), $selection);
				if ($exercice == null) {
					$partie = $this->em->getRepository('MajordeskAppBundle:Partie')
									   ->getPartieInChapitreByNumero($id_chapitre, $partie->getNumero() + 1);
				}
			}
		}
		return $exercice;
	}
	
	/**
     * @info Sélectionne un exercice dans une partie en fonction des caractérisques de l'élève
	 * @algo L'exercice est sélectionné en fonction :
	 *    - du statut de chaque exercice;
	 *    - de la difficulté de chaque exercice;
	 *    - du nombre d'essais pour chaque exercice;
     */
	public function createRandomInPartie($user, $id_partie, $selection = 0)
	{
		$eleve = $this->em->getRepository('MajordeskAppBundle:Eleve')
						  ->find($user);
		$limit = 3; // l'analyse portera sur les ? derniers exercices faits
		$reliquat_essais_par_question_down = 1.8; // au delà de ? essai(s) superflu(s) par question, regression au niveau de difficulté inférieur
		$reliquat_essais_par_question_up = 0.8; // en deçà de ? essai(s) superflu(s) par question, progression au niveau de difficulté supérieur
		$niveau_max = 5;
		
		$exercices = $this->em->getRepository('MajordeskAppBundle:Exercice')
					          ->getLastExercicesByPartie($user, $id_partie, $limit);
		
		$somme = 0;
		foreach( $exercices as $exercice ) {
			$somme += $exercice->getNiveau();
		}	
		if ( count($exercices) != 0 ) {
			$niveau_moyen = round($somme / count($exercices));
		}
		else {
			$niveau_moyen = 0;
		}
		
		$resolu = 0;
		$reliquat_essais = 0;
		$nombre_questions = 0;
		foreach( $exercices as $exercice ) {
			if ($exercice->getStatut() == $this->statut_resolu) {
				$resolu++;
				$reliquat_essais += $exercice->getNombreEssais() - $exercice->getNombreQuestions();	
				$nombre_questions += $exercice->getNombreQuestions();	
			}
			else if ($exercice->getStatut() == $this->statut_non_resolu) {
				$resolu++;
				$reliquat_essais += $exercice->getNombreEssais() - $exercice->getNombreQuestionsCommencees($this->statut_non_commence);
				$nombre_questions += $exercice->getNombreQuestionsCommencees($this->statut_non_commence);	
			}					
		}
		
		if ( $nombre_questions != 0 ) {
			$reliquat_essais_par_question = $reliquat_essais / $nombre_questions;
			
			if ( $reliquat_essais_par_question >= $reliquat_essais_par_question_down ) {
				$niveau = min( max( 0, $niveau_moyen - 1), $niveau_max);
			}
			else if ( $reliquat_essais_par_question <= $reliquat_essais_par_question_up ) {
				$niveau = min( max( 0, $niveau_moyen + 1), $niveau_max);
			}
			else {
				$niveau = min( max( 0, $niveau_moyen ), $niveau_max);
			}
		}
		else {
			$niveau = 0;
		}
		
		$niveau_init = $niveau;
		$exercice = null;
		
		while( $exercice == null && $niveau <= $niveau_max ) {
			$mod_exercice = $this->em->getRepository('MajordeskAppBundle:ModExercice')
									 ->getRandomModExerciceByPartieByNiveau($user, $id_partie, $niveau, $this->statut_en_ligne);
					 
			if ($mod_exercice != null) {
				$exercice = new Exercice();
				$exercice->setModExercice($mod_exercice);
				$exercice->setEleve($eleve);
				$exercice->setQueue(1);

				$mod_questions = $mod_exercice->getModQuestions();
				foreach( $mod_questions as $mod_question ) {
					if ( $mod_question->getType() == null || $mod_question->getType() == 'question') {
						$question = new Question();
						$question->setModQuestion($mod_question);
						$question->setExercice($exercice); // /!\ devrait faire partie de la même action
						$question->setNumero($mod_question->getNumero());
						$exercice->addQuestion($question); // /!\ devrait faire partie de la même action
					}
				}
				
				if ($selection != 0) {
					$exercice->setSelection($selection);
				}
				$this->em->persist($exercice);
				$this->em->flush();
			}
			else {
				$exercice = $this->em->getRepository('MajordeskAppBundle:Exercice')
									 ->getExerciceByPartieByNiveauByStatut($user, $id_partie, $niveau, $this->statut_en_ligne, $this->statut_non_resolu);
				if ( $exercice == null ) {
					$exercice = $this->em->getRepository('MajordeskAppBundle:Exercice')
										 ->getExerciceByPartieByNiveauByStatut($user, $id_partie, $niveau, $this->statut_en_ligne, $this->statut_non_commence);
				}
			}
			$niveau++;
		}
		
		$niveau = $niveau_init;
		
		while( $exercice == null && $niveau >= 0 ) {
			$mod_exercice = $this->em->getRepository('MajordeskAppBundle:ModExercice')
									 ->getRandomModExerciceByPartieByNiveau($user, $id_partie, $niveau, $this->statut_en_ligne);
			
			if ($mod_exercice != null) {
				$exercice = new Exercice();
				$exercice->setModExercice($mod_exercice);
				$exercice->setEleve($eleve);
				$exercice->setQueue(1);

				$mod_questions = $mod_exercice->getModQuestions();
				foreach( $mod_questions as $mod_question ) {
					$question = new Question();
					$question->setModQuestion($mod_question);
					$question->setExercice($exercice);
					$question->setNumero($mod_question->getNumero());
					$exercice->addQuestion($question);
				}
				
				if ($selection != 0) {
					$exercice->setSelection($selection);
				}
				$this->em->persist($exercice);
				$this->em->flush();
			}
			else {
				$exercice = $this->em->getRepository('MajordeskAppBundle:Exercice')
									 ->getExerciceByPartieByNiveauByStatut($user, $id_partie, $niveau, $this->statut_en_ligne, $this->statut_non_resolu);
				if ( $exercice == null ) {
					$exercice = $this->em->getRepository('MajordeskAppBundle:Exercice')
										 ->getExerciceByPartieByNiveauByStatut($user, $id_partie, $niveau, $this->statut_en_ligne, $this->statut_non_commence);
				}
			}
			$niveau--;
		}
		
		return $exercice;
	}
}