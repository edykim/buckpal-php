<?php

namespace BuckPal\Account\Adapter\Out\Persistence;

use BuckPal\Account\Adapter\Out\Persistence\AccountObjectEntityRepository;
use BuckPal\Account\Adapter\Out\Persistence\ActivityObjectEntityRepository;
use BuckPal\Account\Application\Port\Out\LoadAccountPort;
use BuckPal\Account\Application\Port\Out\UpdateAccountStatePort;
use BuckPal\Account\Domain\Account;
use BuckPal\Account\Domain\AccountId;
use BuckPal\Common\PersistenceAdapter;
use DateTime;
use Doctrine\ORM\EntityNotFoundException;

#[PersistenceAdapter]
class AccountPersistenceAdapter implements LoadAccountPort, UpdateAccountStatePort
{
  public function __construct(
    private AccountObjectEntityRepository $accountRepository,
    private ActivityObjectEntityRepository $activityRepository,
    private AccountMapper $accountMapper,
  ) {
  }

  public function loadAccount(AccountId $accountId, DateTime $baselineDate): Account
  {
    $account = $this->accountRepository->findById($accountId->getValue())
      ?: throw new EntityNotFoundException();

    $activities = $this->activityRepository->findByOwnerSince($accountId->getValue(), $baselineDate);
    $withdrawalBalance = $this->activityRepository->getWithdrawalBalanceUntil($accountId->getValue(), $baselineDate);
    $depositBalance = $this->activityRepository->getDepositBalanceUntil($accountId->getValue(), $baselineDate);

    return $this->accountMapper->mapToDomainEntity(
      $account,
      $activities,
      $withdrawalBalance,
      $depositBalance,
    );
  }

  public function updateActivities(Account $account): void
  {
    $activities = $account->getActivityWindow()->getActivities();
    foreach ($activities as $activity) {
      if ($activity?->getId()?->getValue() === null) {
        $this->activityRepository->add($this->accountMapper->mapToObjectEntity($activity), true);
      }
    }
  }
}
