<?php

namespace BuckPal\Account\Application\Service;

use BuckPal\Account\Application\Port\In\SendMoneyCommand;
use BuckPal\Account\Application\Port\In\SendMoneyUseCase;
use BuckPal\Account\Application\Port\Out\AccountLock;
use BuckPal\Account\Application\Port\Out\LoadAccountPort;
use BuckPal\Account\Application\Port\Out\UpdateAccountStatePort;
use BuckPal\Common\UseCase;
use DateTime;
use RuntimeException;

#[UseCase]
class SendMoneyService implements SendMoneyUseCase
{
  public function __construct(
    private LoadAccountPort $loadAccountPort,
    private AccountLock $accountLock,
    private UpdateAccountStatePort $updateAccountStatePort,
    private MoneyTransferProperties $moneyTransferProperties,
  ) {
  }

  public function sendMoney(SendMoneyCommand $command): bool
  {
    $this->checkThreshold($command);

    $baselineDate = new DateTime();
    $baselineDate->modify("-10 day");

    $sourceAccount =  $this->loadAccountPort->loadAccount($command->getSourceAccountId(), $baselineDate);
    $targetAccount =  $this->loadAccountPort->loadAccount($command->getTargetAccountId(), $baselineDate);

    $sourceAccountId = $sourceAccount->getId() ?: throw new RuntimeException("expected source account ID not to be empty");
    $targetAccountId = $targetAccount->getId() ?: throw new RuntimeException("expected target account ID not to be empty");

    $this->accountLock->lockAccount($sourceAccountId);
    if (!$sourceAccount->withdraw($command->getMoney(), $targetAccountId)) {
      $this->accountLock->releaseAccount($sourceAccountId);
      return false;
    }

    $this->accountLock->lockAccount($targetAccountId);
    if (!$targetAccount->deposit($command->getMoney(), $sourceAccountId)) {
      $this->accountLock->releaseAccount($sourceAccountId);
      $this->accountLock->releaseAccount($targetAccountId);
      return false;
    }

    $this->updateAccountStatePort->updateActivities($sourceAccount);
    $this->updateAccountStatePort->updateActivities($targetAccount);

    $this->accountLock->releaseAccount($sourceAccountId);
    $this->accountLock->releaseAccount($targetAccountId);

    return true;
  }

  private function checkThreshold(SendMoneyCommand $command)
  {
    if ($command->getMoney()
      ->isGreaterThan(
        $this->moneyTransferProperties->getMaximumTransferThreshold()
      )
    ) {
      throw new ThresholdExceededException(
        $this->moneyTransferProperties->getMaximumTransferThreshold(),
        $command->getMoney(),
      );
    }
  }
}
