<?php

namespace BuckPal\Tests\Account\Domain;

use BuckPal\Account\Domain\AccountId;
use BuckPal\Account\Domain\ActivityWindow;
use BuckPal\Account\Domain\Money;
use BuckPal\Tests\TestData\AccountTestData;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AccountTest extends KernelTestCase
{
  use AccountTestData;

  public function testCalculatesBalance()
  {
    $accountId = new AccountId(1);
    $account = $this->defaultAccount()
      ->withAccountId($accountId)
      ->withBaselineBalance(Money::of(555))
      ->withActivityWindow(new ActivityWindow([
        $this->defaultActivity()
          ->withTargetAccount($accountId)
          ->withMoney(Money::of(999))->build(),
        $this->defaultActivity()
          ->withTargetAccount($accountId)
          ->withMoney(Money::of(1))->build(),
      ]))->build();

    $balance = $account->calculateBalance();

    $this->assertEquals(Money::of(1555), $balance);
  }

  public function testWithdrawalSucceeds()
  {
    $accountId = new AccountId(1);
    $account = $this->defaultAccount()
      ->withAccountId($accountId)
      ->withBaselineBalance(Money::of(555))
      ->withActivityWindow(new ActivityWindow([
        $this->defaultActivity()
          ->withTargetAccount($accountId)
          ->withMoney(Money::of(999))->build(),
        $this->defaultActivity()
          ->withTargetAccount($accountId)
          ->withMoney(Money::of(1))->build(),
      ]))->build();

      $success = $account->withdraw(Money::of(555), new AccountId(99));

      $this->assertTrue($success);
      $this->assertCount(3, $account->getActivityWindow()->getActivities());
      $this->assertEquals(Money::of(1000), $account->calculateBalance());
  }

  public function testWithdrawalFailure() {
    $accountId = new AccountId(1);
    $account = $this->defaultAccount()
      ->withAccountId($accountId)
      ->withBaselineBalance(Money::of(555))
      ->withActivityWindow(new ActivityWindow([
        $this->defaultActivity()
          ->withTargetAccount($accountId)
          ->withMoney(Money::of(999))->build(),
        $this->defaultActivity()
          ->withTargetAccount($accountId)
          ->withMoney(Money::of(1))->build(),
      ]))->build();

      $success = $account->withdraw(Money::of(1556), new AccountId(99));

      $this->assertFalse($success);
      $this->assertCount(2, $account->getActivityWindow()->getActivities());
      $this->assertEquals(Money::of(1555), $account->calculateBalance());
  }

  public function testDepositSuccess() {
    $accountId = new AccountId(1);
    $account = $this->defaultAccount()
      ->withAccountId($accountId)
      ->withBaselineBalance(Money::of(555))
      ->withActivityWindow(new ActivityWindow([
        $this->defaultActivity()
          ->withTargetAccount($accountId)
          ->withMoney(Money::of(999))->build(),
        $this->defaultActivity()
          ->withTargetAccount($accountId)
          ->withMoney(Money::of(1))->build(),
      ]))->build();

      $success = $account->deposit(Money::of(445), new AccountId(99));

      $this->assertTrue($success);
      $this->assertCount(3, $account->getActivityWindow()->getActivities());
      $this->assertEquals(Money::of(2000), $account->calculateBalance());
  }
}
