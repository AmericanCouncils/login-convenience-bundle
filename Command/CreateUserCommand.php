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
        ->addArgument('identity', InputArgument::OPTIONAL)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getApplication()->getKernel()->getContainer();
        $dbService = $container->getParameter(
            'ac_login_convenience.db_persistence_service'
        );
        $objMan = $container->get($dbService)->getManager();

        $userClass = $container->getParameter(
            'ac_login_convenience.user_model_class'
        );
        $user = new $userClass;
        $user->setEmail($input->getArgument('email'));
        $objMan->persist($user);
        $objMan->flush();

        if ($identityUrl = $input->getArgument('identity')) {
            $userMan = $container->get('ac_login_convenience.openid_user_manager');
            $userMan->associateIdentityWithUser($identityUrl, $user);
        }
    }
}
