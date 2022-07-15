<?php

namespace BuckPal\Account\Adapter\In\Console;

use BuckPal\Account\Application\Port\In\SendMoneyCommand;
use BuckPal\Account\Application\Port\In\SendMoneyUseCase;
use BuckPal\Account\Domain\AccountId;
use BuckPal\Account\Domain\Money;
use BuckPal\Tests\Helpers\CommandTestTrait;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class SendCommandTest extends KernelTestCase
{
  private MockObject $mock;
  private CommandTester $commandTester;

  use CommandTestTrait;

  protected function setUp(): void
  {
    [$this->commandTester, [$this->mock]] = $this->createCommandTest(SendCommand::class, [SendMoneyUseCase::class]);
  }

  public function testSendCommand()
  {
    $this->mock
      ->expects($this->once())
      ->method('sendMoney')
      ->with(
        new SendMoneyCommand(
          new AccountId(41),
          new AccountId(42),
          Money::of(500),
        )
      );

    $this->commandTester->execute([
      'source' => 41,
      'target' => 42,
      'amount' => 500,
    ]);
  }
}
