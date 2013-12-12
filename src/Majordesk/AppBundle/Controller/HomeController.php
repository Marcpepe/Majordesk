<?php

namespace Majordesk\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Symfony\Component\HttpFoundation\Response;

use Majordesk\AppBundle\Form\Type\InscriptionEleveType;
use Majordesk\AppBundle\Form\Type\InscriptionFamilleType;

use Majordesk\AppBundle\Entity\Eleve;
use Majordesk\AppBundle\Entity\EleveMatiere;
use Majordesk\AppBundle\Entity\Client;
use Majordesk\AppBundle\Entity\Famille;
use Majordesk\AppBundle\Entity\Disponibilite;
use Majordesk\AppBundle\Entity\Paiement;

class HomeController extends Controller
{
    public function connexionAction()
    {
		if ($this->get('security.context')->isGranted('ROLE_ELEVE') && !$this->get('security.context')->isGranted('ROLE_PROF') && !$this->get('security.context')->isGranted('ROLE_PARENTS') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
			return $this->redirect($this->generateUrl('majordesk_app_index_eleve'));
		}
		elseif ($this->get('security.context')->isGranted('ROLE_PROF') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
			return $this->redirect($this->generateUrl('majordesk_app_index_professeur'));
		}
		elseif ($this->get('security.context')->isGranted('ROLE_PARENTS') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
			return $this->redirect($this->generateUrl('majordesk_app_index_parents'));
		}
		elseif ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
			return $this->redirect($this->generateUrl('majordesk_app_index_admin'));
		}      
		else {
			return $this->redirect($this->generateUrl('login'));
		}
    }
	
	public function principeIndexAction()
    {
		return $this->render('MajordeskAppBundle:Home:index.html.twig');
    }
	
	public function phpInfoAction()
    {
		return $this->render('MajordeskAppBundle:Home:phpinfo.html.php');
    }
	
	public function coursPresentationAction()
    {
		return $this->render('MajordeskAppBundle:Home:presentation-cours.html.twig');
    }
	
	public function plateformePresentationAction()
    {
		return $this->render('MajordeskAppBundle:Home:presentation-plateforme.html.twig');
    }
	
	public function tarifsPresentationAction()
    {
		return $this->render('MajordeskAppBundle:Home:presentation-tarifs.html.twig');
    }
	
	public function equipePresentationAction()
    {
		return $this->render('MajordeskAppBundle:Home:presentation-equipe.html.twig');
    }
	
	public function inscriptionAction($etape_inscription)
    {
		$session = $this->get('session');
		$em = $this->getDoctrine()->getManager();
		$etape_session = $session->get('etape_inscription');
		if ($etape_inscription == 1) {
			if ($etape_session == null) {
				$session->set('etape_inscription', 1);
			}
			
			$eleve = new Eleve();
			$username = $session->get('username');
			if ($username != '') {
					$eleve->setUsername($username);
				$nom = $session->get('nom');
					$eleve->setNom($nom);
				$prog = $session->get('prog');
				$prog = $this->getDoctrine()
							 ->getManager()
							 ->getRepository('MajordeskAppBundle:Programme')
							 ->find($prog);
					$eleve->setProgramme($prog);
				$lycee = $session->get('lycee');
					$eleve->setLycee($lycee);
				$telephone = $session->get('telephone');	
					$eleve->setTelephone($telephone);
				$email = $session->get('mail');	
					$eleve->setMail($email);
				
				for($i=1;$i<=7;$i++) {
					$disponibilite = new Disponibilite();
					${'jour_'.$i} = $session->get('jour_'.$i);	
					${'heure_debut_'.$i} = $session->get('heure_debut_'.$i);	
					${'heure_fin_'.$i} = $session->get('heure_fin_'.$i);	
					if (!empty(${'jour_'.$i}) && !empty(${'heure_debut_'.$i}) && !empty(${'heure_fin_'.$i})) {
						$disponibilite->setJour(${'jour_'.$i});
						$disponibilite->setHeureDebut(${'heure_debut_'.$i});
						$disponibilite->setHeureFin(${'heure_fin_'.$i});
						$eleve->addDisponibilite($disponibilite);
					}
					else {
						break;
					}
				}
			}
		
			$form = $this->createForm(new InscriptionEleveType(), $eleve);
			
			$request = $this->getRequest();
			if ($request->getMethod() == 'POST') 
			{
				$form->bind($request);

				if ($form->isValid()) 
				{											
					
					if ($form->getData()->getProgramme() != null) {
						$session->set('prog', $form->getData()->getProgramme()->getId());
						$session->set('username', ucfirst($form->getData()->getUsername()));
						$session->set('nom', ucfirst($form->getData()->getNom()));
						$session->set('lycee', $form->getData()->getLycee());
						$session->set('telephone', $form->getData()->getTelephone());
						$session->set('mail', $form->getData()->getMail());
						$session->set('password', $form->getData()->getPassword());
						$i=1;
						if ($form->getData()->getDisponibilites() != null) {
							foreach($form->getData()->getDisponibilites() as $disponibilite) {
								$session->set('jour_'.$i, $disponibilite->getJour());
								$session->set('heure_debut_'.$i, $disponibilite->getHeureDebut());
								$session->set('heure_fin_'.$i, $disponibilite->getHeureFin());
								$i++;
							}
						}
						
						$matiere_maths = $request->request->get('matiere_maths');
						$matiere_physique = $request->request->get('matiere_physique');	
						
						if ($matiere_maths=="on") {
							$session->set('matiere_maths', 1);	
						} else {
							$session->set('matiere_maths', 0);	
						}
						if ($matiere_physique=="on") {
							$session->set('matiere_physique', 1);	
						} else {
							$session->set('matiere_physique', 0);	
						}
						
						if (empty($matiere_maths) && empty($matiere_physique)) {				
							$this->get('session')->getFlashBag()->add('warning-matiere', 'Veuillez sélectionner au moins une matière.');
							return $this->redirect($this->generateUrl('majordesk_app_inscription', array('etape_inscription' => 1)));
						}	
							
						$session->set('etape_inscription', 2);
						return $this->redirect($this->generateUrl('majordesk_app_inscription', array('etape_inscription' => 2)));
					}
					else {
						$this->get('session')->getFlashBag()->add('info', 'Programme non renseigné.');
					}
				}
				$this->get('session')->getFlashBag()->add('warning', 'Un ou plusieurs champs ont été mal remplis.');
			}
		}
		else if ($etape_inscription == 2) {
			if ($etape_session >= 2) {
				$famille = new Famille();
				$client = new Client();
				$username_famille = $session->get('username_famille');
				if ($username_famille != '') {
						$client->setUsername($username_famille);
					$gender = $session->get('gender');
						$client->setGender($gender);
					$nom_famille = $session->get('nom_famille');
						$client->setNom($nom_famille);
					$telephone_famille = $session->get('telephone_famille');	
						$client->setTelephone($telephone_famille);
					$adresse = $session->get('adresse');	
						$client->setAdresse($adresse);
					$code_postal = $session->get('code_postal');	
						$client->setCodePostal($code_postal);
					$ville = $session->get('ville');	
						$client->setVille($ville);
					$mail_famille = $session->get('mail_famille');	
						$client->setMail($mail_famille);
				}
				$famille->addClient($client);
			
				$form = $this->createForm(new InscriptionFamilleType(), $famille);
				
				$request = $this->getRequest();
				if ($request->getMethod() == 'POST') 
				{
					$form->bind($request);

					if ($form->isValid()) 
					{	
						$factory = $this->get('security.encoder_factory');
						
						// Eleve
						$eleve = new Eleve();
						$eleve->setActif(true);
						$eleve->setUsername($session->get('username'));
						$eleve->setNom($session->get('nom'));
						$eleve->setMail($session->get('mail'));
						$eleve->setTelephone($session->get('telephone'));
						$eleve->setSalt(time());
							$encoder = $factory->getEncoder($eleve);
							$pass = $encoder->encodePassword($session->get('password'), $eleve->getSalt()); 
						$eleve->setPassword($pass);					
						$eleve->setLycee($session->get('lycee'));
						
						$prog = $this->getDoctrine()
									 ->getManager()
									 ->getRepository('MajordeskAppBundle:Programme')
									 ->find($session->get('prog'));
						$eleve->setProgramme($prog);
						
						for($i=1;$i<=7;$i++) {
							$disponibilite = new Disponibilite();	
							if (!empty(${'jour_'.$i}) && !empty(${'heure_debut_'.$i}) && !empty(${'heure_fin_'.$i})) {
								$disponibilite->setJour(${'jour_'.$i});
								$disponibilite->setHeureDebut(${'heure_debut_'.$i});
								$disponibilite->setHeureFin(${'heure_fin_'.$i});
								$eleve->addDisponibilite($disponibilite);
							}
							else {
								break;
							}
						}
						if ($session->get('matiere_maths') == 1) {
							$matiere = $this->getDoctrine()
											->getManager()
											->getRepository('MajordeskAppBundle:Matiere')
											->find(1);
							$eleve_matiere = new EleveMatiere();
							$eleve_matiere->setCours(1);
							$matiere->addEleveMatiere($eleve_matiere);
							$eleve->addEleveMatiere($eleve_matiere);
							$em->persist($matiere);
							$em->persist($eleve_matiere);
						} 
						if ($session->get('matiere_physique') == 1) {
							$matiere = $this->getDoctrine()
											->getManager()
											->getRepository('MajordeskAppBundle:Matiere')
											->find(2);
							$eleve_matiere = new EleveMatiere();
							$eleve_matiere->setCours(1);
							$matiere->addEleveMatiere($eleve_matiere);
							$eleve->addEleveMatiere($eleve_matiere);
							$em->persist($matiere);
							$em->persist($eleve_matiere);
						} 
						
						// Parent
						$client->setSalt(time());
							$encoder = $factory->getEncoder($client);
							$pass_famille = $encoder->encodePassword($client->getPassword(), $client->getSalt());
						$client->setPassword($pass_famille);
						$famille->addEleve($eleve);
						$famille->addClient($client);

						$em->persist($famille);
						$em->flush();
						
						$session->clear();						
						$session->set('etape_inscription', 3);
						return $this->redirect($this->generateUrl('majordesk_app_inscription', array('etape_inscription' => 3)));
					}
					$this->get('session')->getFlashBag()->add('warning-parents', 'Un ou plusieurs champs ont été mal remplis.');
				}
			}
			else {
				return $this->redirect($this->generateUrl('majordesk_app_profil_famille', array('etape_inscription' => 1)));
			}
		}
		else if ($etape_inscription == 3) {
		
			$request = $this->getRequest();
			if ($request->getMethod() == 'POST') 
			{
				$matiere_maths = $request->request->get('matiere_maths');
				$matiere_physique = $request->request->get('matiere_physique');

				if (!empty($matiere_maths) || !empty($matiere_physique)) {
				
					$pack = $request->request->get('pack');
					
					if (!empty($pack)) {
						$matieres = '';
						if (!empty($matiere_maths)) {
							$matieres = '1';
						}
						if (!empty($matiere_physique)) {
							if ($matieres == '') {
								$matieres = '2';
							} else {
								$matieres .= '##2';
							}
						}
						$session->set('matieres', $matieres);
						$session->set('pack', $pack);
						return $this->redirect($this->generateUrl('majordesk_app_inscription_paiement'));
					}
					$this->get('session')->getFlashBag()->add('warning-formule', 'Veuillez sélectionner un pack.');
					return $this->redirect($this->generateUrl('majordesk_app_inscription', array('etape_inscription' => 3)));
				}
				$this->get('session')->getFlashBag()->add('warning-matiere', 'Veuillez sélectionner au moins une matière.');
				return $this->redirect($this->generateUrl('majordesk_app_inscription', array('etape_inscription' => 3)));
			}
		
			return $this->render('MajordeskAppBundle:Home:inscription.html.twig', array(
				'etape_inscription' => $etape_inscription,
			));
		}
		
        return $this->render('MajordeskAppBundle:Home:inscription.html.twig', array(
			'etape_inscription' => $etape_inscription,
			'form' => $form->createView()
		));
	}
	
	public function cgvMajorclassAction()
    {
		$file_path = '/home/majorcla/cgv/cgv_majorclass.pdf';
		
		return new Response(file_get_contents($file_path), 200, array(
			'Content-Type' => 'application/pdf'
		));
    }
	
	public function cgvMajordeskAction()
    {
		$file_path = '/home/majorcla/cgv/cgv_majordesk.pdf';
		
		return new Response(file_get_contents($file_path), 200, array(
			'Content-Type' => 'application/pdf'
		));
    }
}
