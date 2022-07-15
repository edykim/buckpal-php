<?php

namespace BuckPal\Account\Application\Port\Out;

use BuckPal\Account\Domain\Account;
use BuckPal\Account\Domain\AccountId;
use DateTime;

interface LoadAccountPort
{
  public function loadAccount(AccountId $accountId, DateTime $baselineDate): Account;
}
