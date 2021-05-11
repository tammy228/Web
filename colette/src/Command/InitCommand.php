<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class InitCommand extends Command
{
    protected static $defaultName = 'app:init';

    protected function configure()
    {
        $this
            ->setDescription('Initial Doctrine Commands')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        exec("php bin/console d:d:d --force");
        exec("php bin/console d:d:c");
        exec("php bin/console make:migration -q");
        exec("php bin/console d:m:m");
        exec("php bin/console d:f:l -q");


        $io = new SymfonyStyle($input, $output);

        $io->success('You have saved roughly 10 sec. XDDDDD');

        return 0;
    }
}
