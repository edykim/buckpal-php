<?php

namespace BuckPal\Account\Application\Service;

use BuckPal\Account\Application\Port\In\SendMoneyCommand;
use BuckPal\Account\Application\Port\Out\AccountLock;
use BuckPal\Account\Application\Port\Out\LoadAccountPort;
use BuckPal\Account\Application\Port\Out\UpdateAccountStatePort;
use BuckPal\Account\Domain\Account;
use BuckPal\Account\Domain\AccountId;
use BuckPal\Account\Domain\Money;
use Mockery;
use Mockery\MockInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SendMoneyServiceTest extends KernelTestCase
{
  protected MockInterface|LoadAccountPort $loadAccountPort;
  protected MockInterface|AccountLock $accountLock;
  protected MockInterface|UpdateAccountStatePort $updateAccountStatePort;

  protected SendMoneyService $sendMoneyService;

  protected function setUp(): void
  {
    parent::setUp();

    /** @var MockInterface|LoadAccountPort */
    $this->loadAccountPort = Mockery::mock(LoadAccountPort::class);

    /** @var MockInterface|AccountLock */
    $this->accountLock = Mockery::mock(AccountLock::class);

    /** @var MockInterface|UpdateAccountStatePort */
    $this->updateAccountStatePort = Mockery::mock(UpdateAccountStatePort::class);

    $this->sendMoneyService = new SendMoneyService(
      $this->loadAccountPort,
      $this->accountLock,
      $this->updateAccountStatePort,
      $this->moneyTransferProperties(),
    );
  }

  public function test_givenWithdrawalFails_thenOnlySourceAccountIsLockedAndReleased()
  {
    $sourceAccountId = new AccountId(41);
    $sourceAccount = $this->givenAnAccountWithId($sourceAccountId);

    $targetAccountId = new AccountId(42);
    $targetAccount = $this->givenAnAccountWithId($targetAccountId);

    $this->givenWithdrawalWillFail($sourceAccount);
    $this->givenDepositWillSucceed($targetAccount);

    $command = new SendMoneyCommand(
      $sourceAccountId,
      $targetAccountId,
      Money::of(300),
    );

    $this->accountLock->shouldReceive('lockAccount');
    $this->accountLock->shouldReceive('releaseAccount');

    $success = $this->sendMoneyService->sendMoney($command);

    $this->assertFalse($success);
    $this->accountLock->shouldHaveReceived('lockAccount', [$sourceAccountId])->once();
    $this->accountLock->shouldHaveReceived('releaseAccount', [$sourceAccountId])->once();
    $this->accountLock->shouldNotHaveReceived('lockAccount', [$targetAccountId]);
  }

  public function testTransaactionSucceeds()
  {
    $sourceAccount = $this->givenSourceAccount();
    $targetAccount = $this->givenTargetAccount();

    $this->givenWithdrawalWillSuccess($sourceAccount);
    $this->givenDepositWillSucceed($targetAccount);

    $money = Money::of(500);


    $command = new SendMoneyCommand(
      $sourceAccount->getId(),
      $targetAccount->getId(),
      $money,
    );

    $this->accountLock->shouldReceive('lockAccount');
    $this->accountLock->shouldReceive('releaseAccount');
    $sourceAccount->shouldReceive('withdraw');
    $targetAccount->shouldReceive('deposit');
    $this->updateAccountStatePort->shouldReceive('updateActivities');

    $success = $this->sendMoneyService->sendMoney($command);

    $sourceAccountId = $sourceAccount->getId();
    $targetAccountId = $targetAccount->getId();

    $this->assertTrue($success);
    $this->accountLock->shouldHaveReceived('lockAccount', [$sourceAccountId]);
    $sourceAccount->shouldHaveReceived('withdraw', [$money, $targetAccountId]);
    $this->accountLock->shouldHaveReceived('releaseAccount', [$sourceAccountId]);

    $this->accountLock->shouldHaveReceived('lockAccount', [$targetAccountId]);
    $targetAccount->shouldHaveReceived('deposit', [$money, $sourceAccountId]);
    $this->accountLock->shouldHaveReceived('releaseAccount', [$targetAccountId]);

    $this->thenAccountsHaveBeenUpdated($sourceAccount, $targetAccount);
  }

  private function thenAccountsHaveBeenUpdated(Account ...$accounts) {
    $this->updateAccountStatePort->shouldHaveReceived('updateActivities')->times(count($accounts));

    foreach($accounts as $account) {
      $this->updateAccountStatePort->shouldHaveReceived('updateActivities', [$account]);
    }

  }

  private function givenDepositWillSucceed(MockInterface|Account $account): void
  {
    /** @var MockInterface|Account */
    $account->shouldReceive('deposit')->andReturn(true);
  }

  private function givenWithdrawalWillFail(MockInterface|Account $account): void
  {
    /** @var MockInterface|Account */
    $account->shouldReceive('withdraw')->andReturn(false);
  }

  private function givenWithdrawalWillSuccess(MockInterface|Account $account): void
  {
    /** @var MockInterface|Account */
    $account->shouldReceive('withdraw')->andReturn(true);
  }

  private function givenTargetAccount(): MockInterface|Account
  {
    return $this->givenAnAccountWithId(new AccountId(42));
  }

  private function givenSourceAccount(): MockInterface|Account
  {
    return $this->givenAnAccountWithId(new AccountId(41));
  }

  private function givenAnAccountWithId(AccountId $id): MockInterface|Account
  {
    /** @var MockInterface|Account */
    $account = Mockery::mock(Account::class);
    $account->shouldReceive('getId')->andReturn($id);

    $this->loadAccountPort
      ->shouldReceive('loadAccount')
      ->withSomeOfArgs($id)
      ->andReturn($account);

    return $account;
  }

  protected function moneyTransferProperties()
  {
    return new MoneyTransferProperties(Money::of(100000000));
  }
}
