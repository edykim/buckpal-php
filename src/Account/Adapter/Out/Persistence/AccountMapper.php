<?php

namespace BuckPal\Account\Adapter\Out\Persistence;

use BuckPal\Account\Domain\Account;
use BuckPal\Account\Domain\AccountId;
use BuckPal\Account\Domain\Activity;
use BuckPal\Account\Domain\ActivityId;
use BuckPal\Account\Domain\ActivityWindow;
use BuckPal\Account\Domain\Money;

class AccountMapper
{
  public function mapToDomainEntity(AccountObjectEntity $account, array $activities, $withdrawalBalance, $depositBalance): Account
  {
    // @FIXME account implement from orm
    $baselineBalance = Money::subtract(Money::of($depositBalance), Money::of($withdrawalBalance));

    return Account::withId(new AccountId($account->getId()), $baselineBalance, $this->mapToActivityWindow($activities));
  }

  public function mapToActivityWindow(array $activities): ActivityWindow
  {
    $list = array_map(function (ActivityObjectEntity $activity) {
      return new Activity(
        new AccountId($activity->getOwnerAccountId()),
        new AccountId($activity->getSourceAccountId()),
        new AccountId($activity->getTargetAccountId()),
        $activity->getTimestamp(),
        Money::of($activity->getAmount()),
        id: new ActivityId($activity->getId()),
      );
    }, $activities);
    return new ActivityWindow($list);
  }

  public function  mapToObjectEntity(Activity $activity)
  {
    $entity = new ActivityObjectEntity;

    if ($activity?->getId()?->getValue() !== null) {
      $entity->setId($activity->getId()->getValue());
    }

    return $entity->setTimestamp($activity->getTimestamp())
      ->setOwnerAccountId($activity->getOwnerAccountId()->getValue())
      ->setSourceAccountId($activity->getSourceAccountId()->getValue())
      ->setTargetAccountId($activity->getTargetAccountId()->getValue())
      ->setAmount($activity->getMoney());
  }
}
