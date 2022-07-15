<?php

namespace BuckPal\Account\Adapter\In\Console;

use BuckPal\Account\Adapter\Out\Persistence\AccountPersistenceAdapter;
use BuckPal\Account\Adapter\Out\Persistence\ActivityObjectEntityRepository;
use BuckPal\Account\Domain\AccountId;
use BuckPal\Account\Domain\ActivityWindow;
use BuckPal\Account\Domain\Money;
use BuckPal\Tests\TestData\AccountTestData;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AccountPersistenceAdapterTest extends KernelTestCase
{
  use AccountTestData;
  private AccountPersistenceAdapter $adapter;
  private ActivityObjectEntityRepository $repository;

  protected function setUp(): void
  {
    $kernel = self::bootKernel();
    $this->adapter = $kernel->getContainer()
      ->get(AccountPersistenceAdapter::class);
    $this->repository = $kernel->getContainer()
        ->get(ActivityObjectEntityRepository::class);
  }

  public function testLoadAccount()
  {
    $account = $this->adapter->loadAccount(new AccountId(1), new DateTime('2022-06-01'));

    $this->assertCount(4, $account->getActivityWindow()->getActivities());
    $this->assertEquals(Money::of(500), $account->calculateBalance());
  }

  public function testUpdatesActivities()
  {
    $account = $this->defaultAccount()
      ->withBaselineBalance(Money::of(555))
      ->withActivityWindow(new ActivityWindow([
        $this->defaultActivity()->withMoney(Money::of(1))->build()
      ]))
      ->build();

    $current = $this->repository->count([]);
    $this->adapter->updateActivities($account);
    $this->assertEquals($current + 1, $this->repository->count([]));

    $savedActivity = $this->repository->findOneBy([], ['id' => 'DESC']);
    $this->assertEquals(Money::of(1), $savedActivity->getAmount());

  }

  protected function tearDown(): void
  {
    parent::tearDown();
  }
}
