<?php

namespace AC\LoginConvenienceBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{
    protected function configure()
    {
        $this
        ->setName('login-convenience:create-user')
        ->setDescription('Creates a user in the local app database')
        ->addArgument('email', InputArgument::REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getApplication()->getKernel()->getContainer();

        $userClass = $container->getParameter('ac_login_convenience.user_model_class');
        $user = new $userClass;
        $user->setEmail($input->getArgument('email'));

        $persistenceService = $container->getParameter('ac_login_convenience.db_persistence_service');
        $manager = $container->get($persistenceService)->getManager();
        $manager->persist($user);
        $manager->flush();
    }
}
