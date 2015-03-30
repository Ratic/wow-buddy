<?php
namespace XRealm\Component\BlizzCharacter;

use XRealm\AppBundle\Entity\BlizzCharacter as Entity;
use XRealm\Component\BlizzApi\BlizzApi;
use Doctrine\ORM\EntityManager;

class CharacterLoader {
    protected $entity;
    protected $api;
    protected $entityManager;
    protected $existingRaids;
    
    public function __construct(Entity $character) {
        $this->entity = $character;
    }
    
    public function getData()
    {
        return $this->entity;
    }
    
    public function setApi(BlizzApi $api)
    {
        $this->api = $api;
        
        return $this;
    }
    
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        
        return $this;
    }
    
    public function update()
    {
        
        $characterData = $this->api->get('character', array(
            'character' => $this->entity->getName(),
            'realm'     => $this->entity->getRealm()->getSlug(),
            'fields'    => 'items, progression,stats,talents',
        ));


        if(!empty($characterData['status']) && !empty($characterData['reason']))
        {
            return $this;
        }
        if(empty($characterData))
        {
            return $this;
        }
        if(!empty($characterData['achievementPoints']))
        {
            $this->entity->setAchievmentPoints($characterData['achievementPoints']);
        }
        if(!empty($characterData['gender']))
            $this->entity->setGender($characterData['gender']);
        if(!empty($characterData['level']))
            $this->entity->setLevel($characterData['level']);
        if(!empty($characterData['name']))
            $this->entity->setName($characterData['name']);
        if(!empty($characterData['race']))
            $this->entity->setRace($characterData['race']);
        if(!empty($characterData['thumbnail']))
             $this->entity->setThumbnail('https://eu.battle.net/static-render/eu/' . $characterData['thumbnail']);
        if(!empty($characterData['items']))
            $this->entity->setItems($characterData['items']);
        if(!empty($characterData['stats']))
            $this->entity->setStats($characterData['stats']);
        if(!empty($characterData['talents']))
            $this->entity->setTalents($characterData['talents']);
        if(!empty($this->mergeProgression($characterData['progression'])))
            $this->entity->setProgression($this->mergeProgression ($characterData['progression']));
        if(!empty($characterData['class']))
            $this->entity->setClass($characterData['class']);
               
        
        $this->entityManager->persist($this->entity);
        $this->entityManager->flush($this->entity);
        
        return $this;
    }

    protected function mergeProgression($progress)
    {
        if(empty($this->existingRaids))
        {
            $existingRaids = $this->entityManager->getRepository('XRealmAppBundle:Raid')->findAll();
            $this->existingRaids = array();
            foreach($existingRaids as $r)
            {
                $this->existingRaids[] = $r->getBlizzId();
            }
        }
        
        
        $return = array();
        if($progress instanceof \stdClass)
        {
            $progress = (array) $progress;
        }
        if($progress['raids'] instanceof \stdClass)
        {
            $progress['raids'] = (array) $progress['raids'];
        }
        foreach($progress['raids'] as $raid)
        {
            if($raid instanceof \stdClass)
            {
                $raid = (array) $raid;
            }
            if(in_array($raid['id'], $this->existingRaids))
            {
                $return[$raid['id']] = $raid;
            }
        }
        return $return;
    }
    
    public function verify()
    {
        $this->update();
        $verifyItems = $this->entity->getVerifyItems();
        $items = $this->entity->getItems();
        $passed = true;
        foreach($verifyItems as $item)
        {
            if(!empty($items[$item]))
            {
                $passed = false;
                break;
            }
        }
        if(count($items) <= 4)
        {
            $passed = false;
        }
        
        if($passed)
        {
            $this->entity->setIsVerified(true);
            $this->entityManager->persist($this->entity);
            $this->entityManager->flush($this->entity);
        }
        return (boolean) $passed;
        
    }
}