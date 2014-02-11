<?php

namespace Majordesk\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;
use Majordesk\AppBundle\Form\Type\FamilleType;
use Majordesk\AppBundle\Entity\Famille;
use Majordesk\AppBundle\Entity\Feedback;
use Majordesk\AppBundle\Entity\ModQuestion;
use Majordesk\AppBundle\Entity\ModComplement;
use Majordesk\AppBundle\Entity\ModReponse;
use Majordesk\AppBundle\Entity\ModMapping;
use Majordesk\AppBundle\Entity\ModBrique;
use Symfony\Component\HttpFoundation\JsonResponse;

class AjaxController extends Controller
{

	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function rechercheExercicesPartieAction($id_partie)
    {
		$user = $this->getUser();
		$mod_exercices = $this->getDoctrine()
							  ->getManager()
							  ->getRepository('MajordeskAppBundle:ModExercice')
							  ->getUngeneratedOnlineModExercicesByPartie($id_partie, $user->getId());
		$result = array();
		foreach($mod_exercices as $mod_exercice) {
			$exo = array();
			$exo['niveau'] = $mod_exercice->getNiveau();
			$exo['id'] = $mod_exercice->getId();
			$exo['preview'] = $mod_exercice->getPreview('<a href="'.$this->generateUrl('majordesk_app_generate_exercice', array('id' => $mod_exercice->getId())).'" class="btn btn-success width-100"><i class="icon-play icon-2x"></i></a><br><br><div class="btn-group width-100"><button type="button" class="btn btn-default dropdown-toggle width-100" data-toggle="dropdown"><i class="icon-gear icon-2x"></i> <i class="icon-caret-down"></i></button><ul class="dropdown-menu" role="menu"><li><a class="cursor"><i class="icon-time icon-large text-emerald"></i> Ajouter à la file d\'attente</a></li><li><a class="cursor"><i class="icon-bullseye icon-large text-peterriver"></i> Donner en devoirs</a></li></ul></div>');
			$result[] = $exo;
		}
		
		return new JsonResponse($result);
	}
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function recherchePartiesChapitreAction($id_chapitre)
    {
		$chapitre = $this->getDoctrine()
						 ->getManager()
						 ->getRepository('MajordeskAppBundle:Chapitre')
						 ->find($id_chapitre);
		if (empty($chapitre)) {
			throw new \Exception('Chapitre introuvable!');
		}
		$result = array();
		foreach($chapitre->getParties() as $partie) {
			$part = array();
			$part['nom'] = $partie->getNom();
			$part['id'] = $partie->getId();
			$result[] = $part;
		}
		
		return new JsonResponse($result);
	}
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function rechercheExercicesQueryAction($query)
    {
		$user = $this->getUser();
		$id_programme = $user->getProgramme()->getId();
		switch($id_programme) {
			case 1:
				$programmes = array(1,3,5,6);
				break;
			case 2:
				$programmes = array(2,4,5,6);
				break;
			case 3:
				$programmes = array(3,5,6,7);
				break;
			case 4:
				$programmes = array(4,5,6,7);
				break;
			case 5:
				$programmes = array(5,6,7);
				break;
			case 6:
				$programmes = array(6,7);
				break;
			case 7:
				$programmes = array(7);
				break;
			default:
				$programmes = array(1,3,5,6);
		}
		$chapitres = $this->getDoctrine()
						  ->getManager()
						  ->getRepository('MajordeskAppBundle:Chapitre')
						  ->search($query);
		
		$parties = $this->getDoctrine()
						->getManager()
						->getRepository('MajordeskAppBundle:Partie')
						->search($query);
		
		// $tags = $this->getDoctrine()
					 // ->getManager()
					 // ->getRepository('MajordeskAppBundle:Tag')
					 // ->search($query);
		
		$result = array();
		foreach($chapitres as $chapitre) {
			if (in_array($chapitre->getProgramme()->getId(),$programmes)) {
				$datum = array();
				$datum['value'] = $chapitre->getNom();
				// foreach(explode(' ',$chapitre->getNom()) as $keyword) {
					// $datum['tokens'][] = $keyword;
				// }
				$datum['tokens'] = array();
				$datum['Type'] = 'Chapitre';
				$datum['type'] = 'chapitre';
				$datum['id'] = $chapitre->getId();
				$datum['classes'] = 'text-blue';
				$datum['src'] = '../img/programmes/programme-'.$chapitre->getProgramme()->getId().'.jpg';
				$result[] = $datum;
			}
		}
		foreach($parties as $partie) {
			if (in_array($partie->getChapitre()->getProgramme()->getId(),$programmes)) {
				$datum = array();
				$datum['value'] = $partie->getNom();
				// foreach(explode(' ',$partie->getNom()) as $keyword) {
					// $datum['tokens'][] = $keyword;
				// }
				$datum['tokens'] = array();
				$datum['Type'] = 'Partie';
				$datum['type'] = 'partie';
				$datum['id'] = $partie->getId();
				$datum['classes'] = 'text-light-blue';
				$datum['src'] = '../img/programmes/programme-'.$partie->getChapitre()->getProgramme()->getId().'.jpg';
				$result[] = $datum;
			}
		}
		
		return new JsonResponse($result);
	}

	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function addSuperBriqueAction($id_exercice, $numero, $type)
    {
		$exercice_editor = $this->get('majordesk_app.service.exercice_editor');
		
		$exercice_editor->incrementSuperBriques($id_exercice, $numero, 1);
		
		$mod_exercice = $this->getDoctrine()
							 ->getManager()
							 ->getRepository('MajordeskAppBundle:ModExercice')
							 ->find($id_exercice);
		
		$mod_question = new ModQuestion();
		$mod_question->setNumero($numero);
		$mod_question->setType($type);
		$mod_exercice->addModQuestion($mod_question);
		$mod_exercice->updateDateEnregistrementToCurrentDate();
		
		if ($type == 'question') {
			$mod_complement1 = new ModComplement();
			$mod_complement1->setType('indice');
			$mod_complement1->setNumero(1);
			$mod_question->addModComplement($mod_complement1);
			
			$mod_complement2 = new ModComplement();
			$mod_complement2->setType('correction');
			$mod_complement2->setNumero(0);
			$mod_question->addModComplement($mod_complement2);
		}
		
		$em = $this->getDoctrine()->getManager(); 
		$em->persist($mod_exercice);
		$em->flush();
		
		if ($type == 'question') {
			return new JsonResponse(array('id_question' => $mod_question->getId(), 'id_indice' => $mod_complement1->getId(), 'id_correction' => $mod_complement2->getId()));
		} else {
			return new JsonResponse(array('id_question' => $mod_question->getId())); 
		}
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function removeSuperBriqueAction($id_superbrique)
    {
		$exercice_editor = $this->get('majordesk_app.service.exercice_editor');

		$mod_question = $this->getDoctrine()
							 ->getManager()
							 ->getRepository('MajordeskAppBundle:ModQuestion')
							 ->find($id_superbrique);
		
		$exercice_editor->incrementSuperBriques($mod_question->getModExercice()->getId(), $mod_question->getNumero(), -1);
		
		$mod_exercice = $mod_question->getModExercice();
		$mod_exercice->updateDateEnregistrementToCurrentDate();
		
		$em = $this->getDoctrine()->getManager();
		$em->persist($mod_exercice);
		$em->remove($mod_question);
		$em->flush();
		
		return new Response();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function addBriqueToSuperBriqueAction($id_superbrique, $numero, $type, $couche)
    {
		$exercice_editor = $this->get('majordesk_app.service.exercice_editor');
		
		$exercice_editor->incrementBriquesInSuperbrique($id_superbrique, $numero, 1);
		
		$mod_question = $this->getDoctrine()
							 ->getManager()
							 ->getRepository('MajordeskAppBundle:ModQuestion')
							 ->find($id_superbrique);
		
		
		
		$request = $this->getRequest();
		if ( $request->isXmlHttpRequest() ) {	
			$contenu = $request->get('contenu');

			$mod_brique = new ModBrique();
			$mod_brique->setNumero($numero);
			$mod_brique->setType($type);
			$mod_brique->setCouche($couche);
			if (!empty($contenu)) {
				$mod_brique->setContenu($contenu);
			}
		
			$mod_question->addModBrique($mod_brique);
			
			$mod_exercice = $mod_question->getModExercice();
			$mod_exercice->updateDateEnregistrementToCurrentDate();
		
			$em = $this->getDoctrine()->getManager();
			$em->persist($mod_exercice);
			$em->flush();
			
			return new JsonResponse(array('id_brique' => $mod_brique->getId()));
		}
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function removeBriqueFromSuperBriqueAction($id_brique)
    {
		$exercice_editor = $this->get('majordesk_app.service.exercice_editor');

		$mod_brique = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:ModBrique')
						   ->find($id_brique);
		
		$exercice_editor->incrementBriquesInSuperBrique($mod_brique->getModQuestion()->getId(), $mod_brique->getNumero(), -1);
		
		$em = $this->getDoctrine()->getManager();
		$em->remove($mod_brique);
		$em->flush();
		
		return new Response();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function addBriqueToComplementAction($id_complement, $numero, $type, $couche)
    {
		$exercice_editor = $this->get('majordesk_app.service.exercice_editor');
		
		$exercice_editor->incrementBriquesInComplement($id_complement, $numero, 1);
		
		$request = $this->getRequest();	
		$contenu = $request->get('contenu');
		
		$mod_complement = $this->getDoctrine()
							 ->getManager()
							 ->getRepository('MajordeskAppBundle:ModComplement')
							 ->find($id_complement);
		
		$mod_brique = new ModBrique();
		$mod_brique->setNumero($numero);
		$mod_brique->setType($type);
		$mod_brique->setCouche($couche);
		if (!empty($contenu)) {
			$mod_brique->setContenu($contenu);
		}
		$mod_complement->addModBrique($mod_brique);
		
		$mod_exercice = $mod_complement->getModQuestion()->getModExercice();
		$mod_exercice->updateDateEnregistrementToCurrentDate();
		
		$em = $this->getDoctrine()->getManager();
		$em->persist($mod_exercice);
		$em->flush();
		
		return new JsonResponse(array('id_brique' => $mod_brique->getId()));
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function removeBriqueFromComplementAction($id_brique)
    {
		$exercice_editor = $this->get('majordesk_app.service.exercice_editor');

		$mod_brique = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:ModBrique')
						   ->find($id_brique);
		
		$exercice_editor->incrementBriquesInComplement($mod_brique->getModComplement()->getId(), $mod_brique->getNumero(), -1);
		
		$em = $this->getDoctrine()->getManager();
		$em->remove($mod_brique);
		$em->flush();
		
		return new Response();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function updateBriqueAction($id_brique)
    {
		$exercice_editor = $this->get('majordesk_app.service.exercice_editor');

		$mod_brique = $this->getDoctrine()
							 ->getManager()
							 ->getRepository('MajordeskAppBundle:ModBrique')
							 ->find($id_brique);	
		
		$request = $this->getRequest();
		if ( $request->isXmlHttpRequest() ) {	
			$contenu = $request->get('contenu');
			// $contenu = preg_replace('/\s+/', ' ',$contenu);
			$mod_brique->setContenu($contenu);
			$em = $this->getDoctrine()->getManager();
			$em->persist($mod_brique);
			$em->flush();
		}
		
		return new JsonResponse();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function addReponseToSuperBriqueAction($id_superbrique, $id_brique, $numero, $clavier)
    {
		$exercice_editor = $this->get('majordesk_app.service.exercice_editor');
		$mod_brique = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:ModBrique')
						   ->find($id_brique);
		
		$request = $this->getRequest();
		if ( $request->isXmlHttpRequest() ) {	
			$constructor = $request->get('constructor');
			$constructor = json_decode($constructor);
			$generated = array();
			$ids = array();
		
			foreach($constructor as $minimap) {	
				$exercice_editor->incrementReponsesInQuestion($id_superbrique, $numero, 1);
					 
				$mod_reponse = new ModReponse();
				$mod_reponse->setContenu($minimap->contenu);
				$mod_reponse->setType($minimap->type);
				$mod_reponse->setNumero($numero);
				if ($clavier!='') {
					$mod_reponse->setClavier($clavier);
				}
				
				$mod_mapping = new ModMapping();
				$mod_mapping->addModReponse($mod_reponse);
				
				$mod_brique->addModReponse($mod_reponse);
				
				$mod_question = $this->getDoctrine()
									 ->getManager()
									 ->getRepository('MajordeskAppBundle:ModQuestion')
									 ->find($id_superbrique);
				if(empty($mod_question)) {
					throw new \Exception('aucune question associée à $id_superbrique');
				}
				$mod_question->addModMapping($mod_mapping);
				
				$numero++;
				$generated[] = $mod_reponse;
			}

			
			$mod_exercice = $mod_question->getModExercice();
			$mod_exercice->updateDateEnregistrementToCurrentDate();
			
			$em = $this->getDoctrine()->getManager();
			// $em->persist($mod_mapping);
			$em->persist($mod_exercice);
			$em->flush();
			
			foreach($generated as $reponse) {
				$ids[] = $reponse->getId();
			}
			
			return new JsonResponse(array('ids' => $ids));
		}
	}

	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function addReponseToMappingAction($id_reponse)
    {
		$reponse = $this->getDoctrine()
						->getManager()
						->getRepository('MajordeskAppBundle:ModReponse')
						->find($id_reponse);
		if(empty($reponse)) {
			throw new \Exception('aucune réponse associée à $id_reponse');
		}

		$mod_reponse = new ModReponse();
		$mod_reponse->setNumero($reponse->getNumero());
		$mod_reponse->setClavier($reponse->getClavier());
		$mod_reponse->setClavier($reponse->getClavier());
		
		$mod_brique = $reponse->getModBrique();
		$mod_brique->addModReponse($mod_reponse);
		
		$mod_mapping = $reponse->getModMapping();
		$mod_mapping->addModReponse($mod_reponse);
	
		$mod_exercice = $mod_mapping->getModQuestion()->getModExercice();
		$mod_exercice->updateDateEnregistrementToCurrentDate();
	
		$em = $this->getDoctrine()->getManager();
		$em->persist($mod_mapping);
		$em->persist($mod_exercice);
		$em->flush();
		
		return new JsonResponse(array('id_reponse' => $mod_reponse->getId()));
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function mergeReponsesToMappingAction($id_superbrique)
    {
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		if ( $request->isXmlHttpRequest() ) {	
			$dependances = $request->get('dependances');
			$dependances = json_decode($dependances);
			
			$mod_mapping = new ModMapping();
			$mod_question = $this->getDoctrine()
								 ->getManager()
								 ->getRepository('MajordeskAppBundle:ModQuestion')
								 ->find($id_superbrique);
			$mod_mapping->setModQuestion($mod_question);
			
			foreach($dependances as $dependance) {
				$mod_reponse = $this->getDoctrine()
									->getManager()
									->getRepository('MajordeskAppBundle:ModReponse')
									->find($dependance);
				$mapping = $mod_reponse->getModMapping();
				$mapping->removeModReponse($mod_reponse);
				$mod_mapping->addModReponse($mod_reponse);
				
				if (count($mapping->getModReponses())==0) {
					$em->remove($mapping);
				}
			}

			$em->persist($mod_mapping);
			$em->flush();
		}
		
		return new JsonResponse();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function unmergeReponseFromMappingAction($id_superbrique, $id_reponse)
    {
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		if ( $request->isXmlHttpRequest() ) {	
			$mod_reponse = $this->getDoctrine()
							    ->getManager()
							    ->getRepository('MajordeskAppBundle:ModReponse')
							    ->find($id_reponse);
			$mapping = $mod_reponse->getModMapping();
			$mapping->removeModReponse($mod_reponse);
			if (count($mapping->getModReponses())<=1) {
				$mapping->setType(null);
			}
								
			$mod_mapping = new ModMapping();
			$mod_question = $this->getDoctrine()
								 ->getManager()
								 ->getRepository('MajordeskAppBundle:ModQuestion')
								 ->find($id_superbrique);
			$mod_mapping->setModQuestion($mod_question);
			$mod_mapping->addModReponse($mod_reponse);

			$em->persist($mapping);
			$em->persist($mod_mapping);
			$em->flush();
		}
		
		return new JsonResponse();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function updateMappingTypeAction($id_reponse)
    {
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		if ( $request->isXmlHttpRequest() ) {
			$type = $request->get('type');
				
			$mod_reponse = $this->getDoctrine()
							    ->getManager()
							    ->getRepository('MajordeskAppBundle:ModReponse')
							    ->find($id_reponse);
								
			$mod_mapping = $mod_reponse->getModMapping();
			$mod_mapping->setType($type);

			$em->persist($mod_mapping);
			$em->flush();
		}
		
		return new JsonResponse();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function resetMappingTypeAction($id_reponse)
    {	
		$em = $this->getDoctrine()->getManager();

		$mod_reponse = $this->getDoctrine()
							->getManager()
							->getRepository('MajordeskAppBundle:ModReponse')
							->find($id_reponse);
							
		$mod_mapping = $mod_reponse->getModMapping();
		$mod_mapping->setType(null);

		$em->persist($mod_mapping);
		$em->flush();
		
		return new JsonResponse();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function removeBriqueAndReponsesAction($id_brique)
    {
		$exercice_editor = $this->get('majordesk_app.service.exercice_editor');

		$mod_brique = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:ModBrique')
						   ->find($id_brique);
		
		$numero = $mod_brique->getPlusPetitNumeroModReponse();
		$incr = $mod_brique->getDifferentModReponsesCount();
		
		$exercice_editor->incrementReponsesInQuestion($mod_brique->getModQuestion()->getId(), $numero, -1*$incr);
		
		$exercice_editor->incrementBriquesInSuperBrique($mod_brique->getModQuestion()->getId(), $mod_brique->getNumero(), -1);
		
		$em = $this->getDoctrine()->getManager();
		$em->remove($mod_brique); // ceci supprime aussi toutes les réponses associées
		$em->flush();
		
		return new Response();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function removeReponseAction($id_reponse)
    {
		$exercice_editor = $this->get('majordesk_app.service.exercice_editor');

		$mod_reponse = $this->getDoctrine()
						    ->getManager()
						    ->getRepository('MajordeskAppBundle:ModReponse')
						    ->find($id_reponse);
							
		$em = $this->getDoctrine()->getManager();
		
		if (!$mod_reponse->isAlt()) {
			$exercice_editor->incrementReponsesInQuestion($mod_reponse->getModMapping()->getModQuestion()->getId(), $mod_reponse->getNumero(), -1);
		}
		$mod_mapping = $mod_reponse->getModMapping();
		if (count($mod_mapping->getModReponses())==0) {
			$em->remove($mod_mapping);
		}
		
		$em->remove($mod_reponse);
		$em->flush();
		
		return new JsonResponse();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function updateReponseContenuAction($id_reponse)
    {
		$exercice_editor = $this->get('majordesk_app.service.exercice_editor');

		$mod_reponse = $this->getDoctrine()
							 ->getManager()
							 ->getRepository('MajordeskAppBundle:ModReponse')
							 ->find($id_reponse);	
		
		$request = $this->getRequest();
		if ( $request->isXmlHttpRequest() ) {	
			$contenu = $request->get('contenu');
			// $contenu = preg_replace('/\s+/', ' ',$contenu);
			$mod_reponse->setContenu($contenu);
			$em = $this->getDoctrine()->getManager();
			$em->persist($mod_reponse);
			$em->flush();
		}
		
		return new JsonResponse();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function updateReponseTypeAction($id_reponse)
    {
		$exercice_editor = $this->get('majordesk_app.service.exercice_editor');

		$mod_reponse = $this->getDoctrine()
							->getManager()
							->getRepository('MajordeskAppBundle:ModReponse')
							->find($id_reponse);	
		
		$request = $this->getRequest();
		if ( $request->isXmlHttpRequest() ) {	
			$type = $request->get('type');
			$mod_reponse->setType($type);
			$em = $this->getDoctrine()->getManager();
			$em->persist($mod_reponse);
			$em->flush();
		}
		
		return new JsonResponse();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function updateReponseClavierAction()
    {
		$exercice_editor = $this->get('majordesk_app.service.exercice_editor');
		$em = $this->getDoctrine()->getManager();				
		$request = $this->getRequest();
		$claviers = array();
		if ( $request->isXmlHttpRequest() ) {	
			$ids_reponse = $request->get('ids_reponse');
			$ids_reponse = json_decode($ids_reponse);
			
			foreach($ids_reponse as $rep) {
				$mod_reponse = $this->getDoctrine()
									->getManager()
									->getRepository('MajordeskAppBundle:ModReponse')
									->find($rep->id_reponse);
				$mod_reponse->setClavier(intval($mod_reponse->getClavier())+$rep->incr);
				$claviers[$rep->id_reponse] = $mod_reponse->getClavier(); // nouveau clavier
				
				$em->persist($mod_reponse);
			}
			$em->flush();
		}

		return new JsonResponse(array('claviers'=>$claviers));
	}

	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function envoiFeedbackAction($id_exercice)
    {
		$feedback = new Feedback();
		$request = $this->getRequest();
		if ( $request->isXmlHttpRequest() ) {	
			$type = $request->get('type');
			$commentaire = $request->get('commentaire');
			$feedback->setType($type);
			$feedback->setCommentaire($commentaire);
			
			$mod_exercice = $this->getDoctrine()
								 ->getManager()
								 ->getRepository('MajordeskAppBundle:ModExercice')
								 ->find($id_exercice);
			
			$feedback->setModExercice($mod_exercice);
			if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
				$user = $this->getUser();
				$mail = $user->getMail();
				$feedback->setMail($mail);
			}
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($feedback);
			$em->flush();
		}
		
		return new Response();
	}
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function updateFeedbackAction($id_feedback)
    {
		$feedback = $this->getDoctrine()
						 ->getManager()
						 ->getRepository('MajordeskAppBundle:Feedback')
						 ->find($id_feedback);
	
		$request = $this->getRequest();
		if ( $request->isXmlHttpRequest() ) {	
			$statut = $request->get('statut');
			$feedback->setStatut($statut);
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($feedback);
			$em->flush();
		}
		
		return new Response();
	}
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function updateRegleAction($id_ticket)
    {
		$ticket = $this->getDoctrine()
						 ->getManager()
						 ->getRepository('MajordeskAppBundle:Ticket')
						 ->find($id_ticket);
	
		$request = $this->getRequest();
		if ( $request->isXmlHttpRequest() ) {	
			$regle = $request->get('regle');
			$ticket->setRegle($regle);
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($ticket);
			$em->flush();
		}
		
		return new Response();
	}

	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function toggleFlagEleveAction($id)
    {
		$repository = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Eleve');
		$eleve = $repository->find($id);
		
		/* toogle --> faire un service */
		if ($eleve->getFlag())
		{
			$eleve->setFlag(false);
		}
		else if ($eleve->getFlag() === false)
		{
			$eleve->setFlag(true);
		}
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$em = $this->getDoctrine()->getManager();
			$em->persist($eleve);
			$em->flush();
		}
		return new Response();
	}

	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function toggleActifEleveAction($id)
    {
		$repository = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Eleve');
		$eleve = $repository->find($id);
		
		/* toogle --> faire un service */
		if ($eleve->getActif())
		{
			$eleve->setActif(false);
		}
		else if ($eleve->getActif() === false)
		{
			$eleve->setActif(true);
		}
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$em = $this->getDoctrine()->getManager();
			$em->persist($eleve);
			$em->flush();
		}
		return new Response();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function toggleFlagFamilleAction($id)
    {
		$repository = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Famille');
		$famille = $repository->find($id);
		
		/* toogle --> faire un service */
		if ($famille->getFlag())
		{
			$famille->setFlag(false);
		}
		else if ($famille->getFlag() === false)
		{
			$famille->setFlag(true);
		}
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$em = $this->getDoctrine()->getManager();
			$em->persist($famille);
			$em->flush();
		}
		return new Response();
	}

	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function toggleActifFamilleAction($id)
    {
		$repository = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Famille');
		$famille = $repository->find($id);
		
		$repository = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Client');
		$parents = $repository->findAll($famille->getId());
		
		/* toogle --> faire un service */
		if ($famille->getActif())
		{
			$famille->setActif(false);
			foreach($parents as $parent)
			{
				$parent->setActif(false);
			}
		}
		else if ($famille->getActif() === false)
		{
			$famille->setActif(true);
			foreach($parents as $parent)
			{
				$parent->setActif(true);
			}
		}
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$em = $this->getDoctrine()->getManager();
			$em->persist($famille);
			$em->flush();
		}
		return new Response();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function toggleActifParentAction($id)
    {
		$repository = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Client');
		$parent = $repository->find($id);
		
		/* toogle --> faire un service */
		if ($parent->getActif())
		{
			$parent->setActif(false);
		}
		else if ($parent->getActif() === false)
		{
			$parent->setActif(true);
		}
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$em = $this->getDoctrine()->getManager();
			$em->persist($parent);
			$em->flush();
		}
		return new Response();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function toggleFlagProfesseurAction($id)
    {
		$repository = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Professeur');
		$professeur = $repository->find($id);
		
		/* toogle --> faire un service */
		if ($professeur->getFlag())
		{
			$professeur->setFlag(false);
		}
		else if ($professeur->getFlag() === false)
		{
			$professeur->setFlag(true);
		}
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$em = $this->getDoctrine()->getManager();
			$em->persist($professeur);
			$em->flush();
		}
		return new Response();
	}

	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function toggleActifProfesseurAction($id)
    {
		$repository = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Professeur');
		$professeur = $repository->find($id);
		
		/* toogle --> faire un service */
		if ($professeur->getActif())
		{
			$professeur->setActif(false);
		}
		else if ($professeur->getActif() === false)
		{
			$professeur->setActif(true);
		}
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$em = $this->getDoctrine()->getManager();
			$em->persist($professeur);
			$em->flush();
		}
		return new Response();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function deleteExerciceAction($id)
    {
		$exercice = $this->getDoctrine()
						 ->getManager()
						 ->getRepository('MajordeskAppBundle:ModExercice')
						 ->find($id);
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$em = $this->getDoctrine()->getManager();
			$em->remove($exercice);
			$em->flush();
		}
		return new Response();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function deleteFamilleAction($id_famille)
    {
		$famille = $this->getDoctrine()
						->getManager()
						->getRepository('MajordeskAppBundle:Famille')
						->find($id_famille);
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$em = $this->getDoctrine()->getManager();
			$em->remove($famille);
			$em->flush();
		}
		return new Response();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function deleteEleveAction($id_eleve)
    {
		$eleve = $this->getDoctrine()
					  ->getManager()
				      ->getRepository('MajordeskAppBundle:Eleve')
					  ->find($id_eleve);
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$em = $this->getDoctrine()->getManager();
			$em->remove($eleve);
			$em->flush();
		}
		return new Response();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function deleteProfesseurAction($id_professeur)
    {
		$professeur = $this->getDoctrine()
					  ->getManager()
				      ->getRepository('MajordeskAppBundle:Professeur')
					  ->find($id_professeur);
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$em = $this->getDoctrine()->getManager();
			$em->remove($professeur);
			$em->flush();
		}
		return new Response();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function uploadExerciceAction($id)
    {
		$en_ligne = $this->container->getParameter('statut_en_ligne');
	
		$exercice = $this->getDoctrine()
						 ->getManager()
						 ->getRepository('MajordeskAppBundle:ModExercice')
						 ->find($id);
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$exercice->setStatut($en_ligne);
		
			$em = $this->getDoctrine()->getManager();
			$em->persist($exercice);
			$em->flush();
		}
		return new Response();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function downloadExerciceAction($id)
    {
		$en_edition = $this->container->getParameter('statut_en_edition');
	
		$exercice = $this->getDoctrine()
						 ->getManager()
						 ->getRepository('MajordeskAppBundle:ModExercice')
						 ->find($id);
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$exercice->setStatut($en_edition);
		
			$em = $this->getDoctrine()->getManager();
			$em->persist($exercice);
			$em->flush();
		}
		return new JsonResponse(array('id' => $exercice->getId(), 'matiere' => $exercice->getMatiere()->getNom(), 'programme' => $exercice->getProgramme()->getNom(), 'chapitre' => $exercice->getChapitre()->getNom(), 'partie' => $exercice->getPartie()->getNom(), 'niveau' => $exercice->getNiveau(), 'date_enregistrement' => $exercice->getDateEnregistrement()));
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function getAllTagsAction()
    {
		$request = $this->getRequest();
		if ( $request->isXmlHttpRequest() ) {		
		
			$tags = $this->getDoctrine()
						 ->getManager()
						 ->getRepository('MajordeskAppBundle:Tag')
						 ->findAll();
			$source = array();
			foreach($tags as $tag) {
				$source[] = $tag->getNom();
			}
		
			return new JsonResponse(array('source' => $source));
		}
		return new JsonResponse();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function assignTagAction($nom_tag, $id_mod_reponse)
    {
		$request = $this->getRequest();
		if ( $request->isXmlHttpRequest() ) {		
		
			$tag = $this->getDoctrine()
						->getManager()
						->getRepository('MajordeskAppBundle:Tag')
						->findOneByNom($nom_tag);
			
			$mod_reponse = $this->getDoctrine()
							    ->getManager()
								->getRepository('MajordeskAppBundle:ModReponse')
								->find($id_mod_reponse);
			
			if ( empty($tag) ) {
				throw new \Exception('Ce tag n\'existe pas !');
			}
			if ( empty($mod_reponse) ) {
				throw new \Exception('Ce modèle de réponse n\'existe pas !');
			}
			
			$mod_reponse->addTag($tag);
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($mod_reponse);
			$em->flush();
		}
		return new Response();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function unassignTagAction($nom_tag, $id_mod_reponse)
    {
		$request = $this->getRequest();
		if ( $request->isXmlHttpRequest() ) {		
		
			$tag = $this->getDoctrine()
						->getManager()
						->getRepository('MajordeskAppBundle:Tag')
						->findOneByNom($nom_tag);
			
			$mod_reponse = $this->getDoctrine()
							    ->getManager()
								->getRepository('MajordeskAppBundle:ModReponse')
								->find($id_mod_reponse);
			
			if ( empty($tag) ) {
				throw new \Exception('Ce tag n\'existe pas !');
			}
			if ( empty($mod_reponse) ) {
				throw new \Exception('Ce modèle de réponse n\'existe pas !');
			}
			
			$mod_reponse->removeTag($tag);
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($mod_reponse);
			$em->flush();
		}
		return new Response();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function populateChapitresAction($id_matiere, $id_programme)
    {
		$request = $this->getRequest();
		if ( $request->isXmlHttpRequest() ) {		
		
			$chapitres = $this->getDoctrine()
						      ->getManager()
						      ->getRepository('MajordeskAppBundle:Chapitre')
						      ->getChapitresByMatiereAndProgramme($id_matiere, $id_programme);
			$html = '<option  disabled="disabled" selected="selected">Choisir un chapitre</option>';
			foreach($chapitres as $chapitre) {
				$html = $html.sprintf("<option value=\"%d\">%s</option>",$chapitre->getId(), $chapitre->getNom());
			}
		
			return new JsonResponse(array('html' => $html));
		}
		return new JsonResponse();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function populatePartiesAction($id_chapitre)
    {
		$request = $this->getRequest();
		if ( $request->isXmlHttpRequest() ) {		
		
			$parties = $this->getDoctrine()
						    ->getManager()
						    ->getRepository('MajordeskAppBundle:Partie')
						    ->getPartiesByChapitre($id_chapitre);
			
			$html = '<option  disabled="disabled" selected="selected">Choisir une partie</option>';
			foreach($parties as $partie) {
				$html = $html.sprintf("<option value=\"%d\">%s</option>",$partie->getId(), $partie->getNom());
			}
		
			return new JsonResponse(array('html' => $html));
		}
		return new JsonResponse();
	}
	
	/**
	 * @Secure(roles="ROLE_PARENTS")
	 */
    public function populateMatieresAction($id_eleve)
    {
		$request = $this->getRequest();
		if ( $request->isXmlHttpRequest() ) {		
		
			$eleve = $this->getDoctrine()
						  ->getManager()
						  ->getRepository('MajordeskAppBundle:Eleve')
						  ->find($id_eleve);
			$matieres = $eleve->getMatieres();
			
			$html = '<option  disabled="disabled" selected="selected">Choisir une matière</option>';
			foreach($matieres as $matiere) {
				$html = $html.sprintf("<option value=\"%d\">%s</option>",$matiere->getId(), $matiere->getNom());
			}
		
			return new JsonResponse(array('html' => $html));
		}
		return new JsonResponse();
	}
	
	/**
	 * @Secure(roles="ROLE_PARENTS")
	 */
    public function populateProfesseursAction($id_eleve)
    {
		$request = $this->getRequest();
		if ( $request->isXmlHttpRequest() ) {		
		
			$eleve = $this->getDoctrine()
						  ->getManager()
						  ->getRepository('MajordeskAppBundle:Eleve')
						  ->find($id_eleve);
			$professeurs = $eleve->getProfesseurs();
			
			$html = '<option  disabled="disabled" selected="selected">Choisir un professeur</option>';
			foreach($professeurs as $professeur) {
				$html = $html.sprintf("<option value=\"%d\">%s</option>",$professeur->getId(), $professeur->getUsername());
			}
		
			return new JsonResponse(array('html' => $html));
		}
		return new JsonResponse();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function filterExercicesAction($id_partie)
    {
		$en_ligne = $this->container->getParameter('statut_en_ligne');
		
		$request = $this->getRequest();
		if ( $request->isXmlHttpRequest() ) {	
			$exercices = $this->getDoctrine()
						      ->getManager()
						      ->getRepository('MajordeskAppBundle:ModExercice')
						      ->findBy(array('partie' => $id_partie, 'statut' => $en_ligne));
			
			$exercicesInfo = array();
			foreach($exercices as $exercice) {
				$exoInfo = array('id' => $exercice->getId(), 'matiere' => $exercice->getMatiere()->getNom(), 'programme' => $exercice->getProgramme()->getNom(), 'chapitre' => $exercice->getChapitre()->getNom(), 'partie' => $exercice->getPartie()->getNom(), 'niveau' => $exercice->getNiveau(), 'date_enregistrement' => $exercice->getDateEnregistrement());
				$exercicesInfo[] = $exoInfo;
			}
			
			return new JsonResponse($exercicesInfo);
		}
		return new JsonResponse();
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function reinitialiserExerciceAction($id_exercice)
    {
		$non_commence = $this->container->getParameter('statut_non_commence');
	
		$request = $this->getRequest();
		if ( $request->isXmlHttpRequest() ) {
			
			$em = $this->getDoctrine()->getManager();
			
			$exercice = $this->getDoctrine()
						     ->getManager()
						     ->getRepository('MajordeskAppBundle:Exercice')
						     ->find($id_exercice);
			
			$questions = $exercice->getQuestions();
			foreach($questions as $question) {
				$question->setNombreEssais(0);
				$question->setCommentaire('');
				$question->setCouche(1);
				$question->setStatut($non_commence);
				
				$reponses = $question->getReponses(); // A supprimer au prochain clean up
				foreach($reponses as $reponse) {
					$question->removeReponse($reponse);
					$em->remove($reponse);
				}
				$reps = $question->getReps();
				foreach($reps as $rep) {
					$question->removeRep($rep);
					$em->remove($rep);
				}
			}
			
			$exercice->setTemps(new \Datetime("00:00:00"));
			$exercice->setStatut($non_commence);
			$exercice->setQueue(1);
			
			$em->persist($exercice);
			$em->flush();
		}
		return new Response();
	}
	
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function displayChapitreStatsAction($id_eleve, $id_chapitre)
    {
		$statut_resolu = $this->container->getParameter('statut_resolu');
	
		$exercices = $this->getDoctrine()
						  ->getManager()
						  ->getRepository('MajordeskAppBundle:Exercice')
						  ->getExercicesByChapitre($id_eleve, $id_chapitre);

		foreach( $exercices as $exercice ) {
			if (!isset($derniere_date)) {
				$derniere_date = $exercice->getDerniereDate();
			}
			else {
				$derniere_date = max( $derniere_date, $exercice->getDerniereDate() );
			}
		}

		$temps_total = new \DateTime("00:00:00");;
		$heures = 0;
		$minutes = 0;
		$secondes = 0;
        foreach( $exercices as $exercice ) {
			$temps = $exercice->getTemps();		
			$heures += intval($temps->format('G'));
			$minutes += intval($temps->format('i'));
			$secondes += intval($temps->format('s'));
		}	
		$temps_total->setTime($heures, $minutes, $secondes);	
		
		$en_autonomie = 0;
		$avec_professeur = 0;
		foreach( $exercices as $exercice ) {
			if ( $exercice->getStatut() == $statut_resolu ) {
				if ( $exercice->getAutonomie() ) {
					$en_autonomie++;
				}
				else {
					$avec_professeur++;
				}
			}
		}
		$nombre_exercices = count($exercices);
		if ( $nombre_exercices != 0) {
			$pourcent_en_autonomie = round($en_autonomie/$nombre_exercices, 1);
			$pourcent_avec_professeur = round($avec_professeur/$nombre_exercices, 1);
		}
		else {
			$pourcent_en_autonomie = 0;
			$pourcent_avec_professeur = 0;
		}
		

		if (isset($derniere_date)) {
			return new JsonResponse(array('pourcent_en_autonomie' => $pourcent_en_autonomie, 'pourcent_avec_professeur' => $pourcent_avec_professeur, 'temps_total' => $temps_total->format('G\h i\m\i\n s\s\e\c'), 'derniere_date' => $derniere_date->format('d/m/Y')));
		}
		else {
			return new JsonResponse(array('pourcent_en_autonomie' => $pourcent_en_autonomie, 'pourcent_avec_professeur' => $pourcent_avec_professeur));
		}
	}
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function displayPartieStatsAction($id_eleve, $id_partie)
    {
		$statut_resolu = $this->container->getParameter('statut_resolu');
	
		$exercices = $this->getDoctrine()
						  ->getManager()
						  ->getRepository('MajordeskAppBundle:Exercice')
						  ->getExercicesByPartieByStatut($id_eleve, $id_partie, $statut_resolu);
		
		$en_autonomie = 0;
		$avec_professeur = 0;
		foreach( $exercices as $exercice ) {
			if ( $exercice->getAutonomie() ) {
				$en_autonomie++;
			}
			else {
				$avec_professeur++;
			}
		}
		$nombre_exercices = count($exercices);
		if ( $nombre_exercices != 0) {
			$pourcent_en_autonomie = round($en_autonomie/$nombre_exercices, 1);
			$pourcent_avec_professeur = round($avec_professeur/$nombre_exercices, 1);
		}
		else {
			$pourcent_en_autonomie = 0;
			$pourcent_avec_professeur = 0;
		}

		return new JsonResponse(array('pourcent_en_autonomie' => $pourcent_en_autonomie, 'pourcent_avec_professeur' => $pourcent_avec_professeur));
	}
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function displayEleveStatsAction($id_eleve, $period)
    {
		$statut_resolu = $this->container->getParameter('statut_resolu');
		
		// WEEK
		if ($period == 1) {
			$first_day_this_week = new \Datetime("monday this week", new \DateTimeZone('Europe/Paris'));
			$first_day_next_week = new \Datetime("monday next week", new \DateTimeZone('Europe/Paris'));
			
			$ex_week = $this->getDoctrine()
							->getManager()
							->getRepository('MajordeskAppBundle:Exercice')
							->getExercicesByEleveBetween($id_eleve, $first_day_this_week, $first_day_next_week);
			
			$ex_week_array = array();
			$ex_week_array[1] = 0; 
			$ex_week_array[2] = 0; 
			$ex_week_array[3] = 0; 
			$ex_week_array[4] = 0; 
			$ex_week_array[5] = 0; 
			$ex_week_array[6] = 0; 
			$ex_week_array[7] = 0; 
			$en_autonomie_week = 0;
			$avec_professeur_week = 0;
			$temps_week = new \DateTime("00:00:00");;
			$heures = 0;
			$minutes = 0;
			$secondes = 0;
			$nombre_ex_week_done = 0;
			$nb_questions = 0;
			$nb_total_essais_questions = 0;
			foreach( $ex_week as $exercice ) {
				$day = date('N', strtotime($exercice->getDateQueue()->format('Y-m-d')));
				$ex_week_array[$day] = $ex_week_array[$day] + 1;
				$temps = $exercice->getTemps();		
				$heures += intval($temps->format('G'));
				$minutes += intval($temps->format('i'));
				$secondes += intval($temps->format('s'));
				if ( $exercice->getAutonomie() ) {
					$en_autonomie_week++;
				}
				else {
					$avec_professeur_week++;
				}
				if ( $exercice->getStatut() == $statut_resolu ) {
					$nombre_ex_week_done++;
				}
				foreach($exercice->getQuestions() as $question) {
					if ($question->getStatut() != 0) {
						$nb_questions++;
						$nb_total_essais_questions += $question->getNombreEssais();
					}
				}
			}
			if ($nb_questions != 0) {
				$nb_moyen_essais = number_format($nb_total_essais_questions / $nb_questions, 1, ',', ' ');
			}
			else {
				$nb_moyen_essais = ' - ';
			}
			$temps_week->setTime($heures, $minutes, $secondes);	
			$nombre_ex_week = count($ex_week);
			if ( $nombre_ex_week != 0) {
				$pourcent_en_autonomie_week = round($en_autonomie_week/$nombre_ex_week, 1);
				$pourcent_avec_professeur_week = round($avec_professeur_week/$nombre_ex_week, 1);
			}
			else {
				$pourcent_en_autonomie_week = 0;
				$pourcent_avec_professeur_week = 0;
			}
			
			return new JsonResponse(array(
				'pourcent_en_autonomie_week' => $pourcent_en_autonomie_week, 
				'pourcent_avec_professeur_week' => $pourcent_avec_professeur_week,
				'ex_week_array' => $ex_week_array,
				'temps_week' => $temps_week->format('G\h i\m\i\n s\s\e\c'),
				'nombre_ex_week' => $nombre_ex_week,
				'nombre_ex_week_done' => $nombre_ex_week_done,
				'nb_moyen_essais' => $nb_moyen_essais,
			));
		}
		// MONTH
		else if ($period == 2) {
			$first_day_this_month = new \Datetime("first day of this month", new \DateTimeZone('Europe/Paris'));
			$first_day_next_month = new \Datetime("first day of next month", new \DateTimeZone('Europe/Paris'));
			
			$ex_month = $this->getDoctrine()
							 ->getManager()
							 ->getRepository('MajordeskAppBundle:Exercice')
							 ->getExercicesByEleveBetween($id_eleve, $first_day_this_month, $first_day_next_month);
			
			$nbDaysThisMonth = date('t');
			
			$ex_month_array = array();
			for ($i=1;$i<=$nbDaysThisMonth;$i++) {
				$ex_month_array[$i] = 0; 	
			}
			$en_autonomie_month = 0;
			$avec_professeur_month = 0;
			$temps_month = new \DateTime("00:00:00");;
			$heures = 0;
			$minutes = 0;
			$secondes = 0;
			$nombre_ex_month_done = 0;
			$nb_questions = 0;
			$nb_total_essais_questions = 0;
			foreach( $ex_month as $exercice ) {
				$day = date('j', strtotime($exercice->getDateQueue()->format('Y-m-d')));
				$ex_month_array[$day] = $ex_month_array[$day] + 1;
				$temps = $exercice->getTemps();		
				$heures += intval($temps->format('G'));
				$minutes += intval($temps->format('i'));
				$secondes += intval($temps->format('s'));
				if ( $exercice->getAutonomie() ) {
					$en_autonomie_month++;
				}
				else {
					$avec_professeur_month++;
				}
				if ( $exercice->getStatut() == $statut_resolu ) {
					$nombre_ex_month_done++;
				}
				foreach($exercice->getQuestions() as $question) {
					if ($question->getStatut() != 0) {
						$nb_questions++;
						$nb_total_essais_questions += $question->getNombreEssais();
					}
				}
			}
			if ($nb_questions != 0) {
				$nb_moyen_essais = number_format($nb_total_essais_questions / $nb_questions, 1, ',', ' ');
			}
			else {
				$nb_moyen_essais = ' - ';
			}
			$temps_month->setTime($heures, $minutes, $secondes);	
			$nombre_ex_month = count($ex_month);
			if ( $nombre_ex_month != 0) {
				$pourcent_en_autonomie_month = round($en_autonomie_month/$nombre_ex_month, 1);
				$pourcent_avec_professeur_month = round($avec_professeur_month/$nombre_ex_month, 1);
			}
			else {
				$pourcent_en_autonomie_month = 0;
				$pourcent_avec_professeur_month = 0;
			}
			
			return new JsonResponse(array(
				'pourcent_en_autonomie_month' => $pourcent_en_autonomie_month, 
				'pourcent_avec_professeur_month' => $pourcent_avec_professeur_month,
				'ex_month_array' => $ex_month_array,
				'temps_month' => $temps_month->format('G\h i\m\i\n s\s\e\c'),
				'nombre_ex_month' => $nombre_ex_month,
				'nombre_ex_month_done' => $nombre_ex_month_done,
				'nb_moyen_essais' => $nb_moyen_essais,
			));
		}
	}
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function validateReponsesAction($id_question, $isLastCouche)
    {
		$session = $this->get('session');
		$etape_cours = $session->get('etape_cours');
		$reponse_validator = $this->get('majordesk_app.service.reponse_validator');
		$question = $this->getDoctrine()
						 ->getManager()
					     ->getRepository('MajordeskAppBundle:Question')
					     ->find($id_question);
		
		$request = $this->getRequest();
		if ( $request->isXmlHttpRequest() ) {	
			$reponses = $request->get('reponses');
			$reponses_ = json_decode($reponses);
			$temps = $request->get('temps');
			$temps_ = new \DateTime($temps);
			$questionAnalysis = $reponse_validator->evaluateAndPersistReponses($question, $reponses_, $temps_, $isLastCouche);
			
			// //MODE DEBUG ON
			// $em = $this->getDoctrine()->getManager();
			
			// $mod_question = $question->getModQuestion();
			// $mod_reponses = array();
			// foreach($mod_question->getModMappings() as $mod_mapping) {
				// foreach($mod_mapping->getModReponses() as $mod_reponse) {
					// $mod_reponses[] = $mod_reponse->getContenu();
				// }
			// }		
			// return new JsonResponse(array('reponses_' => $reponses_, 'mod_reponses' => $mod_reponses, 'valeur_question' => $questionAnalysis[0], 'commentaire' => $questionAnalysis[1], 'exercice_termine' => $questionAnalysis[2], 'CR' => $questionAnalysis[3]));
			
			// MODE DEBUG OFF
			return new JsonResponse(array('etape_cours' => $etape_cours, 'valeur_question' => $questionAnalysis[0], 'commentaire' => $questionAnalysis[1], 'exercice_termine' => $questionAnalysis[2], 'CR' => $questionAnalysis[3]));
		}
	}
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function updateTempsExerciceAction($id_exercice)
    {
		if ($this->get('security.context')->isGranted('ROLE_ELEVE') && !$this->get('security.context')->isGranted('ROLE_PROF') && !$this->get('security.context')->isGranted('ROLE_PARENTS') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
			$exercice = $this->getDoctrine()
							 ->getManager()
							 ->getRepository('MajordeskAppBundle:Exercice')
							 ->find($id_exercice);
			
			$request = $this->getRequest();
			if ( $request->isXmlHttpRequest() ) {		
				$temps = $request->get('temps');
				$temps_ = new \DateTime($temps);
				$exercice->setTemps($temps_);
			
				$em = $this->getDoctrine()->getManager();
				$em->persist($exercice);
				$em->flush();
				
				return new Response();
			}
		}
		return new Response();
	}
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function checkProfesseurPasswordAction($id_professeur, $password, $id_matiere)
    {
		$professeur = $this->getDoctrine()
						   ->getManager()
					       ->getRepository('MajordeskAppBundle:Professeur')
					       ->find($id_professeur);
		
		$request = $this->getRequest();
		if ( $request->isXmlHttpRequest() ) {	
			$factory = $this->get('security.encoder_factory');
			$encoder = $factory->getEncoder($professeur);			
			$encoded_pass = $encoder->encodePassword($password, $professeur->getSalt());
			
			if ($encoded_pass == $professeur->getPassword()) {
				$session = $this->get('session');
				$session->set('type_cours', 1);
				$session->set('matiere_cours', $id_matiere);
				$session->set('professeur_cours', $id_professeur);
				$session->set('debut_cours', time());
				$session->set('etape_cours', 1);
			}
			return new JsonResponse($encoded_pass == $professeur->getPassword());
		}
		return new Response();
	}
	
	public function calendrierEventsAction()
    {
		$user = $this->getUser();
		$mois = array(
			"1" => "Janvier",
			"2" => "Février",
			"3" => "Mars",
			"4" => "Avril",
			"5" => "Mai",
			"6" => "Juin",
			"7" => "Juillet",
			"8" => "Août",
			"9" => "Septembre",
			"10" => "Octobre",
			"11" => "Novembre",
			"12" => "Décembre",
		);
		$jour = array(
			"1" => "Lundi",
			"2" => "Mardi",
			"3" => "Mercredi",
			"4" => "Jeudi",
			"5" => "Vendredi",
			"6" => "Samedi",
			"7" => "Dimanche",
		);
		$result = array();
		if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
			$cal_events = $this->getDoctrine()
							   ->getManager()
							   ->getRepository('MajordeskAppBundle:CalEvent')
							   ->getAllCalEvents();
		
			foreach( $cal_events as $cal_event) {
				$matiere = $cal_event->getMatiere()->getNom();
				switch($matiere) {
					case 'Mathématiques':
						$class_matiere = 'event-info';
						$label_matiere = 'label-info';
						break;
					case 'Physique-Chimie':
						$class_matiere = 'event-success';
						$label_matiere = 'label-success';
						break;
					case 'Biologie':
						$class_matiere = 'event-warning';
						$label_matiere = 'label-warning';
						break;
					case 'Anglais':
						$class_matiere = 'event-important';
						$label_matiere = 'label-important';
						break;
					case 'Français':
						$class_matiere = 'event-inverse';
						$label_matiere = 'label-default';
						break;
					case 'Histoire-Géographie':
						$class_matiere = '';
						$label_matiere = '';
						break;
					default:
						$class_matiere = '';
						$label_matiere = '';
						break;
				}
				$start = strtotime($cal_event->getDateCours()->format('Y-m-d').' '.$cal_event->getHeureDebut().':00').'000';
				$end = strtotime($cal_event->getDateCours()->format('Y-m-d').' '.$cal_event->getHeureFin().':00').'000';
				
				$fr_date = $jour[$cal_event->getDateCours()->format('N')].' '.$cal_event->getDateCours()->format('j').' '.$mois[$cal_event->getDateCours()->format('n')];
				
				$result[] = array("id"=>$cal_event->getId(), "eleve"=>'0.5', "reservation"=>$cal_event->getReservation(), "professeur"=>0, "title"=>'Cours de '.$cal_event->getEleve()->getUsername().' '.$cal_event->getEleve()->getNom().' avec '.$cal_event->getProfesseur()->getUsername().' '.$cal_event->getProfesseur()->getNom().' ('.$cal_event->getHeureDebut().'-'.$cal_event->getHeureFin().')', "url"=>"#", "class"=>$class_matiere, "date"=>$fr_date, "heureDebut"=>$cal_event->getHeureDebut(), "heureFin"=>$cal_event->getHeureFin(), "label"=>$label_matiere, "matiere"=>$matiere, "start"=>$start, "end"=>$end);
			}	
		}
		else if ($this->get('security.context')->isGranted('ROLE_ELEVE') && !$this->get('security.context')->isGranted('ROLE_PROF') && !$this->get('security.context')->isGranted('ROLE_PARENTS')) {
			$cal_events = $this->getDoctrine()
							   ->getManager()
							   ->getRepository('MajordeskAppBundle:CalEvent')
							   ->getAllEleveCalEvents($user->getId());
		
			foreach( $cal_events as $cal_event) {
				$matiere = $cal_event->getMatiere()->getNom();
				switch($matiere) {
					case 'Mathématiques':
						$class_matiere = 'event-info';
						$label_matiere = 'label-info';
						break;
					case 'Physique-Chimie':
						$class_matiere = 'event-success';
						$label_matiere = 'label-success';
						break;
					case 'Biologie':
						$class_matiere = 'event-warning';
						$label_matiere = 'label-warning';
						break;
					case 'Anglais':
						$class_matiere = 'event-important';
						$label_matiere = 'label-important';
						break;
					case 'Français':
						$class_matiere = 'event-inverse';
						$label_matiere = 'label-default';
						break;
					case 'Histoire-Géographie':
						$class_matiere = '';
						$label_matiere = '';
						break;
					default:
						$class_matiere = '';
						$label_matiere = '';
						break;
				}
				date_default_timezone_set("Europe/Paris");
				$start = strtotime($cal_event->getDateCours()->format('Y-m-d').' '.$cal_event->getHeureDebut().':00')*1000;
				$end = strtotime($cal_event->getDateCours()->format('Y-m-d').' '.$cal_event->getHeureFin().':00')*1000;
				
				$fr_date = $jour[$cal_event->getDateCours()->format('N')].' '.$cal_event->getDateCours()->format('j').' '.$mois[$cal_event->getDateCours()->format('n')];
				
				$result[] = array("id"=>$cal_event->getId(), "eleve"=>0, "reservation"=>$cal_event->getReservation(), "professeur"=>$cal_event->getProfesseur()->getId(), "title"=>$cal_event->getTitre(), "url"=>"#", "class"=>$class_matiere, "date"=>$fr_date, "heureDebut"=>$cal_event->getHeureDebut(), "heureFin"=>$cal_event->getHeureFin(), "label"=>$label_matiere, "matiere"=>$matiere, "start"=>$start, "end"=>$end);
			}
			
			$professeurs = $user->getProfesseurs();
			$i = 0;
			foreach($professeurs as $professeur) {
				$i++;
				$cal_events = $this->getDoctrine()
								   ->getManager()
								   ->getRepository('MajordeskAppBundle:CalEvent')
								   ->getAllProfesseurCalEvents($professeur->getId());

				foreach( $cal_events as $cal_event) {
					if ($cal_event->getEleve()->getId() != $user->getId()) {
						$matiere = $cal_event->getMatiere()->getNom();
						$class_matiere = 'event-prof-'.$i;
						$label_matiere = 'label-prof-'.$i;

						$start = strtotime($cal_event->getDateCours()->format('Y-m-d').' '.$cal_event->getHeureDebut().':00').'000';
						$end = strtotime($cal_event->getDateCours()->format('Y-m-d').' '.$cal_event->getHeureFin().':00').'000';
						
						$fr_date = $jour[$cal_event->getDateCours()->format('N')].' '.$cal_event->getDateCours()->format('j').' '.$mois[$cal_event->getDateCours()->format('n')];
						
						$result[] = array("id"=>$cal_event->getId(), "eleve"=>$cal_event->getEleve()->getId(), "professeur"=>$professeur->getId(), "title"=>$professeur->getUsername().' est pris par un cours de '.$cal_event->getHeureDebut().' à '.$cal_event->getHeureFin().'.', "url"=>"#", "class"=>$class_matiere, "date"=>$fr_date, "heureDebut"=>$cal_event->getHeureDebut(), "heureFin"=>$cal_event->getHeureFin(), "label"=>$label_matiere, "matiere"=>$matiere, "start"=>$start, "end"=>$end);
					}
				}
			}
		}
		else if ($this->get('security.context')->isGranted('ROLE_PARENTS')) {
			$famille = $user->getFamille();
			$cal_events = $this->getDoctrine()
							   ->getManager()
							   ->getRepository('MajordeskAppBundle:CalEvent')
							   ->getAllFamilleCalEvents($famille->getId());
		
			foreach( $cal_events as $cal_event) {
				$matiere = $cal_event->getMatiere()->getNom();
				switch($matiere) {
					case 'Mathématiques':
						$class_matiere = 'event-info';
						$label_matiere = 'label-info';
						break;
					case 'Physique-Chimie':
						$class_matiere = 'event-success';
						$label_matiere = 'label-success';
						break;
					case 'Biologie':
						$class_matiere = 'event-warning';
						$label_matiere = 'label-warning';
						break;
					case 'Anglais':
						$class_matiere = 'event-important';
						$label_matiere = 'label-important';
						break;
					case 'Français':
						$class_matiere = 'event-inverse';
						$label_matiere = 'label-default';
						break;
					case 'Histoire-Géographie':
						$class_matiere = '';
						$label_matiere = '';
						break;
					default:
						$class_matiere = '';
						$label_matiere = '';
						break;
				}
				date_default_timezone_set("Europe/Paris");
				$start = strtotime($cal_event->getDateCours()->format('Y-m-d').' '.$cal_event->getHeureDebut().':00')*1000;
				$end = strtotime($cal_event->getDateCours()->format('Y-m-d').' '.$cal_event->getHeureFin().':00')*1000;
				
				$fr_date = $jour[$cal_event->getDateCours()->format('N')].' '.$cal_event->getDateCours()->format('j').' '.$mois[$cal_event->getDateCours()->format('n')];
				
				$result[] = array("id"=>$cal_event->getId(), "eleve"=>$cal_event->getEleve()->getId(), "reservation"=>$cal_event->getReservation(), "professeur"=>$cal_event->getProfesseur()->getId(), "title"=>$cal_event->getEleve()->getUsername().' a un cours avec '.$cal_event->getProfesseur()->getUsername().' de '.$cal_event->getHeureDebut().' à '.$cal_event->getHeureFin().'.', "url"=>"#", "class"=>$class_matiere, "date"=>$fr_date, "heureDebut"=>$cal_event->getHeureDebut(), "heureFin"=>$cal_event->getHeureFin(), "label"=>$label_matiere, "matiere"=>$matiere, "start"=>$start, "end"=>$end);
			}
			
			$professeurs = $famille->getProfesseurs();
			$i = 0;
			foreach($professeurs as $professeur) {
				$i++;
				$cal_events = $this->getDoctrine()
								   ->getManager()
								   ->getRepository('MajordeskAppBundle:CalEvent')
								   ->getAllProfesseurCalEvents($professeur->getId());

				foreach( $cal_events as $cal_event) {
					if ($cal_event->getEleve()->getFamille()->getId() != $famille->getId()) {
						$matiere = $cal_event->getMatiere()->getNom();
						$class_matiere = 'event-prof-'.$i;
						$label_matiere = 'label-prof-'.$i;

						$start = strtotime($cal_event->getDateCours()->format('Y-m-d').' '.$cal_event->getHeureDebut().':00').'000';
						$end = strtotime($cal_event->getDateCours()->format('Y-m-d').' '.$cal_event->getHeureFin().':00').'000';
						
						$fr_date = $jour[$cal_event->getDateCours()->format('N')].' '.$cal_event->getDateCours()->format('j').' '.$mois[$cal_event->getDateCours()->format('n')];
						
						$result[] = array("id"=>$cal_event->getId(), "eleve"=>$cal_event->getEleve()->getId(), "professeur"=>$professeur->getId(), "title"=>$professeur->getUsername().' est pris par un cours de '.$cal_event->getHeureDebut().' à '.$cal_event->getHeureFin().'.', "url"=>"#", "class"=>$class_matiere, "date"=>$fr_date, "heureDebut"=>$cal_event->getHeureDebut(), "heureFin"=>$cal_event->getHeureFin(), "label"=>$label_matiere, "matiere"=>$matiere, "start"=>$start, "end"=>$end);
					}
				}
			}
		}
		else if ($this->get('security.context')->isGranted('ROLE_PROF')) {
			$cal_events = $this->getDoctrine()
							   ->getManager()
							   ->getRepository('MajordeskAppBundle:CalEvent')
							   ->getAllProfesseurCalEvents($user->getId());
		
			foreach( $cal_events as $cal_event) {
				$matiere = $cal_event->getMatiere()->getNom();
				switch($matiere) {
					case 'Mathématiques':
						$class_matiere = 'event-info';
						$label_matiere = 'label-info';
						break;
					case 'Physique-Chimie':
						$class_matiere = 'event-success';
						$label_matiere = 'label-success';
						break;
					case 'Biologie':
						$class_matiere = 'event-warning';
						$label_matiere = 'label-warning';
						break;
					case 'Anglais':
						$class_matiere = 'event-important';
						$label_matiere = 'label-important';
						break;
					case 'Français':
						$class_matiere = 'event-inverse';
						$label_matiere = 'label-default';
						break;
					case 'Histoire-Géographie':
						$class_matiere = '';
						$label_matiere = '';
						break;
					default:
						$class_matiere = '';
						$label_matiere = '';
						break;
				}
				$start = strtotime($cal_event->getDateCours()->format('Y-m-d').' '.$cal_event->getHeureDebut().':00').'000';
				$end = strtotime($cal_event->getDateCours()->format('Y-m-d').' '.$cal_event->getHeureFin().':00').'000';
				
				$fr_date = $jour[$cal_event->getDateCours()->format('N')].' '.$cal_event->getDateCours()->format('j').' '.$mois[$cal_event->getDateCours()->format('n')];
				
				$result[] = array("id"=>$cal_event->getId(), "eleve"=>'0.5', "reservation"=>$cal_event->getReservation(), "professeur"=>0, "title"=>'Cours avec '.$cal_event->getEleve()->getUsername().' '.$cal_event->getEleve()->getNom().' ('.$cal_event->getHeureDebut().'-'.$cal_event->getHeureFin().')', "url"=>"#", "class"=>$class_matiere, "date"=>$fr_date, "heureDebut"=>$cal_event->getHeureDebut(), "heureFin"=>$cal_event->getHeureFin(), "label"=>$label_matiere, "matiere"=>$matiere, "start"=>$start, "end"=>$end);
			}	
		}
		$json_events = array("success"=>1, "result"=>$result);
		return new JsonResponse($json_events);
	}
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function exerciceToFavorisAction($id_exercice)
    {
		$exercice = $this->getDoctrine()
						 ->getManager()
						 ->getRepository('MajordeskAppBundle:Exercice')
						 ->find($id_exercice);
		
		$request = $this->getRequest();
		if ( $request->isXmlHttpRequest() ) {
			$today = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
			$exercice->setDateQueue($today);
			$exercice->setFavoris(true);			
		
			$em = $this->getDoctrine()->getManager();
			$em->persist($exercice);
			$em->flush();	
		}
		return new Response();
	}
	
	public function calendrierTemplatesAction($periode)
    {
		$timeStamp = strtotime("now").'000';
	
		switch($periode) {
			case 'day':
				$html = '<ul class="list-unstyled cal-event-list"> <% _.each(events, function(event){ %> <li><span class="pull-left event <%= event.class %>"></span> <a href="<%= event.url %>" data-event-id="<%= event.id %>" data-event-class="<%= event.class %>" class="event-item"> <%= event.title %></a></li> <% }) %> </ul> ';
				break;
			case 'events-list':
				$html = '<span class="hide" id="cal-slide-tick"></span> <div id="cal-slide-content" class="cal-event-list"> <ul class="list-unstyled"> <% _.each(events, function(event){ %> <li> <span class="label <%= $(event).data("label") %>"><%= $(event).data("matiere") %></span> <a href="<%= $(event).attr("href") %>" data-event-id="<%= $(event).data("event-id") %>" data-event-class="<%= $(event).data("event-class") %>" class="event-item"> <%= $(event).data("original-title") %> </a> </li> <% }) %> </ul> </div> ';
				break;
			case 'month':
				$html = '<div class="cal-row-fluid cal-row-head"> <% _.each(months, function(name){ %> <div class="cal-span1"><%= name %></div> <% }) %> </div> <div class="cal-month-box"> <% for(i = 0; i < 6; i++) { %> <% if(cal.break == true) break; %> <div class="cal-row-fluid"> <div class="cal-span1 cal-cell" data-cal-row="-day1"><%= cal._day(i, day++) %></div> <div class="cal-span1 cal-cell" data-cal-row="-day2"><%= cal._day(i, day++) %></div> <div class="cal-span1 cal-cell" data-cal-row="-day3"><%= cal._day(i, day++) %></div> <div class="cal-span1 cal-cell" data-cal-row="-day4"><%= cal._day(i, day++) %></div> <div class="cal-span1 cal-cell" data-cal-row="-day5"><%= cal._day(i, day++) %></div> <div class="cal-span1 cal-cell" data-cal-row="-day6"><%= cal._day(i, day++) %></div> <div class="cal-span1 cal-cell" data-cal-row="-day7"><%= cal._day(i, day++) %></div> </div> <% } %> </div>';
				break;
			case 'month-day':
				$html = '<div class="cal-month-day <%= cls %>"> <span class="pull-right" data-cal-date="<%=  data_day %>"  data-cal-view="day" rel="tooltip" data-original-title="<%= tooltip %>"><%= day %></span> <% if (events.length > 0) {%> <div class="events-list"> <% _.each(events, function(event){ %> <a href="<%= event.url %>" data-eleve="<%= event.eleve %>" data-professeur="<%= event.professeur %>" data-matiere="<%= event.matiere %>" data-label="<%= event.label %>" data-event-id="<%= event.id %>" data-event-class="<%= event.class %>" class="pull-left event <%= event.class %> event<%= event.id %>" rel="tooltip" data-original-title="<%= event.title %>" data-event-start="<%= event.start %>" data-event-end="<%= event.end %>"></a> <% }); %> </div> <% } %> </div>';
				break;
			case 'week':
				$html = '<div style="position: relative" class="cal-week-box"> <div class="cal-offset1 cal-column"></div> <div class="cal-offset2 cal-column"></div> <div class="cal-offset3 cal-column"></div> <div class="cal-offset4 cal-column"></div> <div class="cal-offset5 cal-column"></div> <div class="cal-offset6 cal-column"></div> <div class="cal-row-fluid cal-row-head"> <% _.each(months, function(name){  %> <div class="cal-span1"><%= name %><br> <small><span data-cal-date="<%= start.getFullYear() %>-<%= start.getMonthFormatted() %>-<%= start.getDateFormatted() %>" data-cal-view="day"><%= start.getDate() %> <%= language["ms" + start.getMonth()] %></span></small> </div> <% start.setDate(start.getDate() + 1); }) %> </div> <hr> <%= cal._week() %> </div>';
				break;
			case 'week-days':
				$html = '<% _.each(events, function(event){ %> <div class="cal-row-fluid"> <div class="cal-span<%= event.days%> cal-offset<%= event.start_day %> day-highlight dh-<%= event.class %>" data-event-class="<%= event.class %>"> <a href="<%= event.url %>" data-event-id="<%= event.id %>" class="cal-event-week event<%= event.id %>"><%= event.title %></a> </div> </div> <% }); %> ';
				break;
			case 'year':
				$html = '<div class="cal-year-box"> <div class="row-fluid cal-row-fluid"> <div class="span3 cal-cell" data-cal-row="-month1"><%= cal._month(0) %></div> <div class="span3 cal-cell" data-cal-row="-month2"><%= cal._month(1) %></div> <div class="span3 cal-cell" data-cal-row="-month3"><%= cal._month(2) %></div> <div class="span3 cal-cell" data-cal-row="-month4"><%= cal._month(3) %></div> </div> <div class="row-fluid cal-row-fluid"> <div class="span3 cal-cell" data-cal-row="-month1"><%= cal._month(4) %></div> <div class="span3 cal-cell" data-cal-row="-month2"><%= cal._month(5) %></div> <div class="span3 cal-cell" data-cal-row="-month3"><%= cal._month(6) %></div> <div class="span3 cal-cell" data-cal-row="-month4"><%= cal._month(7) %></div> </div> <div class="row-fluid cal-row-fluid"> <div class="span3 cal-cell" data-cal-row="-month1"><%= cal._month(8) %></div> <div class="span3 cal-cell" data-cal-row="-month2"><%= cal._month(9) %></div> <div class="span3 cal-cell" data-cal-row="-month3"><%= cal._month(10) %></div> <div class="span3 cal-cell" data-cal-row="-month4"><%= cal._month(11) %></div> </div> </div>';
				break;
			case 'year-month':
				$html = '<span class="pull-right" data-cal-date="<%= data_day %>" data-cal-view="month"><%= month_name %></span> <% if (events.length > 0) {%> <small class="cal-events-num badge badge-important pull-left"><%= events.length %></small> <div class="hide events-list"> <% _.each(events, function(event){ %> <a href="<%= event.url %>" data-event-id="<%= event.id %>" data-event-class="<%= event.class %>" class="pull-left event <%= event.class %> event<%= event.id %>" rel="tooltip" data-original-title="<%= event.title %>" data-event-start="<%= event.start %>" data-event-end="<%= event.end %>"></a> <% }); %> </div> <% } %> ';
				break;
		}
		return new Response($html);
	}
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function updateChapitreOnlyAction($chapitre_only)
    {
		$session = $this->get('session');
		$session->set('chapitre_only', $chapitre_only);
		$partie_only = $session->get('partie_only');
		if (empty($partie_only)) {
			$partie_only = 0;
		}
		if ($chapitre_only == 0) {
			$session->set('partie_only', 0);
		}
		
		return new JsonResponse(array('chapitre_only' => $chapitre_only, 'partie_only' => $partie_only));
	}
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function updatePartieOnlyAction($partie_only)
    {
		$session = $this->get('session');
		$session->set('partie_only', $partie_only);
		$chapitre_only = $session->get('chapitre_only');
		if (empty($chapitre_only)) {
			$chapitre_only = 0;
		}
		if ($partie_only == 1) {
			$session->set('chapitre_only', 1);
		}
		
		return new JsonResponse(array('chapitre_only' => $chapitre_only, 'partie_only' => $partie_only));
	}
}