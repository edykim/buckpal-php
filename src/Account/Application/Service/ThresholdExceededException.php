<?php

namespace BuckPal\Account\Application\Service;

use BuckPal\Account\Domain\Money;
use Exception;

class ThresholdExceededException extends Exception
{
  public function __construct(Money $threshold, Money $actual)
  {
    parent::__construct(sprintf("Maximum threshold for transferring money exceeded: tried to transfer %s but threshold is %s!", $actual, $threshold));
  }
}
