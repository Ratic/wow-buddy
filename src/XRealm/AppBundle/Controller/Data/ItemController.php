<?php
namespace XRealm\AppBundle\Controller\Data;

use XRealm\AppBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;

class ItemController extends BaseController
{
   public function showAction(Request $request, $id, $context = false)
   {
       $tooltip = (boolean) $request->get('tooltip', false);
       $item = $this->getRepository('XRealmAppBundle:BlizzItem')->findOneByIdent($id, $context);
       if($item === null)
       {
           throw $this->createNotFoundException('The item does not exist');
       }
       $template = $tooltip ? 'XRealmAppBundle:Pages/Data:itemData.html.twig' : 'XRealmAppBundle:Pages/Data:item.html.twig';
       return $this->render($template, array(
           'item'   => $item,
           'tooltip'    => $tooltip,
       ));
   }
}