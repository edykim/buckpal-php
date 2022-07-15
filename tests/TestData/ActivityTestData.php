<?php

namespace BuckPal\Tests\TestData;

use BuckPal\Account\Domain\AccountId;
use BuckPal\Account\Domain\Money;
use DateTime;

trait ActivityTestData
{
  public function defaultActivity(): ActivityBuilder {
		return (new ActivityBuilder)
				->withOwnerAccount(new AccountId(42))
				->withSourceAccount(new AccountId(42))
				->withTargetAccount(new AccountId(41))
				->withTimestamp(new DateTime())
				->withMoney(Money::of(999));
  }
}