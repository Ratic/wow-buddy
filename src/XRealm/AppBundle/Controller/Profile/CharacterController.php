<?php
namespace XRealm\AppBundle\Controller\Profile;

use XRealm\AppBundle\Controller\Controller as BaseController;

use XRealm\AppBundle\Form\Type\AddCharacterType;
use XRealm\AppBundle\Entity\BlizzCharacter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use XRealm\Component\BlizzCharacter\CharacterLoader;

class CharacterController extends BaseController
{
    public function indexAction(Request $request)
    {
        if(!$this->isGranted('ROLE_USER'))
        {
            return $this->redirect($this->generateUrl('login', array('_target_path' => $this->getRouteName())));
        }
		
        $character = new BlizzCharacter();
        $form = $this->createForm(new AddCharacterType(), $character, array(
            'action' => $this->generateUrl('profile_character_create'),
        ));
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $characterData = $this->get('blizz_api')->get('character', array(
                'character' => $character->getName(),
                'realm'     => $character->getRealm()->getSlug(),
                'fields'    => 'items,talents,progression,stats',
            ));
            $attempts = 3;
            while(!empty($characterData['status']) || !isset($characterData['lastModified']) && $attempts > 0)
            {
                 $characterData = $this->get('blizz_api')->get('character', array(
                    'character' => $character->getName(),
                    'realm'     => $character->getRealm()->getSlug(),
                    'fields'    => 'items,talents,progression,stats',
                ));
                 $attempts--;
            }
            
            $existingCharacter = $this->getRepository('XRealm\\AppBundle\\Entity\\BlizzCharacter')->findOneBy(array(
                'name'  => $character->getName(),
                'realm' => $character->getRealm(),
            ));
            
            if(!empty($existingCharacter))
            {
                $character = $existingCharacter;
            }
            
            if(!empty($characterData['status']) || !isset($characterData['lastModified']))
            {
                $form->get('name')->addError(new FormError('Dieser Character existiert nicht.'));
            }
            else if($character->getIsVerified())
            {
                $form->get('name')->addError(new FormError('Dieser Character wurde bereits einem Account zugewiesen.'));
            }
            else
            {
                $creator = new \XRealm\Component\BlizzCharacter\CharacterCreater();
                $creator->setUser($this->getUser());
                $creator->bindData($characterData, $character);
                
                
                
                $this->addFlash('success', $this->trans('message.character_added', array('%name%' => $character->getName())));
                $this->persist($character, true);
                return $this->redirect($this->generateUrl('profile_character_index'));
            }
        }
        return $this->render('XRealmAppBundle:Pages/Profile:characters.html.twig', array(
            'form'	=> $form->createView(),
        ));
    }

    public function updateAction()
    {
        $characters = $this->get('blizz_character')->getAll();
        if(!empty($characters)) {
            foreach($characters as $character)
            {
                $character->update();
            }
        }
        return $this->redirect($this->generateUrl('profile_character_index'));
    }

    public function updateCharacterAction(BlizzCharacter $character)
    {
        $charLoader = new CharacterLoader($character);
        $charLoader->setApi($this->get('blizz_api'));
        $charLoader->setEntityManager($this->getDoctrine()->getManager());
        $charLoader->update();

        return $this->redirect($this->generateUrl('data_character_show', array(
            'identifier'    => $character->getName() . '-' . $character->getRealm()->getSlug(),
        )));
    }

    public function verifyAction()
    {
        $characters = $this->get('blizz_character')->getAll();
        if(!empty($characters)) {
            foreach($characters as $character)
            {
                $character->verify();
            }
        }
        return $this->redirect($this->generateUrl('profile_character_index'));
    }
   
    
    public function selectAction(BlizzCharacter $character)
    {
        $chars = $this->get('blizz_character')->getAll();
        if(empty($chars))
        {
            return $this->redirect($this->generateUrl('profile_character_index'));
        }
        foreach ($chars as $char)
        {
            if($char->getData()->getIsSelected())
            {
                $char->getData()->setIsSelected(false);
                $this->persist($char->getData(), true);
            }

        }
        $character->setIsSelected(true);
        $this->persist($character, true);
        return $this->redirect($this->generateUrl('profile_character_index'));
    }
    
    public function changeRoleAction(BlizzCharacter $character, $role)
    {
        if($character->getUser()->getId() !== $this->getUser()->getId())
        {
            return $this->redirect($this->generateUrl('profile_character_index'));
        }
        $availableRoles = $character->getAvailableRoles();
        if(!in_array($role, $availableRoles))
        {
            return $this->redirect($this->generateUrl('profile_character_index'));
        }
        $character->setRole($role);
        $this->persist($character, true);
        
        return $this->redirect($this->generateUrl('profile_character_index'));
    }

    public function deleteAction(BlizzCharacter $character)
    {
        if($character->getUser()->getId() !== $this->getUser()->getId())
        {
            return $this->redirect($this->generateUrl('profile_character_index'));
        }
        $character->setIsVerified(0);
        $character->setUser(null);
        $character->setIsSelected(false);
        $this->persist($character, true);
        return $this->redirect($this->generateUrl('profile_character_index'));
    }
}
