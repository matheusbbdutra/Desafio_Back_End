<?php
declare(strict_types=1);

namespace App\Command;

use App\Infrastructure\Service\EmailConsumer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsumeMensageCommand extends Command
{

    private $emailConsumer;

    public function __construct(EmailConsumer $emailConsumer)
    {
        $this->emailConsumer = $emailConsumer;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:consume-message');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Starting to consume messages...');

        $this->emailConsumer->consume();

        $output->writeln('Finished consuming messages.');

        return Command::SUCCESS;
    }
}