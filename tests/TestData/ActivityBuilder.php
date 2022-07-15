<?php

namespace BuckPal\Tests\TestData;

use BuckPal\Account\Domain\AccountId;
use BuckPal\Account\Domain\ActivityId;
use BuckPal\Account\Domain\Activity;
use BuckPal\Account\Domain\Money;
use DateTime;

class ActivityBuilder
{
  private ActivityId $id;
  private AccountId $ownerAccountId;
  private AccountId $sourceAccountId;
  private AccountId $targetAccountId;
  private DateTime $timestamp;
  private Money $money;

  public function withId(ActivityId $id): static
  {
    $this->id = $id;
    return $this;
  }

  public function withOwnerAccount(AccountId $accountId): static
  {
    $this->ownerAccountId = $accountId;
    return $this;
  }

  public function withSourceAccount(AccountId $accountId): static
  {
    $this->sourceAccountId = $accountId;
    return $this;
  }

  public function withTargetAccount(AccountId $accountId): static
  {
    $this->targetAccountId = $accountId;
    return $this;
  }

  public function withTimestamp(DateTime $timestamp): static
  {
    $this->timestamp = $timestamp;
    return $this;
  }

  public function withMoney(Money $money): static
  {
    $this->money = $money;
    return $this;
  }

  public function build(): Activity
  {
    return new Activity(
      $this->ownerAccountId,
      $this->sourceAccountId,
      $this->targetAccountId,
      $this->timestamp,
      $this->money,
    );
  }
}
