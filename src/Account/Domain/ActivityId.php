<?php

namespace BuckPal\Account\Domain;

class ActivityId
{
  public function __construct(private int $value)
  {
  }

  public function getValue(): int
  {
    return $this->value;
  }
}
