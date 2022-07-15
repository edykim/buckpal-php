<?php

namespace BuckPal\Account\Application\Port\In;

use BuckPal\Account\Domain\AccountId;
use BuckPal\Account\Domain\Money;

class SendMoneyCommand
{
  public function __construct(
    private AccountId $sourceAccountId,
    private AccountId $targetAccountId,
    private Money $money,
  ) {
    // @TODO input validation
  }

  public function getMoney(): Money
  {
    return $this->money;
  }

  public function getSourceAccountId(): AccountId
  {
    return $this->sourceAccountId;
  }

  public function getTargetAccountId(): AccountId
  {
    return $this->targetAccountId;
  }
}
