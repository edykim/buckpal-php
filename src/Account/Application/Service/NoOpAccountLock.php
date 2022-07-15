<?php

namespace BuckPal\Account\Application\Service;

use BuckPal\Account\Application\Port\Out\AccountLock;
use BuckPal\Account\Domain\AccountId;

class NoOpAccountLock implements AccountLock
{
  public function lockAccount(AccountId $accountId): void
  {
    // do nothing
  }
  public function releaseAccount(AccountId $accountId): void
  {
    // do nothing
  }
}
