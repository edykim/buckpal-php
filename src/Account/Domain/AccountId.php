<?php

namespace BuckPal\Account\Domain;

class AccountId
{
  public function __construct(private int $value)
  {
  }

  public function getValue(): int
  {
    return $this->value;
  }
}
