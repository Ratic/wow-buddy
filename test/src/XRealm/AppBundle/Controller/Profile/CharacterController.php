<?php
namespace XRealm\AppBundle\Controller\Profile;

use XRealm\AppBundle\Controller\Controller as BaseController;

use XRealm\UserBundle\Form\Type\AddCharacterType;
use XRealm\UserBundle\Entity\BlizzCharacter;

class CharacterController extends BaseController
{
    public function indexAction()
    {
		if(!$this->isGranted('ROLE_USER'))
		{
			return $this->redirect($this->generateUrl('login', array('_target_path' => $this->getRouteName())));
		}
		
		$character = new BlizzCharacter();
		$form = $this->createForm(new AddCharacterType(), $character, array(
            'action' => $this->generateUrl('profile_character_create'),
        ));

		

        return $this->render('XRealmAppBundle:Pages/Profile:characters.html.twig', array(
			'form'	=> $form->createView(),
		));
    }
}
