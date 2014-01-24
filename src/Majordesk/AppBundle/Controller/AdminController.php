<?php

namespace Majordesk\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Majordesk\AppBundle\Form\Type\EleveType;
use Majordesk\AppBundle\Form\Type\AddEleveType;
use Majordesk\AppBundle\Form\Type\GererProfesseursType;

use Majordesk\AppBundle\Form\Type\FamilleType;
use Majordesk\AppBundle\Form\Type\AddFamilleType;

use Majordesk\AppBundle\Form\Type\ProfesseurType;
use Majordesk\AppBundle\Form\Type\AddProfesseurType;

use Majordesk\AppBundle\Form\Type\ChapitreType;
use Majordesk\AppBundle\Form\Type\ProgrammeType;
use Majordesk\AppBundle\Form\Type\TagType;

use Majordesk\AppBundle\Entity\Eleve;
use Majordesk\AppBundle\Entity\Famille;
use Majordesk\AppBundle\Entity\Client;
use Majordesk\AppBundle\Entity\Professeur;
use Majordesk\AppBundle\Entity\Disponibilite;
use Majordesk\AppBundle\Entity\ModExercice;
use Majordesk\AppBundle\Entity\ModType;
use Majordesk\AppBundle\Entity\ModQuestion;
use Majordesk\AppBundle\Entity\ModElement;
use Majordesk\AppBundle\Entity\Chapitre;
use Majordesk\AppBundle\Entity\Partie;
use Majordesk\AppBundle\Entity\Programme;
use Majordesk\AppBundle\Entity\Matiere;
use Majordesk\AppBundle\Entity\Tag;
use Majordesk\AppBundle\Entity\TagMap;

use Majordesk\AppBundle\Form\Model\ExerciceFormulaire;
use Majordesk\AppBundle\Form\Type\ExerciceFormulaireType;
use Majordesk\AppBundle\Form\Type\AddExerciceFormulaireType;
use Majordesk\AppBundle\Form\Type\ModExerciceNewType;

use Majordesk\AppBundle\Form\Type\ExercicesFilterType;

class AdminController extends Controller
{
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function indexAdminAction()
    {
		return $this->render('MajordeskAppBundle:Admin:index-admin.html.twig');
    }

/* FEEDBACKS */

	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function gestionFeedbacksAction()
    {
		$feedbacks = $this->getDoctrine()
					      ->getManager()
					      ->getRepository('MajordeskAppBundle:Feedback')
					      ->findAll();
		
        return $this->render('MajordeskAppBundle:Admin:gestion-feedbacks.html.twig', array(
			'feedbacks' => $feedbacks,
		));
    }

	
/* HEURES */

	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function gestionHeuresAction()
    {
		$tickets = $this->getDoctrine()
					    ->getManager()
					    ->getRepository('MajordeskAppBundle:Ticket')
					    ->getAllTickets();
					    // ->findAll();
		
		$cal_events = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:CalEvent')
						   ->findAll();
		
		$paiements = $this->getDoctrine()
						  ->getManager()
						  ->getRepository('MajordeskAppBundle:Paiement')
						  ->findAll();
		
        return $this->render('MajordeskAppBundle:Admin:gestion-heures.html.twig', array(
			'tickets' => $tickets,
			'cal_events' => $cal_events,
			'paiements' => $paiements,
		));
    }
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function gestionPaiementsAction()
    {
		$paiements = $this->getDoctrine()
						  ->getManager()
						  ->getRepository('MajordeskAppBundle:Paiement')
						  ->getAllPaiements();
		
        return $this->render('MajordeskAppBundle:Admin:gestion-paiements.html.twig', array(
			'paiements' => $paiements,
		));
    }
	

/* ELEVES */

	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function gestionElevesAction()
    {
		$eleves = $this->getDoctrine()
					   ->getManager()
					   ->getRepository('MajordeskAppBundle:Eleve')
					   ->findAll();
		
		$disponibilites = $this->getDoctrine()
								->getManager()
								->getRepository('MajordeskAppBundle:Disponibilite')
								->findAll();
		
		$eleve_matieres = $this->getDoctrine()
							   ->getManager()
							   ->getRepository('MajordeskAppBundle:EleveMatiere')
							   ->findAll();
		
        return $this->render('MajordeskAppBundle:Admin:gestion-eleves.html.twig', array(
			'eleves' => $eleves,
			'disponibilites' => $disponibilites,
			'eleve_matieres' => $eleve_matieres,
		));
    }
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function gestionElevesFilterFamilleAction($id)
    {
		$eleves = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Eleve')
		                   ->findBy(array('famille' => $id));
		
		$disponibilites = $this->getDoctrine()
						->getManager()
						->getRepository('MajordeskAppBundle:Disponibilite')
			            ->findAll();
		
        return $this->render('MajordeskAppBundle:Admin:gestion-eleves.html.twig', array(
			'eleves' => $eleves,
			'disponibilites' => $disponibilites,
			'filter_famille' => 'on'
		));
    }
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function gestionElevesFilterProfesseurAction($id)
    {
		$eleves = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Eleve')
		                   ->getElevesFilteredByProfesseur($id);
		
		$disponibilites = $this->getDoctrine()
						->getManager()
						->getRepository('MajordeskAppBundle:Disponibilite')
			            ->findAll();
		
        return $this->render('MajordeskAppBundle:Admin:gestion-eleves.html.twig', array(
			'eleves' => $eleves,
			'disponibilites' => $disponibilites,
			'filter_professeur' => 'on'
		));
    }
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function profilEleveAction($id)
    {
		$eleve = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Eleve')
		                   ->find($id);
		
		$disponibilites = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Disponibilite')
						   ->findBy(array('eleve' => $eleve->getId()));
		
        return $this->render('MajordeskAppBundle:Admin:profil-eleve.html.twig', array(
			'eleve' => $eleve,
			'disponibilites' => $disponibilites
		));
    }
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function modifierEleveAction($id)
    {
		$doctrine_manager = $this->get('majordesk_app.service.doctrine_manager');
	
		$eleve = $this->getDoctrine()
					  ->getManager()
					  ->getRepository('MajordeskAppBundle:Eleve')
					  ->find($id);
		
		$form = $this->createForm(new EleveType(), $eleve);
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$form->bind($request);
			
			if ($form->isValid()) 
			{
				$em = $this->getDoctrine()->getManager();
				
					// Suppression des entités		
					$toTrash = new \Doctrine\Common\Collections\ArrayCollection();
					$disponibilites = $form->getData()->getDisponibilites();
					$disponibilites_initiales = $this->getDoctrine()
												     ->getManager()
												     ->getRepository('MajordeskAppBundle:Disponibilite')
												     ->findBy(array('eleve' => $id));			   
					$toTrash = $doctrine_manager->locateEntitiesToDelete($disponibilites_initiales, $disponibilites, $toTrash);
					$doctrine_manager->flushEntitiesToDelete($toTrash);
				
				// Ajout des disponibilités
				foreach($disponibilites as $disponibilite) {
					$eleve->addDisponibilite($disponibilite);
				}
				
				$em->persist($eleve);
				$em->flush();
				
				$this->get('session')->getFlashBag()->add('info', 'Champ(s) modifié(s).');

				return $this->redirect($this->generateUrl('majordesk_app_profil_eleve', array('id' => $id)));
			}
			$this->get('session')->getFlashBag()->add('warning', $form->getErrorsAsString() );
		}
		
