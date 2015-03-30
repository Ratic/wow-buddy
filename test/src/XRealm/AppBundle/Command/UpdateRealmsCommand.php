<?php
namespace XRealm\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use XRealm\Component\BlizzApi\BlizzApi;

use XRealm\UserBundle\Entity\BlizzRealm;

class UpdateRealmsCommand extends ContainerAwareCommand
{
     protected function configure()
    {
        $this
            ->setName('xrealm:realm:update')
            ->setDescription('Update local Realm list.');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text = 'Start to update Realm list!';
		$blizzApi = $this->getContainer()->get('blizz_api');
		$em =  $this->getContainer()->get('doctrine')->getEntityManager();


		$realms = $blizzApi->get('realmStatus');

		if(empty($realms))
		{
			$output->writeln('Failed to call Api.');
		}
		else
		{
			foreach($realms['realms'] as $key => $row)
			{
				$row = (array) $row;
				$realm = $em->getRepository('XRealm\\UserBundle\\Entity\\BlizzRealm')->findOneBySlug($row['slug']);
				$action = 'Updated';
				if(empty($realm))
				{
					$realm = new BlizzRealm();
					$action = 'Created';
				}
				
				$realm->setType($row['type']);
				$realm->setName($row['name']);
				$realm->setLocale($row['locale']);
				$realm->setTimezone($row['timezone']);
				$realm->setSlug($row['slug']);
				$realm->setBattlegroup($row['battlegroup']);
				$realm->setName($row['name']);

				$em->persist($realm);
				$em->flush($realm);

				$output->writeln($action . ' Realm: ' . $realm->getName() . ' (' . $realm->getSlug() . ')');
			}
		}
		
        $output->writeln($text);
    }
}