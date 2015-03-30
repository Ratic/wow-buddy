<?php
namespace XRealm\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use XRealm\Component\BlizzApi\BlizzApi;

class TestCommand extends ContainerAwareCommand
{
     protected function configure()
    {
        $this
            ->setName('xrealm:test')
            ->setDescription('Test implementation of XRealm Console Command');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text = 'Works, nice bro!';
        $output->writeln($text);
    }
}