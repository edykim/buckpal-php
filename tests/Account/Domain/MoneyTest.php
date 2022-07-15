<?php

namespace BuckPal\Tests\Account\Domain;

use BuckPal\Account\Domain\Money;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MoneyTest extends KernelTestCase
{
  public function testSomething()
  {
    $aMoney = Money::of(1000);
    $bMoney = Money::of(10);
    $cMoney = Money::of(1000);
    $this->assertEquals(
      true,
      $aMoney->isGreaterThan($bMoney),
      '1000 is greater than 10'
    );
    $this->assertEquals(
      true,
      $aMoney->isGreaterThanOrEqualTo($cMoney),
      '1000 is greater than or equal to 1000'
    );
  }
}
