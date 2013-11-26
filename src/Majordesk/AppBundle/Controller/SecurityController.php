<?php
 
namespace Majordesk\AppBundle\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

use Majordesk\AppBundle\Entity\Eleve;
use Majordesk\AppBundle\Entity\Famille;
use Majordesk\AppBundle\Entity\Client;
use Majordesk\AppBundle\Entity\Disponibilite;
use Majordesk\AppBundle\Entity\Paiement;
 
class SecurityController extends Controller
{
    public function loginAction()
    {
		$request = $this->getRequest();
		$session = $request->getSession();
		
		// Si le visiteur est déjà identifié, on le redirige vers l'accueil
		if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
		  return $this->redirect($this->generateUrl('majordesk_app_index'));
		}
		
		// On vérifie s'il y a des erreurs d'une précédente soumission du formulaire
		if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
		  $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
		} else {
		  $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
		  $session->remove(SecurityContext::AUTHENTICATION_ERROR);
		}
	 
		return $this->render('MajordeskAppBundle:Security:login.html.twig', array(
		  // Valeur du précédent nom d'utilisateur rentré par l'internaute
		  'last_username' => $session->get(SecurityContext::LAST_USERNAME),
		  'error'         => $error,
		));
    }
}