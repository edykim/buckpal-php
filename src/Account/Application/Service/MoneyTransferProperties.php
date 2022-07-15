<?php

namespace BuckPal\Account\Application\Service;

use BuckPal\Account\Domain\Money;

class MoneyTransferProperties
{
  private Money $maximumTransferThreshold;

  public function __construct()
  {
    $this->maximumTransferThreshold = Money::of(1_000_000);
  }

  public function getMaximumTransferThreshold(): Money
  {
    return $this->maximumTransferThreshold;
  }
}
