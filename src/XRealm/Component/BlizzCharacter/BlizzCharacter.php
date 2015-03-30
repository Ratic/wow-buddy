<?php
namespace XRealm\Component\BlizzCharacter;

use XRealm\Component\BlizzCharacter\CharacterLoader;
use XRealm\Component\BlizzApi\BlizzApi;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityManager;
use XRealm\AppBundle\Entity\User;

class BlizzCharacter {
    private $context;
    private $characters;
    private $selectedCharacter;
    private $user;
    private $api;
    private $guestmode;
    private $entityManager;
   
    
    public function __construct(SecurityContext $context, BlizzApi $api, EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->api = $api;
        $this->context = $context;
        $token = $this->context->getToken();
        if($token === null)
        {
            $this->guestmode = true;
            return;
        }
        else
        {
            $this->guestmode = false;
        }
        
        $this->user = $token->getUser();
        if(!empty($this->user) && $this->user instanceof User)
        {
            $characters = $this->user->getBlizzCharacters();
            $this->buildCharacters($characters);
        }
        else 
        {
            $this->guestmode = true;
            return;
        }

    }
    
    public function getAll()
    {
        if($this->guestmode) return array();
        return $this->characters;
    }
    
    public function getSelected()
    {
        if($this->guestmode) return null;
        return $this->selectedCharacter;
    }
    
    protected function buildCharacters($characters)
    {
        if($this->guestmode) return false;
        foreach($characters as $character)
        {
            $charLoader = new CharacterLoader($character);
            $charLoader->setApi($this->api);
            $charLoader->setEntityManager($this->entityManager);
            $this->characters[] = $charLoader;
            if($character->getIsSelected())
            {
                $this->selectedCharacter = $charLoader;
            }
        }
    }
}