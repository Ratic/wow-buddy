<?php
namespace XRealm\Component\BlizzCharacter;

use XRealm\AppBundle\Entity\BlizzCharacter as Character;

class CharacterExporter {
    protected $entityManager;
    protected $characters;
    
    protected $data;
    
    public function __construct($entityManager) {
        $this->characters = array();
        $this->entityManager = $entityManager;
    }
    
    public function addCharacter(Character $character)
    {
        $this->characters[$character->getId()] = $character;
    }
    
    public function loadData()
    {
        if(!is_array($this->data))
        {
            $this->data = array();
        }
        
        foreach($this->characters as $character)
        {
            $ref = $character->getName() . ' - ' . $character->getRealm()->getName();
            $this->data[$ref] = array(
                'character'    => array(),
                'event'        => $this->bindEvents($character),
                'group'        => $this->bindGroups($character),
                
            );
            $this->data[$ref] = array_merge($this->data[$ref], array(
                'character'    => $this->bindCharacters(array_keys($this->data[$ref]['group'])),
                'event_character' => $this->bindEventMembers(array_keys($this->data[$ref]['event'])),

                'group_character' => $this->bindGroupMembers(array_keys($this->data[$ref]['group'])),
            ));
        }
    }
    
    protected function bindGroups(Character $character)
    {
        return $this->entityManager->getRepository('XRealmAppBundle:Group')->getAllowedExportGroups($character);
    }
    protected function bindEvents(Character $character)
    {
        return $this->entityManager->getRepository('XRealmAppBundle:Event')->getAllowedExportEvents($character);
    }
    protected function bindGroupMembers($groupIds)
    {
        return $this->entityManager->getRepository('XRealmAppBundle:GroupInvolvedCharacter')->getAllowedExportMembers($groupIds);
    }
    protected function bindEventMembers($eventIds)
    {
        return $this->entityManager->getRepository('XRealmAppBundle:EventInvolvedCharacter')->getAllowedExportMembers($eventIds);
    }
    protected function bindCharacters($groupIds)
    {
        return $this->entityManager->getRepository('XRealmAppBundle:User')->getAllowedCharacters($groupIds);
    }
    
    public function export($base64 = false)
    {
        $return = '';
        
        foreach($this->data as $key => $value)
        {
            $return .= $this->handleExport($key, $value);
        }
        $return = '{' . $return . '}';
        if($base64)
        {
            return base64_encode($return);
        }
        return $return;
    }
    
    protected function handleExport($key, $value)
    {
        $return = $this->parseKey($key) . '=';
        $return .= $this->parseValue($value);
        $return .= ',';
        return $return;
    }
    
    protected function parseKey($key)
    {
        $return = '[';
        if(is_string($key))
        {
            $return .= '"' . $key . '"';
        }
        else
        {
            $return .= $key;
        }
        return $return . ']';
    }
    protected function parseValue($value)
    {
        $return = '';
        if(is_array($value))
        {
            $return .= '{';
            foreach($value as $subkey => $subvalue)
            {
                $return .= $this->handleExport($subkey, $subvalue);
            }
            
            $return .= '}';
        }
        else if(is_string($value))
        {
            $return .= '"';
            $return .= $value;
            $return .= '"';
        }
        else if($value instanceof \DateTime)
        {
            $return .= '"';
            $return .= $value->format('Y-m-d H:i:s');
            $return .= '"';
        }
        else
        {
            $return += $value;
        }
        return $return;
    }
}
