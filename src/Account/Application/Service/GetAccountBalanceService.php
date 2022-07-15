<?php

namespace BuckPal\Account\Application\Service;

use BuckPal\Account\Application\Port\In\GetAccountBalanceQuery;
use BuckPal\Account\Application\Port\Out\LoadAccountPort;
use BuckPal\Account\Domain\AccountId;
use BuckPal\Account\Domain\Money;
use DateTime;

class GetAccountBalanceService implements GetAccountBalanceQuery
{
  public function __construct(private LoadAccountPort $loadAccountPort)
  {
  }

  public function getAccountBalance(AccountId $accountId): Money
  {
    return $this->loadAccountPort
      ->loadAccount($accountId, new DateTime())
      ->calculateBalance();
  }
}
