<?php
namespace XRealm\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use XRealm\Component\BlizzApi\BlizzApi;
use XRealm\Component\BlizzCharacter\CharacterLoader;
use Symfony\Component\Console\Helper\ProgressBar;
use XRealm\AppBundle\Entity\BlizzItem;
use XRealm\AppBundle\Entity\BlizzRealm;

class ImportItemsCommand extends ContainerAwareCommand
{
    protected $locales = array(
        'en' => 'en_GB',
        'es' => 'es_ES',
        'fr' => 'fr_FR',
        'ru' => 'ru_RU',
        'de' => 'de_DE',
        'pt' => 'pt_PT',
        'it' => 'it_IT',
    );

    protected function configure()
    {
        $this
            ->setName('xrealm:items:import')
            ->addArgument(
                'from',
                InputArgument::REQUIRED,
                'Start at Item Id?'
            )
            ->addArgument(
                'to',
                InputArgument::REQUIRED,
                'End at Item Id?'
            )
            ->setDescription('Import all Items, carfull! api call for every item.');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text = 'Start to import Items!';
		$blizzApi = $this->getContainer()->get('blizz_api');
		$em =  $this->getContainer()->get('doctrine')->getEntityManager();
        $from =  (int) $input->getArgument('from');
        $to =  (int) $input->getArgument('to');
        $count = $to - $from;
        
        if($to - $from < 0)
        {
            $output->writeln('Error!');
            return;

        }

        $output->writeln('Importing Items');

        $progress = $this->getHelper('progress');
        $progress->start($output,$count);
        while($from <= $to)
        {
            $result = $blizzApi->get('item', array(
                'item' => $from,
            ));
            if((!empty($result['status']) && $result['status'] != 'ok'))
            {
                $progress->advance();
                $from++;
                continue;
               
            }
            else
            {
                $progress->advance();
                if(!empty($result['availableContexts']) && count($result['availableContexts']) > 0 && !empty($result['availableContexts'][0]))
                {
                    
                    foreach($result['availableContexts'] as $context)
                    {
                        $result = $blizzApi->get('itemcontext', array(
                            'item' => $from,
                            'context'  => $context,
                        ));
                        if(!empty($result['status']) && $result['status'] != 'ok')
                        {
                            echo 'empty context';
                            continue;
                        }
                        $this->handleItemData($em, $result, $blizzApi, $context);
                    }
                    
                }
                else
                {
                    $result = $blizzApi->get('item', array(
                        'item' => $from,
                    ));
                    $this->handleItemData($em, $result, $blizzApi);

                }
                $from++;
            }
            
            
        }
        $progress->finish();
        $output->writeln('Done!');
    }
    protected function handleItemData($em, $data, $blizzApi, $context = false)
    {
        if(empty($data['id']))
        {
            echo 'id missing';
            return;
        }
        $data['blizzId'] = $data['id'];
        unset($data['id']);
        if($context)
        {
            $existing = $em->getRepository('XRealmAppBundle:BlizzItem')->findOneBy(array(
                'blizzId' => $data['blizzId'],
                'context'   => $context,
            ));
        }
        else
        {
            $existing = $em->getRepository('XRealmAppBundle:BlizzItem')->findOneBy(array(
                'blizzId' => $data['blizzId'],
            ));
        }


        if(!empty($existing))
        {
            $entity = $existing;
        }
        else
        {
            $entity = new BlizzItem();
        }
        foreach($data as $key => $value)
        {
            $method = 'set' . ucfirst($key);

            if(method_exists($entity, $method))
            {
                if(is_array($value) || $value instanceof \stdClass)
                {
                    $value = (array) $value;
                    if(!empty($value['id'])) {
                        $value = $value['id'];
                    }
                    else
                    {
                        $value = json_encode($value);
                    }

                    
                }
                $entity->$method($value);

            }

        }
        $this->localizeItem($entity, $data['blizzId'], $blizzApi, $context);
        $em->persist($entity);
        $em->flush($entity);

    }

    protected function localizeItem($entity, $id, $blizzApi, $context)
    {
        foreach($this->locales as $locale => $query)
        {
            if($context)
            {
                $result = $blizzApi->get('itemcontext', array(
                    'item'      => $id,
                    'context'   => $context,
                    'locale'    => $query,
                ));
            }
            else
            {
                $result = $blizzApi->get('item', array(
                    'item'      => $id,
                    'locale'    => $query,
                ));
            }
            $entity->setName($result['name'], $locale);
            
            $entity->setDescription($result['description'], $locale);
        }

    }
}