<?php
namespace XRealm\Component\BlizzCharacter;

use XRealm\AppBundle\Entity\BlizzCharacter;

class CharacterCreater {
    protected $user = null;
    
    public function setUser($user)
    {
        $this->user = $user;
    }
    protected function getUser()
    {
        return $this->user;
    }
    
    public function bindData($characterData, BlizzCharacter $character)
    {
        if(!empty($characterData))
        { 
           
            $character->setAchievmentPoints($characterData['achievementPoints']);
            $character->setGender($characterData['gender']);
            $selected = 0;
            if($this->user)
            {
                $selected = (count($this->getUser()->getBlizzCharacters()) > 0 ? false : true);
            }
            $character->setIsSelected($selected);
            $character->setIsVerified(false);
            $character->setLevel($characterData['level']);
            $character->setName($characterData['name']);
            $character->setRace($characterData['race']);
            $character->setClass($characterData['class']);
            $character->setThumbnail('https://eu.battle.net/static-render/eu/' . $characterData['thumbnail']);
            $character->setUser($this->getUser());
            if(!empty($characterData['stats']))
                $character->setStats($characterData['stats']);
            if(!empty($characterData['talents']))
                $character->setTalents($characterData['talents']);
            $character->setItems($characterData['items']);
            $character->setProgression($characterData['progression']);
            $character->setVerifyItems($this->generateVerifyToken());

            $character->setRole(BlizzCharacter::ROLE_RANGE);

            $talents = (array) $characterData['talents'];
            $melees = array(1,2,4,6,10);
            $ranges = array(3,5,8,9);
            $hybrit = array(7,11);


            foreach($talents as $tree)
            {
                $tree = (array) $tree;
                if(!empty($tree['selected']))
                {
                    $role = (array) $tree['spec'];
                    $role = $role['role'];
                    switch ($role)
                    {
                        case "DPS":
                            if(in_array($characterData['class'], $melees))
                            {
                                $character->setRole(BlizzCharacter::ROLE_MELEE);
                            }
                            else if(in_array($characterData['class'], $ranges))
                            {
                                $character->setRole(BlizzCharacter::ROLE_RANGE);
                            }
                            else if(in_array($characterData['class'], $hybrit))
                            {
                                $character->setRole($this->getRoleForHybrit((array) $tree['spec']));
                            }
                            break;
                        case "HEALING":
                            $character->setRole(BlizzCharacter::ROLE_HEAL);
                            break;
                        case "TANK":
                            $character->setRole(BlizzCharacter::ROLE_TANK);
                            break;
                    }

                }
            }
        }
        else
        {
            return false;
        }
    }
    public function getRoleForHybrit($specdata)
    {
        switch($specdata['name'])
        {
            case 'Enhancement':
                return BlizzCharacter::ROLE_MELEE;
            case 'Elementar':
                return BlizzCharacter::ROLE_RANGE;
            case 'Feral':
                return BlizzCharacter::ROLE_MELEE;
            case 'Balance':
                return BlizzCharacter::ROLE_RANGE;
        }
        return BlizzCharacter::ROLE_RANGE;
    }
    
    
    protected function generateVerifyToken()
    {
        $parts = array();
        $requiredParts = array(
            "head",
            "back",
            "chest",
            "wrist",
            "hands",
            "waist",
            "legs",
            "feet",

        );
        $optionalParts = array(
            "finger1",
            "finger2",
            "trinket1",
            "trinket2",
            "neck",
            "shoulder",
        );
        
        $sum = rand(5, 6);
        $required = 3;
        $optional = $sum - $required;
        $keylist = array_rand($requiredParts, $required);
        foreach($keylist as $key)
        {
            $parts[] = $requiredParts[$key];
        }
        $keylist = array_rand($optionalParts, $optional);
        foreach($keylist as $key)
        {
            $parts[] = $optionalParts[$key];
        }
        
        return $parts;
    }
    
}