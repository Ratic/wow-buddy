<?php
namespace XRealm\AppBundle\Controller\Data;

use XRealm\AppBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;

class ItemController extends BaseController
{
    protected $contexthirachy = array(
        'raid-normal'           => 100,
        'raid-heroic'           => 90,
        'raid-mythic'           => 80,
        'raid-finder'           => 72,
        'dungeon-level-up-1'    => 50,
        'dungeon-level-up-2'    => 60,
        'dungeon-level-up-3'    => 70,
        'dungeon-level-up-4'    => 65,
        'dungeon-normal'        => 71,
        'dungeon-heroic'        => 66,
    );

    public function showAction(Request $request, $id, $context = false)
    {

        $tooltip = (boolean) $request->get('tooltip', false);
        $itemlist = $this->getRepository('XRealmAppBundle:BlizzItem')->findOneByIdent($id, false);
        if($itemlist === null)
        {
            throw $this->createNotFoundException('The item does not exist');
        }
        if($context)
        {
            $this->contexthirachy[$context] = 999;
        }
        $item = $this->prepareItemByList($itemlist);
        
        $recentItems = array();
        $recentItems = $this->pushRecentItems($recentItems, $itemlist, $item);


        $template = $tooltip ? 'XRealmAppBundle:Pages/Data:itemData.html.twig' : 'XRealmAppBundle:Pages/Data:item.html.twig';
        return $this->render($template, array(
            'item'   => $item,
            'tooltip'    => $tooltip,
            'recentItems'   => $recentItems,
        ));
    }

    protected function prepareItemByList($itemlist)
    {

        uasort($itemlist, array($this, 'contextSort'));
        return array_values($itemlist)[0];
    }

    public function contextSort($first, $second)
    {
        $a = (empty($this->contexthirachy[$first->getContext()]) ? 0 : $this->contexthirachy[$first->getContext()]);
        $b = (empty($this->contexthirachy[$second->getContext()]) ? 0 : $this->contexthirachy[$second->getContext()]);
        if($a == $b)
        {
            return 0;
        }

        return $a < $b ? 1 : -1;
    }

    protected function pushRecentItems($base, $includes, $item)
    {

        foreach($includes as $row)
        {
            if($row->getId() != $item->getId())
            {
                $base[$row->getId()] = $row;
            }
            
        }
        return $base;
    }

}