        return $this->render('MajordeskAppBundle:Admin:modifier-eleve.html.twig', array(
			'eleve' => $eleve,
			'form' => $form->createView()
		));
    }
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function gererProfesseursAction($id_eleve)
    {
		$eleve = $this->getDoctrine()
					  ->getManager()
					  ->getRepository('MajordeskAppBundle:Eleve')
					  ->find($id_eleve);
					  
		$professeurs = $eleve->getProfesseurs();
		$prof_array = array();
		foreach($professeurs as $professeur) {
			$prof_array[] = $professeur->getId();
		}
		
		$form = $this->createForm(new GererProfesseursType(), $eleve);
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$form->bind($request);
			
			if ($form->isValid()) 
			{
				$new_professeurs = $form->getData()->getProfesseurs();
				$new_prof_array = array();
				foreach($new_professeurs as $professeur) {
					$new_prof_array[] = $professeur->getId();
				}
				
				$array_diff = array_diff($new_prof_array,$prof_array);
				if (!empty($array_diff)) {
					$famille = $eleve->getFamille();
					$mail = $famille->getMail();
					$nom = $famille->getNom();
					$date_inscription = $famille->getDateInscription();
					$adresse = $famille->getAdresse().' '.$famille->getCodePostal().' '.$famille->getVille();
					$abonnement = $famille->getAbonnement();
					$parente = $famille->getGender();
					if ($parente % 2 == 0) {
						$gender = 'Cher M.';
					} else {
						$gender = 'Chère Mme.';
					}
					
					foreach($array_diff as $id_professeur) {
						$professeur = $this->getDoctrine()
										   ->getManager()
										   ->getRepository('MajordeskAppBundle:Professeur')
										   ->find($id_professeur);
						if (!empty($abonnement)) {
							$telephone = $professeur->getTelephone();
							$message = \Swift_Message::newInstance()
									->setSubject('Notification Majorclass')
									->setFrom('ne-pas-repondre@majorclass.fr')
									->setTo($mail)
									->setBody($this->renderView('MajordeskAppBundle:Template:assignation-et-mise-en-relation.html.twig', array('gender' => $gender, 'nom' => $nom, 'telephone' => $telephone)), 'text/html')
								;
								$this->get('mailer')->send($message);
								
							$message = \Swift_Message::newInstance()
									->setSubject('Notification Majorclass')
									->setFrom('ne-pas-repondre@majorclass.fr')
									->setTo($professeur->getMail())
									->setBody($this->renderView('MajordeskAppBundle:Template:avertissement-professeur.html.twig', array('nom' => $nom, 'prenom_enfant' => $eleve->getUsername(), 'classe' => $eleve->getProgramme()->getNom(), 'representant' => $representant,  'telephone' => $famille->getTelephone(), 'adresse' => $adresse)), 'text/html')
								;
								$this->get('mailer')->send($message);
						} else {
							$message = \Swift_Message::newInstance()
									->setSubject('Notification Majorclass')
									->setFrom('ne-pas-repondre@majorclass.fr')
									->setTo($mail)
									->setBody($this->renderView('MajordeskAppBundle:Template:assignation.html.twig', array('gender' => $gender, 'nom' => $nom, 'date_inscription' => $date_inscription)), 'text/html')
								;
								$this->get('mailer')->send($message);
						}
					}
				}
			
				$em = $this->getDoctrine()->getManager();
				$em->persist($eleve);
				$em->flush();
				
				$this->get('session')->getFlashBag()->add('info', 'Champ(s) modifié(s).');

				return $this->redirect($this->generateUrl('majordesk_app_profil_eleve', array('id' => $id_eleve)));
			}
			$this->get('session')->getFlashBag()->add('warning', 'Un ou plusieurs champs ont été mal remplis.');
		}	
		
        return $this->render('MajordeskAppBundle:Admin:gerer-professeurs.html.twig', array(
			'form' => $form->createView(),
			'id_eleve' => $id_eleve
		));
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function ajouterEleveAction()
    {
		$eleve = new Eleve();
	
		$form = $this->createForm(new AddEleveType(), $eleve);
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$form->bind($request);

			if ($form->isValid()) 
			{
				/* password encryption --> faire un service */
				$eleve->setSalt(time());
				$factory = $this->get('security.encoder_factory');
				$encoder = $factory->getEncoder($eleve);
				$password = $encoder->encodePassword($eleve->getPassword(), $eleve->getSalt()); // $eleve->getPassword()   <=>   $form->get('password')->getData()
				$eleve->setPassword($password);
				
				$em = $this->getDoctrine()->getManager();
				$em->persist($eleve);
				$em->flush();
				
				$this->get('session')->getFlashBag()->add('info', 'Elève ajouté.');

				return $this->redirect($this->generateUrl('majordesk_app_profil_eleve', array('id' => $eleve->getId())));
			}
			$this->get('session')->getFlashBag()->add('warning', 'Un ou plusieurs champs ont été mal remplis.');
		}
		
        return $this->render('MajordeskAppBundle:Admin:ajouter-eleve.html.twig', array(
			'form' => $form->createView()
		));
    }
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function deblocageExercicesAction($id_eleve)
    {		
		$limit = 10;
		$exercices = $this->getDoctrine()
					      ->getManager()
					      ->getRepository('MajordeskAppBundle:Exercice')
						  ->getLastExercices($id_eleve, $limit);
		
		return $this->render('MajordeskAppBundle:Professeur:gestion-devoirs.html.twig', array(
			'exercices' => $exercices,
		));
    }
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function deblocageRemoveAction($id_exercice, $id_eleve)
    {		
		$exercice = $this->getDoctrine()
					     ->getManager()
					     ->getRepository('MajordeskAppBundle:Exercice')
						 ->find($id_exercice);
		
		$em = $this->getDoctrine()->getManager();		
		$em->remove($exercice);
		$em->flush();
		
		return $this->redirect($this->generateUrl('majordesk_app_deblocage_exercices', array('id_eleve' => $id_eleve)));
    }
	
	
