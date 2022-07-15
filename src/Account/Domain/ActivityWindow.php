<?php

namespace BuckPal\Account\Domain;

use DateTime;

class ActivityWindow
{
  /**
   * @var Activity[]
   */
  private array $activities;

  public function __construct(
    array $activities = [],
  ) {
    $this->activities = $activities;
  }

  /**
   * @return Activity[]
   */
  public function getActivities(): array
  {
    return $this->activities;
  }

  public function addActivity(Activity $activity): void
  {
    $this->activities[] =  $activity;
  }

  public function calculateBalance(AccountId $accountId): Money
  {
    $depositBalance = array_reduce(
      array_map(
        fn (Activity $activity) => $activity->getMoney(),
        array_filter(
          $this->activities,
          fn (Activity $activity) => $accountId == $activity->getTargetAccountId()
        )
      ),
      Money::add(...),
      Money::$zero
    );

    $withdrawBalance = array_reduce(
      array_map(
        fn (Activity $activity) => $activity->getMoney(),
        array_filter(
          $this->activities,
          fn (Activity $activity) => $accountId == $activity->getSourceAccountId()
        )
      ),
      Money::add(...),
      Money::$zero
    );

    return Money::add($depositBalance, $withdrawBalance->negate());
  }

  public function getStartTimestamp(): DateTime
  {
    /**
     * @var DateTime
     */
    $min = array_reduce($this->activities, function (?DateTime $carry, Activity $activity) {
      $dt = $activity->getTimestamp();
      return $carry !== null ? ($carry < $dt ? $carry : $dt) : $dt;
    }, null);

    return $min;
  }

  public function getEndTimestamp(): DateTime
  {
    /**
     * @var DateTime
     */
    $min = array_reduce($this->activities, function (?DateTime $carry, Activity $activity) {
      $dt = $activity->getTimestamp();
      return $carry !== null ? ($carry > $dt ? $carry : $dt) : $dt;
    }, null);

    return $min;
  }
}
