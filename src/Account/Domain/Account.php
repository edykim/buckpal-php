<?php

namespace BuckPal\Account\Domain;

use DateTime;

class Account
{
  protected function __construct(
    private ?AccountId $id,
    private Money $baselineBalance,
    private ActivityWindow $activityWindow,
  ) {
  }

  public static function withoutId(
    Money $baselineBalance,
    ActivityWindow $activityWindow,
  ): static {
    return new static(null, $baselineBalance, $activityWindow);
  }

  public static function withId(
    AccountId $id,
    Money $baselineBalance,
    ActivityWindow $activityWindow,
  ): static {
    return new static($id, $baselineBalance, $activityWindow);
  }

  public function getId(): ?AccountId
  {
    return $this->id;
  }

  public function calculateBalance(): Money
  {
    return Money::add(
      $this->baselineBalance,
      $this->activityWindow->calculateBalance($this->id),
    );
  }

  public function withdraw(Money $money, AccountId $targetAccountid): bool
  {
    if ($this->mayWithdraw($money)) {
      return false;
    }

    $withdrawal = new Activity(
      $this->id,
      $this->id,
      $targetAccountid,
      new DateTime(),
      $money,
    );

    $this->activityWindow->addActivity($withdrawal);
    return true;
  }

  private function mayWithdraw(Money $money): bool
  {
    return Money::add(
      $this->calculateBalance(),
      $money->negate()
    )->isPositiveOrZero();
  }

  public function deposit(Money $money, AccountId $sourceAccountId)
  {
    $deposit = new Activity(
      $this->id,
      $sourceAccountId,
      $this->id,
      new DateTime(),
      $money
    );
    $this->activityWindow->addActivity($deposit);
    return true;
  }

  public function getActivityWindow(): ActivityWindow
  {
    return $this->activityWindow;
  }
}