/* FAMILLES */

	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function gestionFamillesAction()
    {
		$familles = $this->getDoctrine()
						 ->getManager()
						 ->getRepository('MajordeskAppBundle:Famille')
		                 ->findAll();
		
		$parents = $this->getDoctrine()
						->getManager()
						->getRepository('MajordeskAppBundle:Client')
			            ->findAll();
		
        return $this->render('MajordeskAppBundle:Admin:gestion-familles.html.twig', array(
			'familles' => $familles,
			'parents' => $parents
		));
    }
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function gestionFamillesFilterEleveAction($id)
    {
		$familles = $this->getDoctrine()
						 ->getManager()
						 ->getRepository('MajordeskAppBundle:Famille')
						 ->findBy(array('id' => $id));
		
		$parents = $this->getDoctrine()
						->getManager()
						->getRepository('MajordeskAppBundle:Client')
			            ->findBy(array('famille' => $id));
		
        return $this->render('MajordeskAppBundle:Admin:gestion-familles.html.twig', array(
			'familles' => $familles,
			'parents' => $parents,
			'filter_eleve' => 'on'
		));
    }
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function profilFamilleAction($id)
    {
		$famille = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Famille')
						   ->find($id);
						   
		$parents = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Client')
						   ->findBy(array('famille' => $famille->getId()));
		
        return $this->render('MajordeskAppBundle:Admin:profil-famille.html.twig', array(
			'famille' => $famille,
			'parents' => $parents
		));
    }
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function modifierFamilleAction($id)
    {
		$famille = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Famille')
						   ->find($id);
		
		$parents = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Client')
						   ->findBy(array('famille' => $famille->getId()));
		
		$form = $this->createForm(new FamilleType(), $famille);
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$form->bind($request);

			if ($form->isValid()) 
			{
				$em = $this->getDoctrine()->getManager();
				$em->persist($famille);
				$em->flush();
				
				$this->get('session')->getFlashBag()->add('info', 'Champ(s) modifié(s).');

				return $this->redirect($this->generateUrl('majordesk_app_profil_famille', array('id' => $id)));
			}
			$this->get('session')->getFlashBag()->add('warning', 'Un ou plusieurs champs ont été mal remplis.');
		}	
		
        return $this->render('MajordeskAppBundle:Admin:modifier-famille.html.twig', array(
			'famille' => $famille,
			'parents' => $parents,
			'form' => $form->createView()
		));
    }	
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function ajouterFamilleAction()
    {	
		$famille = new Famille();
	
		$form = $this->createForm(new AddFamilleType(), $famille);
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$form->bind($request);

			if ($form->isValid()) 
			{		
				$factory = $this->get('security.encoder_factory');
				
				foreach($famille->getClients() as $client)
				{
					/* password encryption --> faire un service */
					$client->setSalt(time());
					$encoder = $factory->getEncoder($client);
					$password = $encoder->encodePassword($client->getPassword(), $client->getSalt());
					$client->setPassword($password);
				}
				
				$em = $this->getDoctrine()->getManager();
				$em->persist($famille);
				$em->flush();
				
				$this->get('session')->getFlashBag()->add('info', 'Famille et Parent(s) ajoutés.');

				return $this->redirect($this->generateUrl('majordesk_app_profil_famille', array('id' => $famille->getId())));
			}
			$this->get('session')->getFlashBag()->add('warning', 'Un ou plusieurs champs ont été mal remplis.');
		}
		
        return $this->render('MajordeskAppBundle:Admin:ajouter-famille.html.twig', array(
			'form' => $form->createView()
		));
    }
	
