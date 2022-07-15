<?php

namespace BuckPal\Account\Application\Port\Out;

use BuckPal\Account\Domain\Account;

interface UpdateAccountStatePort
{
  public function updateActivities(Account $account): void;
}
