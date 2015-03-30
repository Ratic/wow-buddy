<?php
namespace XRealm\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use XRealm\AppBundle\Entity\Group;

class Controller extends BaseController {
    protected function addFlash($type, $message)
    {
        $request = $this->container->get('request');
        $request->getSession()->getFlashBag()->add($type,$message);
    }
    protected function trans($string, $params)
    {
       return $this->get('translator')->trans($string, $params);
    }
    protected function getRepository($object)
    {
		$em = $this->getDoctrine()->getManager();
        return $em->getRepository($object);
    }
    protected function isGranted($role)
    {
        return $this->get('security.context')->isGranted($role);
    }
    protected function persist($object, $flush = false)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($object);
        if($flush)
        {
                $em->flush($object);
        }
        return true;
    }
    protected function remove($object, $flush = false)
    {
		$em = $this->getDoctrine()->getManager();
        $em->remove($object);
        if($flush)
        {
            $em->flush($object);
        }
        return true;
    }
    protected function flush($object = null)
    {
		$em = $this->getDoctrine()->getManager();
        return $em->flush($object);
    }
    protected function getRouteName()
    {
            $request = $this->container->get('request');
            return $request->get('_route');
    }
    
    protected function returnGroupPage(Group $group, $page = false, $hash = false)
    {
        if($page)
        {
            return $this->redirect($this->generateUrl('groups_show_' . $page, array('slug' => $group->getSlug())) . (empty($hash) ? '' : ('#' . $hash)));
        }
        return $this->redirect($this->generateUrl('groups_show', array('slug' => $group->getSlug())) . (empty($hash) ? '' : ('#' . $hash)));
    }
}
