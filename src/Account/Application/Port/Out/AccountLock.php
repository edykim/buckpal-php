<?php

namespace BuckPal\Account\Application\Port\Out;

use BuckPal\Account\Domain\AccountId;

interface AccountLock
{
  public function lockAccount(AccountId $accountId): void;
  public function releaseAccount(AccountId $accountId): void;
}
