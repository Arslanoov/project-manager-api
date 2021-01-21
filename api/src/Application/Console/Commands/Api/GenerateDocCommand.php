<?php

declare(strict_types=1);

namespace App\Console\Commands\Api;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class GenerateDocCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('api:generate:docs')
            ->setDescription('Generates OpenAPI docs');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $actions = 'src/App/Http/Action';
        $swagger = 'vendor/bin/openapi';
        $to = './public/docs/openapi.json';

        passthru("$swagger --output $to $actions");

        $output->writeln('<info>Done!</info>');

        return 0;
    }
}
