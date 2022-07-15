<?php

namespace BuckPal\Account\Adapter\In\Console;

use BuckPal\Account\Application\Service\GetAccountBalanceService;
use BuckPal\Account\Domain\AccountId;
use BuckPal\Tests\Helpers\CommandTestTrait;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class BalanceCommandTest extends KernelTestCase
{
  private MockObject $mock;
  private CommandTester $commandTester;

  use CommandTestTrait;

  protected function setUp(): void
  {
    [$this->commandTester, [$this->mock]] = $this->createCommandTest(BalanceCommand::class, [GetAccountBalanceService::class]);
  }

  public function testBalanceCommand()
  {
    $this->mock
      ->expects($this->once())
      ->method('getAccountBalance')
      ->with(new AccountId(1001));

    $this->commandTester->execute([
      'id' => 1001,
    ]);
  }
}
