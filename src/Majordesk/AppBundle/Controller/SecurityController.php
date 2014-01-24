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
		  'last_username' => $session->get(SecurityContext::LAST_USERNAME),
		  'error'         => $error,
		));
    }
	
	public function forgotPasswordAction()
    {
		$request = $this->getRequest();
		$session = $request->getSession();
		
		
		$defaultData = array('email' => $session->get(SecurityContext::LAST_USERNAME));
		$form = $this->createFormBuilder($defaultData)
			->add('email', 'email', array(
				'attr' => array('class'=>'form-control input-lg')
			))
			->getForm();

		$form->handleRequest($request);

		// if ($request->getMethod() == 'POST') 
		// {	
			// $form->bind($request);

			if ($form->isValid()) 
			{
				$data = $form->getData();
				$mail = $data['email'];
				$encrypted_mail = urlencode(base64_encode($mail));
				
				// throw new \Exception($encrypted_mail.' et '.base64_decode(urldecode($encrypted_mail)));
				
				$message = \Swift_Message::newInstance()
						->setSubject('Majorclass - Mot de passe oublié')
						->setFrom('ne-pas-repondre@majorclass.fr')
						->setTo($mail)
						->setBody($this->renderView('MajordeskAppBundle:Template:oubli-mdp.html.twig', array('encrypted_mail'=>$encrypted_mail)), 'text/html')
					;
					$this->get('mailer')->send($message);
				
				$session->getFlashBag()->add('reset-password-alert', ' Bienvenue !');
			}
		// }
		
		return $this->render('MajordeskAppBundle:Security:forgot-password.html.twig', array(
			'form' => $form->createView()
		));
    }
	
	public function resetPasswordAction($encrypted_mail)
    {
		$request = $this->getRequest();
		$session = $request->getSession();	
		
		$mail = base64_decode(urldecode($encrypted_mail));
		
		$defaultData = array('password' => '');
		$form = $this->createFormBuilder($defaultData)
			->add('password'            , 'repeated', array(
				'type' => 'password',
				'invalid_message' => 'Les mots de passe doivent correspondre',
				'first_name' => 'pass',
				'second_name' => 'confirm',
				'options' => array('always_empty' => false, 'attr' => array('class' => 'form-control input-lg')),
				'first_options'  => array('label' => ' '),
				'second_options' => array('label' => 'Confirmation')
				))
			->getForm();

		// $form->handleRequest($request);

		if ($request->getMethod() == 'POST') 
		{	
			$form->bind($request);

			if ($form->isValid()) 
			{
				$factory = $this->get('security.encoder_factory');
				
				$data = $form->getData();
				$password = $data['password'];
			
				$user = $this->getDoctrine()
							 ->getManager()
							 ->getRepository('MajordeskAppBundle:Client')
							 ->findBy(array('mail'=>$mail));
				
				if (empty($user)) {
					$user = $this->getDoctrine()
								 ->getManager()
								 ->getRepository('MajordeskAppBundle:Eleve')
								 ->findBy(array('mail'=>$mail));
				}
				
				if (empty($user)) {
					$user = $this->getDoctrine()
								 ->getManager()
								 ->getRepository('MajordeskAppBundle:Professeur')
								 ->findBy(array('mail'=>$mail));
				}
				
				if (empty($user)) {
					$session->getFlashBag()->add('reset-password-impossible', ' Un problème est survenu !');
				} else {
					$user = $user[0];
					$user->setSalt(time());
						$encoder = $factory->getEncoder($user);
						$new_password = $encoder->encodePassword($password, $user->getSalt());
					$user->setPassword($new_password);
					
					$em = $this->getDoctrine()->getManager();
					$em->persist($user);
					$em->flush();
					
					$session->getFlashBag()->add('reset-password-alert', ' Votre nouveau mot de passe a bien été enregistré');
					
					return $this->redirect($this->generateUrl('login'));
				}
				
			} else {
				$session->getFlashBag()->add('reset-password-alert', ' Un problème est survenu !');
			}
		}
			
		return $this->render('MajordeskAppBundle:Security:reset-password.html.twig', array(
			'form' => $form->createView(),
			'encrypted_mail' => $encrypted_mail,
			'mail' => $mail
		));
    }
}