/* PROFESSEURS */

	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function gestionProfesseursAction()
    {
		$professeurs = $this->getDoctrine()
						 ->getManager()
						 ->getRepository('MajordeskAppBundle:Professeur')
		                 ->findAll();
		
		$disponibilites = $this->getDoctrine()
						->getManager()
						->getRepository('MajordeskAppBundle:Disponibilite')
			            ->findAll();
		
        return $this->render('MajordeskAppBundle:Admin:gestion-professeurs.html.twig', array(
			'professeurs' => $professeurs,
			'disponibilites' => $disponibilites
		));
    }
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function gestionProfesseursFilterEleveAction($id)
    {
		$repository = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Professeur');
		$professeurs = $repository->getProfesseursFilteredByEleve($id);
		
		$disponibilites = $this->getDoctrine()
						->getManager()
						->getRepository('MajordeskAppBundle:Disponibilite')
			            ->findAll();
		
        return $this->render('MajordeskAppBundle:Admin:gestion-professeurs.html.twig', array(
			'professeurs' => $professeurs,
			'disponibilites' => $disponibilites,
			'filter_eleve' => 'on'
			));
    }
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function gestionProfesseursFilterDisponibilitesAction($lu, $ma, $me, $je, $ve, $sa, $di)
    {
		$repository = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Professeur');
		$professeurs = $repository->getProfesseursFilteredByDisponibilites($lu, $ma, $me, $je, $ve, $sa, $di);
		
		$disponibilites = $this->getDoctrine()
						->getManager()
						->getRepository('MajordeskAppBundle:Disponibilite')
			            ->findAll();
		
        return $this->render('MajordeskAppBundle:Admin:gestion-professeurs.html.twig', array(
			'professeurs' => $professeurs,
			'disponibilites' => $disponibilites,
			'filter_lu' => $lu,
			'filter_ma' => $ma,
			'filter_me' => $me,
			'filter_je' => $je,
			'filter_ve' => $ve,
			'filter_sa' => $sa,
			'filter_di' => $di,
			));
    }
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function profilProfesseurAction($id)
    {
		$professeur = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Professeur')
						   ->find($id);
						   
		$disponibilites = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Disponibilite')
						   ->findBy(array('professeur' => $professeur->getId()));
		
        return $this->render('MajordeskAppBundle:Admin:profil-professeur.html.twig', array(
			'professeur' => $professeur,
			'disponibilites' => $disponibilites
		));
    }
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function modifierProfesseurAction($id)
    {
		$doctrine_manager = $this->get('majordesk_app.service.doctrine_manager');
	
		$professeur = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Professeur')
						   ->find($id);

		$form = $this->createForm(new ProfesseurType(), $professeur);
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$form->bind($request);
			
			if ($form->isValid()) 
			{
				$em = $this->getDoctrine()->getManager();
				
					// Suppression des entités		
					$toTrash = new \Doctrine\Common\Collections\ArrayCollection();
					$disponibilites = $form->getData()->getDisponibilites();
					$disponibilites_initiales = $this->getDoctrine()
												     ->getManager()
												     ->getRepository('MajordeskAppBundle:Disponibilite')
												     ->findBy(array('professeur' => $id));			   
					$toTrash = $doctrine_manager->locateEntitiesToDelete($disponibilites_initiales, $disponibilites, $toTrash);
					$doctrine_manager->flushEntitiesToDelete($toTrash);
				
				// Ajout des disponibilités
				foreach($disponibilites as $disponibilite) {
					$professeur->addDisponibilite($disponibilite);
				}
				
				$em->persist($professeur);
				$em->flush();
				
				$this->get('session')->getFlashBag()->add('info', 'Champ(s) modifié(s).');

				return $this->redirect($this->generateUrl('majordesk_app_profil_professeur', array('id' => $id)));
			}
			$this->get('session')->getFlashBag()->add('warning', 'Un ou plusieurs champs ont été mal remplis.');
		}	
		
        return $this->render('MajordeskAppBundle:Admin:modifier-professeur.html.twig', array(
			'professeur' => $professeur,
			'form' => $form->createView()
		));
    }	
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function ajouterProfesseurAction()
    {	
		$professeur = new Professeur();
	
		$form = $this->createForm(new AddProfesseurType(), $professeur);
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$form->bind($request);

			if ($form->isValid()) 
			{					
				/* password encryption --> faire un service */
				$professeur->setSalt(time());
				$factory = $this->get('security.encoder_factory');
				$encoder = $factory->getEncoder($professeur);
				$password = $encoder->encodePassword($professeur->getPassword(), $professeur->getSalt()); // $professeur->getPassword()   <=>   $form->get('password')->getData()
				$professeur->setPassword($password);
				
				$em = $this->getDoctrine()->getManager();
				$em->persist($professeur);
				$em->flush();
				
				$this->get('session')->getFlashBag()->add('info', 'Professeur et Disponibilités ajoutés.');

				return $this->redirect($this->generateUrl('majordesk_app_profil_professeur', array('id' => $professeur->getId())));
			}
			// $errors = $form->getErrorsAsString();
			$this->get('session')->getFlashBag()->add('warning', 'Un ou plusieurs champs ont été mal remplis.');
		}
		
        return $this->render('MajordeskAppBundle:Admin:ajouter-professeur.html.twig', array(
			'form' => $form->createView()
		));
    }
	
	
