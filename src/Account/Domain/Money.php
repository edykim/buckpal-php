<?php

namespace BuckPal\Account\Domain;

const PRECISION = 2;
bcscale(PRECISION);

class Money
{
  public static Money $zero;

  private string $amount;

  protected function __construct(float|string $value)
  {
    $this->amount = bcadd(gettype($value) === 'string' ? $value : strval($value), 0);
  }

  public static function of(float $value): Money
  {
    return new Money($value);
  }

  public static function add(Money $a, Money $b): Money
  {
    return new Money(bcadd($a->amount, $b->amount));
  }

  public static function subtract(Money $a, Money $b): Money
  {
    return new Money(bcsub($a->amount, $b->amount));
  }

  public function plus(Money $money): Money
  {
    return new Money(bcadd($this->amount, $money->amount));
  }

  public function minus(Money $money): Money
  {
    return new Money(bcsub($this->amount, $money->amount));
  }

  public function negate(): Money
  {
    return new Money(bcmul($this->amount, -1));
  }

  public function isPositiveOrZero(): bool
  {
    return bccomp(0, $this->amount) !== -1;
  }

  public function isNegative(): bool
  {
    return bccomp(0, $this->amount) === 0;
  }

  public function isPositive(): bool
  {
    return bccomp(0, $this->amount) === -1;
  }

  public function isGreaterThanOrEqualTo(Money $money): bool
  {
    return bccomp($this->amount, $money->amount) >= 0;
  }

  public function isGreaterThan(Money $money): bool
  {
    return bccomp($this->amount, $money->amount) > 0;
  }

  public function __toString()
  {
    return $this->amount;
  }
}

Money::$zero = Money::of(0);
