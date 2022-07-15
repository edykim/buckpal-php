<?php

namespace BuckPal\Tests\TestData;

use BuckPal\Account\Domain\Account;
use BuckPal\Account\Domain\AccountId;
use BuckPal\Account\Domain\ActivityWindow;
use BuckPal\Account\Domain\Money;

class AccountBuilder
{
  private AccountId $accountId;
  private Money $baselineBalance;
  private ActivityWindow $activityWindow;

  public function withAccountId(AccountId $accountId): static
  {
    $this->accountId = $accountId;
    return $this;
  }

  public function withBaselineBalance(Money $baselineBalance): static
  {
    $this->baselineBalance = $baselineBalance;
    return $this;
  }

  public function withActivityWindow(ActivityWindow $activityWindow): static
  {
    $this->activityWindow = $activityWindow;
    return $this;
  }

  public function build(): Account
  {
    return Account::withId(
      $this->accountId,
      $this->baselineBalance,
      $this->activityWindow,
    );
  }
}
