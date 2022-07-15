<?php

namespace BuckPal\Account\Adapter\In\Console;

use BuckPal\Account\Application\Port\In\SendMoneyCommand;
use BuckPal\Account\Application\Port\In\SendMoneyUseCase;
use BuckPal\Account\Domain\AccountId;
use BuckPal\Account\Domain\Money;
use BuckPal\Common\ConsoleAdapter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[ConsoleAdapter]
#[AsCommand(name: 'buckpal:send', description: 'Send a certain amount of money from source to target')]
class SendCommand extends Command
{
  public function __construct(private SendMoneyUseCase $sendMoneyUseCase)
  {
    parent::__construct();
  }

  public function execute(InputInterface $input, OutputInterface $output): int
  {
    $sourceAccountId = $input->getArgument('source');
    $targetAccountId = $input->getArgument('target');
    $amount = $input->getArgument('amount');

    $command = new SendMoneyCommand(
      new AccountId($sourceAccountId),
      new AccountId($targetAccountId),
      Money::of($amount),
    );

    $this->sendMoneyUseCase->sendMoney($command);

    return Command::SUCCESS;
  }

  protected function configure(): void
  {
    $this
      ->addArgument('source', InputArgument::REQUIRED, 'Source Account ID')
      ->addArgument('target', InputArgument::REQUIRED, 'Target Account ID')
      ->addArgument('amount', InputArgument::REQUIRED, 'Amount of a transaction');
  }
}
