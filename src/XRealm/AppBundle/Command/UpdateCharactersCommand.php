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

use XRealm\AppBundle\Entity\BlizzRealm;

class UpdateCharactersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('xrealm:character:update')
            ->setDescription('Update all Characters (Carfull use with many cahracters).');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text = 'Start to update Character list!';
		$blizzApi = $this->getContainer()->get('blizz_api');
		$em =  $this->getContainer()->get('doctrine')->getEntityManager();

        $characters = $em->getRepository('XRealmAppBundle:BlizzCharacter')->findAll();
        $output->writeln('Updating Characters...');

        $progress = $this->getHelper('progress');
        $progress->start($output,count($characters));
        foreach($characters as $character)
        {
            $charLoader = new CharacterLoader($character);
            $charLoader->setApi($blizzApi);
            $charLoader->setEntityManager($em);
            $charLoader->update();
            $progress->advance();
        }
        $progress->finish();
        $output->writeln('Done!');
    }
}