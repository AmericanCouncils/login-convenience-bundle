<?php

namespace AC\LoginConvenienceBundle\Command;

use AC\LoginConvenienceBundle\Entity\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{
    protected function configure()
    {
        $this
        ->setName('user:create')
        ->setDescription('Creates a user in the local app database')
        ->addArgument('email', InputArgument::REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getApplication()->getKernel()->getContainer();

        $user = new User;
        $user->setEmail($input->getArgument('email'));

        $em = $container->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();
    }
}
