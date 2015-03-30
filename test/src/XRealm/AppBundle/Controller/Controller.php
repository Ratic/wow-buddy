<?php
namespace XRealm\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

class Controller extends BaseController {


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
        return $em->remove($object, $flush);
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
}