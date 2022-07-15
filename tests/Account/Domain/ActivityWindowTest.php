<?php

namespace BuckPal\Tests\Account\Domain;

use BuckPal\Account\Domain\AccountId;
use BuckPal\Account\Domain\ActivityWindow;
use BuckPal\Account\Domain\Money;
use BuckPal\Tests\TestData\ActivityTestData;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ActivityWindowTest extends KernelTestCase
{
  use ActivityTestData;

  public function testCalculatesStartTimestamp()
  {
    $window = new ActivityWindow([
      $this->defaultActivity()->withTimestamp($this->startDate())->build(),
      $this->defaultActivity()->withTimestamp($this->inBetweenDate())->build(),
      $this->defaultActivity()->withTimestamp($this->endDate())->build(),
    ]);

    $this->assertEquals($this->startDate(), $window->getStartTimestamp());
  }

  public function testCalculatesEndTimestamp()
  {
    $window = new ActivityWindow([
      $this->defaultActivity()->withTimestamp($this->startDate())->build(),
      $this->defaultActivity()->withTimestamp($this->inBetweenDate())->build(),
      $this->defaultActivity()->withTimestamp($this->endDate())->build(),
    ]);

    $this->assertEquals($this->endDate(), $window->getEndTimestamp());
  }

  public function testCalculatesBalance()
  {
    $account1 = new AccountId(1);
    $account2 = new AccountId(2);

    $window = new ActivityWindow([
      $this->defaultActivity()->withSourceAccount($account1)->withTargetAccount($account2)->withMoney(Money::of(999))->build(),
      $this->defaultActivity()->withSourceAccount($account1)->withTargetAccount($account2)->withMoney(Money::of(1))->build(),
      $this->defaultActivity()->withSourceAccount($account2)->withTargetAccount($account1)->withMoney(Money::of(500))->build(),
    ]);

    $this->assertEquals(Money::of(-500), $window->calculateBalance($account1));
    $this->assertEquals(Money::of(500), $window->calculateBalance($account2));
  }

  private function startDate(): DateTime
  {
    return new DateTime('2019-08-03 00:00');
  }
  private function inBetweenDate(): DateTime
  {
    return new DateTime('2019-08-04 00:00');
  }
  private function endDate(): DateTime
  {
    return new DateTime('2019-08-05 00:00');
  }
}
