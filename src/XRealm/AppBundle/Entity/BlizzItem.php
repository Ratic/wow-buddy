<?php
// src/XRealm/AppBundle/Entity/User.php
namespace XRealm\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * XRealm\AppBundle\Entity\BlizzCharater
 *
 * @ORM\Table(name="blizz_item")
 * @ORM\Entity(repositoryClass="XRealm\AppBundle\Entity\Repository\ItemRepository")
 */
class BlizzItem
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $blizzId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $disenchantingSkillRank;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string", name="name_DE")
     */
    private $nameDe;

    /**
     * @ORM\Column(type="string", name="name_EN")
     */
    private $nameEn;

    /**
     * @ORM\Column(type="string", name="name_FR")
     */
    private $nameFr;

    /**
     * @ORM\Column(type="string", name="name_ES")
     */
    private $nameEs;

    /**
     * @ORM\Column(type="string", name="name_RU")
     */
    private $nameRu;

    /**
     * @ORM\Column(type="string", name="name_PT")
     */
    private $namePt;

    /**
     * @ORM\Column(type="string", name="name_IT")
     */
    private $nameIt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true, name="description_DE")
     */
    private $descriptionDe;

    /**
     * @ORM\Column(type="text", nullable=true, name="description_EN")
     */
    private $descriptionEn;

    /**
     * @ORM\Column(type="text", nullable=true, name="description_ES")
     */
    private $descriptionEs;

    /**
     * @ORM\Column(type="text", nullable=true, name="description_FR")
     */
    private $descriptionFr;

    /**
     * @ORM\Column(type="text", nullable=true, name="description_RU")
     */
    private $descriptionRu;

    /**
     * @ORM\Column(type="text", nullable=true, name="description_PT")
     */
    private $descriptionPt;

    /**
     * @ORM\Column(type="text", nullable=true, name="description_IT")
     */
    private $descriptionIt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $icon;

    /**
     * @ORM\Column(type="boolean")
     */
    private $stackable;

    /**
     * @ORM\Column(type="string", nullable=true))
     */
    private $allowableClasses;

    /**
     * @ORM\Column(type="boolean")
     */
    private $itemBind;

    /**
     * @ORM\Column(type="text")
     */
    private $bonusStats;

    /**
     * @ORM\Column(type="text")
     */
    private $itemSpells;

    /**
     * @ORM\Column(type="integer")
     */
    private $buyPrice;

    /**
     * @ORM\Column(type="integer", length=3)
     */
    private $itemClass;

    /**
     * @ORM\Column(type="integer", length=3)
     */
    private $itemSubClass;

    /**
     * @ORM\Column(type="integer", length=3)
     */
    private $containerSlots;

    /**
     * @ORM\Column(type="integer", length=3)
     */
    private $inventoryType;

    /**
     * @ORM\Column(type="boolean")
     */
    private $equippable;

    /**
     * @ORM\Column(type="integer", length=5)
     */
    private $itemLevel;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true))
     */
    private $itemSet;

    /**
     * @ORM\Column(type="integer", length=5)
     */
    private $maxCount;

    /**
     * @ORM\Column(type="integer", length=5)
     */
    private $maxDurability;

    /**
     * @ORM\Column(type="integer", length=10)
     */
    private $minFactionId;

    /**
     * @ORM\Column(type="integer")
     */
    private $minReputation;

    /**
     * @ORM\Column(type="integer", length=5)
     */
    private $quality;

    /**
     * @ORM\Column(type="integer")
     */
    private $sellPrice;

    /**
     * @ORM\Column(type="integer")
     */
    private $requiredSkill;

    /**
     * @ORM\Column(type="integer")
     */
    private $requiredLevel;

    /**
     * @ORM\Column(type="integer")
     */
    private $requiredSkillRank;

    /**
     * @ORM\Column(type="text")
     */
    private $itemSource;

    /**
     * @ORM\Column(type="integer")
     */
    private $baseArmor;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasSockets;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAuctionable;

    /**
     * @ORM\Column(type="integer")
     */
    private $armor;

    /**
     * @ORM\Column(type="integer")
     */
    private $displayInfoId;

    /**
     * @ORM\Column(type="string")
     */
    private $nameDescription;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $nameDescriptionColor;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $upgradeable;

    /**
     * @ORM\Column(type="string")
     */
    private $heroicTooltip;

    /**
     * @ORM\Column(type="string")
     */
    private $context;

    /**
     * @ORM\Column(type="text")
     */
    private $bonusLists;

    /**
     * @ORM\Column(type="text")
     */
    private $availableContexts;

    /**
     * @ORM\Column(type="text")
     */
    private $bonusSummary;




    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set blizzId
     *
     * @param integer $blizzId
     * @return BlizzItem
     */
    public function setBlizzId($blizzId)
    {
        $this->blizzId = $blizzId;

        return $this;
    }

    /**
     * Get blizzId
     *
     * @return integer 
     */
    public function getBlizzId()
    {
        return $this->blizzId;
    }

    /**
     * Set disenchantingSkillRank
     *
     * @param integer $disenchantingSkillRank
     * @return BlizzItem
     */
    public function setDisenchantingSkillRank($disenchantingSkillRank)
    {
        $this->disenchantingSkillRank = $disenchantingSkillRank;

        return $this;
    }

    /**
     * Get disenchantingSkillRank
     *
     * @return integer 
     */
    public function getDisenchantingSkillRank()
    {
        return $this->disenchantingSkillRank;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return BlizzItem
     */
    public function setDescription($description, $locale = false)
    {
        if($locale)
        {
            $prop = 'description' . ucfirst($locale);
            $this->$prop = $description;
            return $this;
        }
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription($locale = false)
    {
        if($locale)
        {
            $prop = 'description' . ucfirst($locale);
            if(!empty($this->$prop))
            {
                return $this->$prop;
            }
        }
        return $this->description;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return BlizzItem
     */
    public function setName($name, $locale = false)
    {
        if($locale)
        {
            $prop = 'name' . ucfirst($locale);
            $this->$prop = $name;
            return $this;
        }
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName($locale = false)
    {
        if($locale)
        {
            $prop = 'name' . ucfirst($locale);
            if(!empty($this->$prop))
            {
                return $this->$prop;
            }
        }
        return $this->name;
    }

    /**
     * Set icon
     *
     * @param string $icon
     * @return BlizzItem
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string 
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set stackable
     *
     * @param boolean $stackable
     * @return BlizzItem
     */
    public function setStackable($stackable)
    {
        $this->stackable = $stackable;

        return $this;
    }

    /**
     * Get stackable
     *
     * @return boolean 
     */
    public function getStackable()
    {
        return $this->stackable;
    }

    /**
     * Set allowableClasses
     *
     * @param string $allowableClasses
     * @return BlizzItem
     */
    public function setAllowableClasses($allowableClasses)
    {
        $this->allowableClasses = $allowableClasses;

        return $this;
    }

    /**
     * Get allowableClasses
     *
     * @return string 
     */
    public function getAllowableClasses()
    {
        return $this->allowableClasses;
    }

    /**
     * Set itemBind
     *
     * @param boolean $itemBind
     * @return BlizzItem
     */
    public function setItemBind($itemBind)
    {
        $this->itemBind = $itemBind;

        return $this;
    }

    /**
     * Get itemBind
     *
     * @return boolean 
     */
    public function getItemBind()
    {
        return $this->itemBind;
    }

    /**
     * Set bonusStats
     *
     * @param string $bonusStats
     * @return BlizzItem
     */
    public function setBonusStats($bonusStats)
    {
        $this->bonusStats = $bonusStats;

        return $this;
    }

    /**
     * Get bonusStats
     *
     * @return string 
     */
    public function getBonusStats()
    {
        $stats = json_decode($this->bonusStats,true);
        uasort($stats, function ($a, $b) {
            if ($a['stat'] == $b['stat']) {
                return 0;
            }
            if($a['stat'] == 73)
            {
                return -1;
            }
            if($b['stat'] == 73)
            {
                return 1;
            }
            return ($a['stat'] < $b['stat']) ? -1 : 1;
        });
        return $stats;
    }

    /**
     * Set itemSpells
     *
     * @param string $itemSpells
     * @return BlizzItem
     */
    public function setItemSpells($itemSpells)
    {
        $this->itemSpells = $itemSpells;

        return $this;
    }

    /**
     * Get itemSpells
     *
     * @return string 
     */
    public function getItemSpells()
    {
        return $this->itemSpells;
    }

    /**
     * Set buyPrice
     *
     * @param integer $buyPrice
     * @return BlizzItem
     */
    public function setBuyPrice($buyPrice)
    {
        $this->buyPrice = $buyPrice;

        return $this;
    }

    /**
     * Get buyPrice
     *
     * @return integer 
     */
    public function getBuyPrice()
    {
        return $this->buyPrice;
    }

    /**
     * Set itemClass
     *
     * @param integer $itemClass
     * @return BlizzItem
     */
    public function setItemClass($itemClass)
    {
        $this->itemClass = $itemClass;

        return $this;
    }

    /**
     * Get itemClass
     *
     * @return integer 
     */
    public function getItemClass()
    {
        return $this->itemClass;
    }

    /**
     * Set itemSubClass
     *
     * @param integer $itemSubClass
     * @return BlizzItem
     */
    public function setItemSubClass($itemSubClass)
    {
        $this->itemSubClass = $itemSubClass;

        return $this;
    }

    /**
     * Get itemSubClass
     *
     * @return integer 
     */
    public function getItemSubClass()
    {
        return $this->itemSubClass;
    }

    /**
     * Set containerSlots
     *
     * @param integer $containerSlots
     * @return BlizzItem
     */
    public function setContainerSlots($containerSlots)
    {
        $this->containerSlots = $containerSlots;

        return $this;
    }

    /**
     * Get containerSlots
     *
     * @return integer 
     */
    public function getContainerSlots()
    {
        return $this->containerSlots;
    }

    /**
     * Set inventoryType
     *
     * @param integer $inventoryType
     * @return BlizzItem
     */
    public function setInventoryType($inventoryType)
    {
        $this->inventoryType = $inventoryType;

        return $this;
    }

    /**
     * Get inventoryType
     *
     * @return integer 
     */
    public function getInventoryType()
    {
        return $this->inventoryType;
    }

    /**
     * Set equippable
     *
     * @param boolean $equippable
     * @return BlizzItem
     */
    public function setEquippable($equippable)
    {
        $this->equippable = $equippable;

        return $this;
    }

    /**
     * Get equippable
     *
     * @return boolean 
     */
    public function getEquippable()
    {
        return $this->equippable;
    }

    /**
     * Set itemLevel
     *
     * @param integer $itemLevel
     * @return BlizzItem
     */
    public function setItemLevel($itemLevel)
    {
        $this->itemLevel = $itemLevel;

        return $this;
    }

    /**
     * Get itemLevel
     *
     * @return integer 
     */
    public function getItemLevel()
    {
        return $this->itemLevel;
    }

    /**
     * Set itemSet
     *
     * @param integer $itemSet
     * @return BlizzItem
     */
    public function setItemSet($itemSet)
    {
        $this->itemSet = $itemSet;

        return $this;
    }

    /**
     * Get itemSet
     *
     * @return integer 
     */
    public function getItemSet()
    {
        return $this->itemSet;
    }

    /**
     * Set maxCount
     *
     * @param integer $maxCount
     * @return BlizzItem
     */
    public function setMaxCount($maxCount)
    {
        $this->maxCount = $maxCount;

        return $this;
    }

    /**
     * Get maxCount
     *
     * @return integer 
     */
    public function getMaxCount()
    {
        return $this->maxCount;
    }

    /**
     * Set maxDurability
     *
     * @param integer $maxDurability
     * @return BlizzItem
     */
    public function setMaxDurability($maxDurability)
    {
        $this->maxDurability = $maxDurability;

        return $this;
    }

    /**
     * Get maxDurability
     *
     * @return integer 
     */
    public function getMaxDurability()
    {
        return $this->maxDurability;
    }

    /**
     * Set minFactionId
     *
     * @param integer $minFactionId
     * @return BlizzItem
     */
    public function setMinFactionId($minFactionId)
    {
        $this->minFactionId = $minFactionId;

        return $this;
    }

    /**
     * Get minFactionId
     *
     * @return integer 
     */
    public function getMinFactionId()
    {
        return $this->minFactionId;
    }

    /**
     * Set minReputation
     *
     * @param integer $minReputation
     * @return BlizzItem
     */
    public function setMinReputation($minReputation)
    {
        $this->minReputation = $minReputation;

        return $this;
    }

    /**
     * Get minReputation
     *
     * @return integer 
     */
    public function getMinReputation()
    {
        return $this->minReputation;
    }

    /**
     * Set quality
     *
     * @param integer $quality
     * @return BlizzItem
     */
    public function setQuality($quality)
    {
        $this->quality = $quality;

        return $this;
    }

    /**
     * Get quality
     *
     * @return integer 
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * Set sellPrice
     *
     * @param integer $sellPrice
     * @return BlizzItem
     */
    public function setSellPrice($sellPrice)
    {
        $this->sellPrice = $sellPrice;

        return $this;
    }

    /**
     * Get sellPrice
     *
     * @return integer 
     */
    public function getSellPrice()
    {
        return $this->sellPrice;
    }

    /**
     * Set requiredSkill
     *
     * @param integer $requiredSkill
     * @return BlizzItem
     */
    public function setRequiredSkill($requiredSkill)
    {
        $this->requiredSkill = $requiredSkill;

        return $this;
    }

    /**
     * Get requiredSkill
     *
     * @return integer 
     */
    public function getRequiredSkill()
    {
        return $this->requiredSkill;
    }

    /**
     * Set requiredLevel
     *
     * @param integer $requiredLevel
     * @return BlizzItem
     */
    public function setRequiredLevel($requiredLevel)
    {
        $this->requiredLevel = $requiredLevel;

        return $this;
    }

    /**
     * Get requiredLevel
     *
     * @return integer 
     */
    public function getRequiredLevel()
    {
        return $this->requiredLevel;
    }

    /**
     * Set requiredSkillRank
     *
     * @param integer $requiredSkillRank
     * @return BlizzItem
     */
    public function setRequiredSkillRank($requiredSkillRank)
    {
        $this->requiredSkillRank = $requiredSkillRank;

        return $this;
    }

    /**
     * Get requiredSkillRank
     *
     * @return integer 
     */
    public function getRequiredSkillRank()
    {
        return $this->requiredSkillRank;
    }

    /**
     * Set itemSource
     *
     * @param string $itemSource
     * @return BlizzItem
     */
    public function setItemSource($itemSource)
    {
        $this->itemSource = $itemSource;

        return $this;
    }

    /**
     * Get itemSource
     *
     * @return string 
     */
    public function getItemSource()
    {
        return $this->itemSource;
    }

    /**
     * Set baseArmor
     *
     * @param integer $baseArmor
     * @return BlizzItem
     */
    public function setBaseArmor($baseArmor)
    {
        $this->baseArmor = $baseArmor;

        return $this;
    }

    /**
     * Get baseArmor
     *
     * @return integer 
     */
    public function getBaseArmor()
    {
        return $this->baseArmor;
    }

    /**
     * Set hasSockets
     *
     * @param boolean $hasSockets
     * @return BlizzItem
     */
    public function setHasSockets($hasSockets)
    {
        $this->hasSockets = $hasSockets;

        return $this;
    }

    /**
     * Get hasSockets
     *
     * @return boolean 
     */
    public function getHasSockets()
    {
        return $this->hasSockets;
    }

    /**
     * Set isAuctionable
     *
     * @param boolean $isAuctionable
     * @return BlizzItem
     */
    public function setIsAuctionable($isAuctionable)
    {
        $this->isAuctionable = $isAuctionable;

        return $this;
    }

    /**
     * Get isAuctionable
     *
     * @return boolean 
     */
    public function getIsAuctionable()
    {
        return $this->isAuctionable;
    }

    /**
     * Set armor
     *
     * @param integer $armor
     * @return BlizzItem
     */
    public function setArmor($armor)
    {
        $this->armor = $armor;

        return $this;
    }

    /**
     * Get armor
     *
     * @return integer 
     */
    public function getArmor()
    {
        return $this->armor;
    }

    /**
     * Set displayInfoId
     *
     * @param integer $displayInfoId
     * @return BlizzItem
     */
    public function setDisplayInfoId($displayInfoId)
    {
        $this->displayInfoId = $displayInfoId;

        return $this;
    }

    /**
     * Get displayInfoId
     *
     * @return integer 
     */
    public function getDisplayInfoId()
    {
        return $this->displayInfoId;
    }

    /**
     * Set nameDescription
     *
     * @param string $nameDescription
     * @return BlizzItem
     */
    public function setNameDescription($nameDescription)
    {
        $this->nameDescription = $nameDescription;

        return $this;
    }

    /**
     * Get nameDescription
     *
     * @return string 
     */
    public function getNameDescription()
    {
        return $this->nameDescription;
    }

    /**
     * Set nameDescriptionColor
     *
     * @param string $nameDescriptionColor
     * @return BlizzItem
     */
    public function setNameDescriptionColor($nameDescriptionColor)
    {
        $this->nameDescriptionColor = $nameDescriptionColor;

        return $this;
    }

    /**
     * Get nameDescriptionColor
     *
     * @return string 
     */
    public function getNameDescriptionColor()
    {
        return $this->nameDescriptionColor;
    }

    /**
     * Set upgradeable
     *
     * @param boolean $upgradeable
     * @return BlizzItem
     */
    public function setUpgradeable($upgradeable)
    {
        $this->upgradeable = $upgradeable;

        return $this;
    }

    /**
     * Get upgradeable
     *
     * @return boolean 
     */
    public function getUpgradeable()
    {
        return $this->upgradeable;
    }

    /**
     * Set heroicTooltip
     *
     * @param string $heroicTooltip
     * @return BlizzItem
     */
    public function setHeroicTooltip($heroicTooltip)
    {
        $this->heroicTooltip = $heroicTooltip;

        return $this;
    }

    /**
     * Get heroicTooltip
     *
     * @return string 
     */
    public function getHeroicTooltip()
    {
        return $this->heroicTooltip;
    }

    /**
     * Set context
     *
     * @param string $context
     * @return BlizzItem
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context
     *
     * @return string 
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set bonusLists
     *
     * @param string $bonusLists
     * @return BlizzItem
     */
    public function setBonusLists($bonusLists)
    {
        $this->bonusLists = $bonusLists;

        return $this;
    }

    /**
     * Get bonusLists
     *
     * @return string 
     */
    public function getBonusLists()
    {
        return $this->bonusLists;
    }

    /**
     * Set availableContexts
     *
     * @param string $availableContexts
     * @return BlizzItem
     */
    public function setAvailableContexts($availableContexts)
    {
        $this->availableContexts = $availableContexts;

        return $this;
    }

    /**
     * Get availableContexts
     *
     * @return string 
     */
    public function getAvailableContexts()
    {
        return $this->availableContexts;
    }

    /**
     * Set bonusSummary
     *
     * @param string $bonusSummary
     * @return BlizzItem
     */
    public function setBonusSummary($bonusSummary)
    {
        $this->bonusSummary = $bonusSummary;

        return $this;
    }

    /**
     * Get bonusSummary
     *
     * @return string 
     */
    public function getBonusSummary()
    {
        return json_decode($this->bonusSummary, true);
    }
}
