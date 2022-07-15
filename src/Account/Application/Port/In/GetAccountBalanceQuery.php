<?php
namespace BuckPal\Account\Application\Port\In;

use BuckPal\Account\Domain\AccountId;
use BuckPal\Account\Domain\Money;

interface GetAccountBalanceQuery
{
  public function getAccountBalance(AccountId $accountId): Money;
}