/* CONTENU */

	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
    public function gestionContenuAction()
    {		
		$en_edition = $this->container->getParameter('statut_en_edition');
		$contenu_manager = $this->get('majordesk_app.service.contenu_manager');
		
		$matieres = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Matiere')
						   ->findAll();
		
		if (empty($matieres)) {
			throw new \Exception('Aucune matière n\'a été trouvée !');
		}
		
		$ids_exercices_en_cours = $contenu_manager->getModExercicesEnCours($en_edition, $matieres);				   
		
		if (empty($ids_exercices_en_cours)) {
			throw new \Exception('Aucun exercice en cours n\'a été trouvé !');
		}
		
        return $this->render('MajordeskAppBundle:Admin:gestion-contenu.html.twig', array(
			'matieres' => $matieres,
			'ids_exercices_en_cours' => $ids_exercices_en_cours
		));
    }
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function gestionnaireExercicesAction()
    {	
		$en_edition = $this->container->getParameter('statut_en_edition');
		$en_ligne = $this->container->getParameter('statut_en_ligne');
		
		$form = $this->createForm(new ExercicesFilterType(), new ModExercice());
		
		$exercices_en_edition = $this->getDoctrine()
								   ->getManager()
								   ->getRepository('MajordeskAppBundle:ModExercice')
								   ->getModExercicesOrderedByDate($en_edition);
		
		$exercices_en_ligne = $this->getDoctrine()
								   ->getManager()
								   ->getRepository('MajordeskAppBundle:ModExercice')
								   ->getModExercicesOrderedByDate($en_ligne);
		
        return $this->render('MajordeskAppBundle:Admin:gestionnaire-exercices.html.twig', array(
			'exercices_en_edition' => $exercices_en_edition,
			'exercices_en_ligne' => $exercices_en_ligne,
			'form' => $form->createView(),
		));
    }
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function ajouterExerciceAction()
    {
		$mod_exercice = new ModExercice();
		$mod_type = new ModType();
		$mod_exercice->setModType($mod_type);
		$em = $this->getDoctrine()->getManager();	
		$em->persist($mod_exercice);	
		$em->persist($mod_type);	
		$em->flush();
		return $this->redirect($this->generateUrl('majordesk_app_modifier_exercice', array('id' => $mod_exercice->getId())));
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function modifierExerciceAction($id)
    {
		$mod_exercice = $this->getDoctrine()
							 ->getManager()
							 ->getRepository('MajordeskAppBundle:ModExercice')
							 ->find($id); 
							  
		$mod_questions = $this->getDoctrine()
							  ->getManager()
							  ->getRepository('MajordeskAppBundle:ModQuestion')
							  ->getOrderedModQuestionsByModExerciceId($id);
		
		$mod_briques = $this->getDoctrine()
							->getManager()
							->getRepository('MajordeskAppBundle:ModBrique')
							->getOrderedModBriquesInSuperBriquesByModExercice($id);
		
		$mod_briques_c = $this->getDoctrine()
							  ->getManager()
							  ->getRepository('MajordeskAppBundle:ModBrique')
							  ->getOrderedModBriquesInComplementsByModExercice($id);
		
		$mod_complements = $this->getDoctrine()
								->getManager()
								->getRepository('MajordeskAppBundle:ModComplement')
								->getOrderedModComplementsByModExercice($id);
		
		$mod_mappings = $this->getDoctrine()
							 ->getManager()
							 ->getRepository('MajordeskAppBundle:ModMapping')
							 ->getOrderedModMappingsByModExerciceId($id);
		
		$mod_reponses = $this->getDoctrine()
							 ->getManager()
							 ->getRepository('MajordeskAppBundle:ModReponse')
							 ->getOrderedModReponsesByModExercice($id);
		
		$form = $this->createForm(new ModExerciceNewType(), $mod_exercice );
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$form->bind($request);			

			if ($form->isValid()) 
			{	
				$em = $this->getDoctrine()->getManager();	
				$em->persist($mod_exercice);	
				$em->flush();
				
				$this->get('session')->getFlashBag()->add('info', 'Exercice et questions modifiés.');

				return $this->redirect($this->generateUrl('majordesk_app_afficher_exercice', array('id' => $mod_exercice->getId())));
			}
			$this->get('session')->getFlashBag()->add('warning', 'Un ou plusieurs champs ont été mal remplis.');
		}	
		
		return $this->render('MajordeskAppBundle:Admin:modifier-exercice-new.html.twig', array(
			'form' => $form->createView(),
			'mod_exercice' => $mod_exercice,
			'mod_questions' => $mod_questions,
			'mod_briques' => $mod_briques,
			'mod_briques_c' => $mod_briques_c,
			'mod_complements' => $mod_complements,
			'mod_mappings' => $mod_mappings,
			'mod_reponses' => $mod_reponses
		));
    }
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function dupliquerExerciceAction($id)
    {
		$mod_exercice = $this->getDoctrine()
							 ->getManager()
							 ->getRepository('MajordeskAppBundle:ModExercice')
							 ->find($id); 
							 
		$mod_exercice_clone = clone $mod_exercice;
		$mod_exercice_clone->updateDateEnregistrementToCurrentDate();
		
		foreach($mod_exercice->getModQuestions() as $mod_question) {
			$mod_question_clone = clone $mod_question;
			$mod_exercice_clone->addModQuestion($mod_question_clone);
			
			$clones = array();
			foreach($mod_question->getModBriques() as $mod_brique) {
				$mod_brique_clone = clone $mod_brique;
				$mod_question_clone->addModBrique($mod_brique_clone);
				$clones[$mod_brique->getId()] = $mod_brique_clone;
			}
			
			foreach($mod_question->getModComplements() as $mod_complement) {
				$mod_complement_clone = clone $mod_complement;
				$mod_question_clone->addModComplement($mod_complement_clone);
				
				foreach($mod_complement->getModBriques() as $mod_brique) {
					$mod_brique_clone = clone $mod_brique;
					$mod_complement_clone->addModBrique($mod_brique_clone);
				}
			}
			
			foreach($mod_question->getModMappings() as $mod_mapping) {
				$mod_mapping_clone = clone $mod_mapping;
				$mod_question_clone->addModMapping($mod_mapping_clone);
				
				foreach($mod_mapping->getModReponses() as $mod_reponse) {
					$mod_reponse_clone = clone $mod_reponse;
					$mod_brique = $mod_reponse->getModBrique();
					if (!empty($mod_brique)) {
						$mod_reponse_clone->setModBrique($clones[$mod_brique->getId()]);
					}
					$mod_mapping_clone->addModReponse($mod_reponse_clone);
				}
			}
		}
		
		$em = $this->getDoctrine()->getManager();	
		if ($mod_exercice->getModType() != null) {
			$mod_exercice_clone->setModType($mod_exercice->getModType());
		} else {
			$mod_type = new ModType();
			$mod_exercice->setModType($mod_type);
			$mod_exercice_clone->setModType($mod_type);
			$em->persist($mod_exercice);
		}	
		$em->persist($mod_exercice_clone);	
		$em->flush();
		
		return $this->redirect($this->generateUrl('majordesk_app_modifier_exercice', array('id' => $mod_exercice_clone->getId())));
	}
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function afficherExerciceAction($id)
    {		
		$mod_exercice = $this->getDoctrine()
							 ->getManager()
						     ->getRepository('MajordeskAppBundle:ModExercice')
						     ->find($id);
		
		if (empty($mod_exercice)) {
			throw new \Exception('L\'exercice recherché n\'existe pas !');
		}
		
		if ($mod_exercice->getIsNew() == true) {
			$mod_questions = $this->getDoctrine()
								  ->getManager()
								  ->getRepository('MajordeskAppBundle:ModQuestion')
								  ->getOrderedModQuestionsByModExerciceId($id);
			
			$mod_briques = $this->getDoctrine()
							    ->getManager()
							    ->getRepository('MajordeskAppBundle:ModBrique')
							    ->getOrderedModBriquesInSuperBriquesByModExercice($id);
			
			$mod_briques_c = $this->getDoctrine()
								  ->getManager()
								  ->getRepository('MajordeskAppBundle:ModBrique')
								  ->getOrderedModBriquesInComplementsByModExercice($id);
			
			$mod_complements = $this->getDoctrine()
									->getManager()
									->getRepository('MajordeskAppBundle:ModComplement')
									->getOrderedModComplementsByModExercice($id);
			
			$mod_mappings = $this->getDoctrine()
								 ->getManager()
								 ->getRepository('MajordeskAppBundle:ModMapping')
								 ->getOrderedModMappingsByModExerciceId($id);
			
			$mod_reponses = $this->getDoctrine()
								 ->getManager()
								 ->getRepository('MajordeskAppBundle:ModReponse')
								 ->getOrderedModReponsesByModExercice($id);

			return $this->render('MajordeskAppBundle:Admin:afficher-exercice-new.html.twig', array(
				'mod_exercice' => $mod_exercice,
				'mod_questions' => $mod_questions,
				'mod_briques' => $mod_briques,
				'mod_briques_c' => $mod_briques_c,
				'mod_mappings' => $mod_mappings,
				'mod_reponses' => $mod_reponses,
				'mod_complements' => $mod_complements,
			));
		}
		else {
			$mod_macros_exercice = $this->getDoctrine()
										->getManager()
										->getRepository('MajordeskAppBundle:ModMacro')
										->getOrderedModMacrosExerciceByModExerciceId($id);
			
			$mod_elements_exercice = $this->getDoctrine()
										  ->getManager()
										  ->getRepository('MajordeskAppBundle:ModElement')
										  ->getOrderedModElementsExerciceByModExerciceId($id);
			
			$mod_questions = $this->getDoctrine()
								  ->getManager()
								  ->getRepository('MajordeskAppBundle:ModQuestion')
								  ->getOrderedModQuestionsByModExerciceId($id);
			
			$mod_macros = $this->getDoctrine()
							   ->getManager()
							   ->getRepository('MajordeskAppBundle:ModMacro')
							   ->getOrderedModMacrosByModExerciceId($id);
			
			$mod_elements = $this->getDoctrine()
								 ->getManager()
								 ->getRepository('MajordeskAppBundle:ModElement')
								 ->getOrderedModElementsByModExerciceId($id);
			
			
		
			return $this->render('MajordeskAppBundle:Admin:afficher-exercice.html.twig', array(
				'mod_exercice' => $mod_exercice,
				'mod_macros_exercice' => $mod_macros_exercice,
				'mod_elements_exercice' => $mod_elements_exercice,
				'mod_questions' => $mod_questions,
				'mod_macros' => $mod_macros,
				'mod_elements' => $mod_elements
			));
		}
    }
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function gestionnaireChapitresAction($id)
    {			
		$programmes = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Programme')
						   ->findAll();
		
		$matiere = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Matiere')
						   ->find($id);
		
		$chapitres = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Chapitre')
						   ->getChapitresByMatiere($id);
		
		$parties = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Partie')
						   ->getPartiesByMatiere($id);
		
        return $this->render('MajordeskAppBundle:Admin:gestionnaire-chapitres.html.twig', array(
			'programmes' => $programmes,
			'chapitres' => $chapitres,
			'parties' => $parties,
			'matiere' => $matiere
		));
    }
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function modifierChapitreAction($id_chapitre)
    {	
		$doctrine_manager = $this->get('majordesk_app.service.doctrine_manager');
	
		$chapitre = $this->getDoctrine()
					     ->getManager()
					 	 ->getRepository('MajordeskAppBundle:Chapitre')
						 ->find($id_chapitre);
						 
		if (empty($chapitre)) {
			throw new \Exception('Le chapitre recherché n\'existe pas !');
		}
		
		$form = $this->createForm(new ChapitreType(), $chapitre);
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$form->bind($request);
			
			if ($form->isValid()) 
			{
				$em = $this->getDoctrine()->getManager();
				
					// Suppression des entités		
					$toTrash = new \Doctrine\Common\Collections\ArrayCollection();
					$parties = $form->getData()->getParties();
					$parties_initiales = $this->getDoctrine()
											  ->getManager()
											  ->getRepository('MajordeskAppBundle:Partie')
											  ->getPartiesByChapitre($id_chapitre);				   
					$toTrash = $doctrine_manager->locateEntitiesToDelete($parties_initiales, $parties, $toTrash);
					$doctrine_manager->flushEntitiesToDelete($toTrash);
				
				$em->persist($chapitre);
				$em->flush();
				
				$this->get('session')->getFlashBag()->add('info', 'Chapitre modifié.');

				return $this->redirect($this->generateUrl('majordesk_app_gestionnaire_chapitres', array('id' => $chapitre->getMatiere()->getId())));
			}
			$this->get('session')->getFlashBag()->add('warning', 'Un ou plusieurs champs ont été mal remplis.');
		}	
	
        return $this->render('MajordeskAppBundle:Admin:modifier-chapitre.html.twig', array(
			'chapitre' => $chapitre,
			'form' => $form->createView()
		));
    }
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function modifierProgrammeAction($id_matiere, $id_programme)
    {	
		$doctrine_manager = $this->get('majordesk_app.service.doctrine_manager');
		
		$matiere = $this->getDoctrine()
					    ->getManager()
					    ->getRepository('MajordeskAppBundle:Matiere')
				        ->find($id_matiere);
		
		$programme = $this->getDoctrine()
					      ->getManager()
					      ->getRepository('MajordeskAppBundle:Programme')
						  ->find($id_programme);
						 
		if (empty($programme)) {
			throw new \Exception('Le programme recherché n\'existe pas !');
		}
		
		$form = $this->createForm(new ProgrammeType(), $programme);
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$form->bind($request);
			
			if ($form->isValid()) 
			{
				$em = $this->getDoctrine()->getManager();
				
					// Suppression des entités		
					$toTrash = new \Doctrine\Common\Collections\ArrayCollection();
					$chapitres = $form->getData()->getChapitres();
					$chapitres_initiaux = $this->getDoctrine()
											   ->getManager()
											   ->getRepository('MajordeskAppBundle:Chapitre')
											   ->findBy(array('programme' => $id_programme));				   
					$toTrash = $doctrine_manager->locateEntitiesToDelete($chapitres_initiaux, $chapitres, $toTrash);
					$doctrine_manager->flushEntitiesToDelete($toTrash);
				
					// Lien des chapitres à la matière
					foreach($chapitres as $chapitre) {
						$chapitre->setMatiere($matiere);
					}
				
				$em->persist($programme);
				$em->flush();
				
				$this->get('session')->getFlashBag()->add('info', 'Chapitre modifié.');

				return $this->redirect($this->generateUrl('majordesk_app_gestionnaire_chapitres', array('id' => $chapitre->getMatiere()->getId())));
			}
			$this->get('session')->getFlashBag()->add('warning', 'Un ou plusieurs champs ont été mal remplis.');
		}	
	
        return $this->render('MajordeskAppBundle:Admin:modifier-programme.html.twig', array(
			'matiere' => $matiere,
			'programme' => $programme,
			'form' => $form->createView()
		));
    }
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function gestionnaireTagsAction($id_matiere)
    {			
		$tags = $this->getDoctrine()
				     ->getManager()
				     ->getRepository('MajordeskAppBundle:Tag')
				     ->findBy(array('matiere' => $id_matiere));
					 
		$matiere = $this->getDoctrine()
						->getManager()
						->getRepository('MajordeskAppBundle:Matiere')
						->find($id_matiere);
		
        return $this->render('MajordeskAppBundle:Admin:gestionnaire-tags.html.twig', array(
			'tags' => $tags,
			'matiere' => $matiere
		));
    }
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function modifierTagAction($id_tag, $id_matiere)
    {	
		$matiere = $this->getDoctrine()
					    ->getManager()
				        ->getRepository('MajordeskAppBundle:Matiere')
					    ->find($id_matiere );
		
		$tags = $this->getDoctrine()
					 ->getManager()
				     ->getRepository('MajordeskAppBundle:Tag')
					 ->findBy(array('matiere' => $id_matiere));
		
		if ( $id_tag != 0 ) {
			$tag = $this->getDoctrine()
						->getManager()
						->getRepository('MajordeskAppBundle:Tag')
						->find($id_tag);
		}
		else {
			$tag = new Tag();
			$tag->setMatiere($matiere);
		}
		
		$form = $this->createForm(new TagType(), $tag, array('em' => $this->getDoctrine()->getManager()) );
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$form->bind($request);
			
			if ($form->isValid()) 
			{
				$em = $this->getDoctrine()->getManager();	
			
				foreach($form->getData()->getPTags() as $p_tag) {
					if($p_tag->getMatiere() == null) {
						$p_tag->setMatiere($matiere);
					}
					$c_tags = $p_tag->getCTags();
					if(empty($c_tags)) {
						$p_tag->addCTag($tag);
					}
				}
			
				foreach($form->getData()->getCTags() as $c_tag) {
					if($c_tag->getMatiere() == null) {
						$c_tag->setMatiere($matiere);
					}
				}	
						
				$em->persist($tag);
				$em->flush();
				if ( $id_tag != 0 ) {
					$this->get('session')->getFlashBag()->add('info', 'Tag(s) modifié(s).');
				}
				else {
					$this->get('session')->getFlashBag()->add('info', 'Tag(s) ajouté(s).');
				}

				return $this->redirect($this->generateUrl('majordesk_app_gestionnaire_tags', array('id_matiere' => $id_matiere)));
			}
			$this->get('session')->getFlashBag()->add('warning', 'Un ou plusieurs champs ont été mal remplis.');
		}	
	
        return $this->render('MajordeskAppBundle:Admin:modifier-tag.html.twig', array(			
			'tag' => $tag,
			'tags' => $tags,
			'id_tag' => $id_tag,
			'id_matiere' => $id_matiere,
			'form' => $form->createView()
		));
    }
	
	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function supprimerTagAction($id_tag)
    {		
		$tag = $this->getDoctrine()
					->getManager()
				    ->getRepository('MajordeskAppBundle:Tag')
				    ->find($id_tag);
		$id_matiere = $tag->getMatiere()->getId();
		
		if ( empty($tag) ) {
			throw new \Exception('Ce tag n\'existe pas !');
		}
		
		$em = $this->getDoctrine()->getManager();		
		$em->remove($tag);
		$em->flush();
			
		$this->get('session')->getFlashBag()->add('info', 'Tag(s) supprimé(s).');
		
		return $this->redirect($this->generateUrl('majordesk_app_gestionnaire_tags', array('id_matiere' => $id_matiere)));
    }
}