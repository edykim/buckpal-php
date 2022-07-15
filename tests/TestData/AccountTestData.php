<?php

namespace BuckPal\Tests\TestData;

use BuckPal\Account\Domain\AccountId;
use BuckPal\Account\Domain\ActivityWindow;
use BuckPal\Account\Domain\Money;

trait AccountTestData
{
  use ActivityTestData;

  public function defaultAccount(): AccountBuilder
  {

    return (new AccountBuilder)
      ->withAccountId(new AccountId(42))
      ->withBaselineBalance(Money::of(999))
      ->withActivityWindow(new ActivityWindow(
        [
          $this->defaultActivity()->build(),
          $this->defaultActivity()->build(),
        ]
      ));
  }
}
