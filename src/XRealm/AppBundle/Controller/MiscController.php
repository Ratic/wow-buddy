<?php
namespace XRealm\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use XRealm\Component\BlizzCharacter\CharacterExporter;

use XRealm\AppBundle\Controller\Controller as BaseController;

class MiscController extends BaseController
{
    public function imprintAction()
    {
        return $this->render('XRealmAppBundle:Pages:imprint.html.twig');
    }
    public function contactAction()
    {
        return $this->render('XRealmAppBundle:Pages:contact.html.twig');
    }
    public function teamAction()
    {
        return $this->render('XRealmAppBundle:Pages:team.html.twig');
    }
    public function supportUsAction()
    {
        return $this->render('XRealmAppBundle:Pages:supportUs.html.twig');
    }
    public function secureUrlAction(Request $request)
    {
        $url = $request->get('url', false);
        return $this->render('XRealmAppBundle:Pages:redirect.html.twig', array(
            'url'   => $url,
        ));
    }
    public function downloadsAction(Request $request)
    {
        return $this->render('XRealmAppBundle:Pages:downloads.html.twig', array(
        ));
    }
    public function exportAction(Request $request)
    {
        if(!$this->get('blizz_character')->getSelected())
        {
            return $this->redirect($this->generateUrl('downloads'));
        }
        $exporter = new CharacterExporter($this->getDoctrine()->getManager());
        $exporter->addCharacter($this->get('blizz_character')->getSelected()->getData());
        $exporter->loadData();
        
        return $this->render('XRealmAppBundle:Pages:export.html.twig', array(
            'exporter'  => $exporter,
        ));
    }
    
    public function testAction(Request $request)
    {
        if($this->get('blizz_character')->getSelected())
        {
            $character = $this->get('blizz_character')->getSelected()->getData();
            $events = $this->getRepository('XRealmAppBundle:Event')->findDetailedEvents($character);
        }
        return $this->render('XRealmAppBundle:Pages:test.html.twig', array(
        ));
    }
}
