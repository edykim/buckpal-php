<?php

namespace BuckPal\Account\Adapter\In\Console;

use BuckPal\Account\Application\Port\In\GetAccountBalanceQuery;
use BuckPal\Account\Domain\AccountId;
use BuckPal\Common\ConsoleAdapter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[ConsoleAdapter]
#[AsCommand(name: 'buckpal:balance', description: 'Check balance of a given account')]
class BalanceCommand extends Command
{
  public function __construct(private GetAccountBalanceQuery $getAccountbalanceQuery)
  {
    parent::__construct();
  }

  public function execute(InputInterface $input, OutputInterface $output): int
  {
    $id = $input->getArgument('id');

    $balance = $this->getAccountbalanceQuery->getAccountBalance(new AccountId($id));
    $output->writeln(["Balance: ", $balance]);

    return Command::SUCCESS;
  }

  protected function configure(): void
  {
    $this
      ->addArgument('id', InputArgument::REQUIRED, 'Account ID');
  }
}
