<?php

namespace BuckPal\Tests\DataFixtures;

use BuckPal\Account\Adapter\Out\Persistence\AccountObjectEntity;
use BuckPal\Account\Adapter\Out\Persistence\ActivityObjectEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTime;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $account1 = new AccountObjectEntity;
        $manager->persist($account1);

        $account2 = new AccountObjectEntity;
        $manager->persist($account2);
        $manager->flush();

        $id1 = $account1->getId();
        $id2 = $account2->getId();

        $manager->persist($this->createActivity($id1, $id1, $id2, 500, new DateTime('2022-06-01')));
        $manager->persist($this->createActivity($id2, $id1, $id2, 500, new DateTime('2022-06-01')));
        $manager->persist($this->createActivity($id1, $id2, $id1, 1000, new DateTime('2022-06-02')));
        $manager->persist($this->createActivity($id2, $id2, $id1, 1000, new DateTime('2022-06-02')));
        $manager->persist($this->createActivity($id1, $id1, $id2, 1000, new DateTime('2022-06-02')));
        $manager->persist($this->createActivity($id2, $id1, $id2, 1000, new DateTime('2022-06-02')));
        $manager->persist($this->createActivity($id1, $id2, $id1, 1000, new DateTime('2022-06-03')));
        $manager->persist($this->createActivity($id2, $id2, $id1, 1000, new DateTime('2022-06-03')));
        $manager->flush();
    }

    private function createActivity(
        int $ownerAccountId,
        int $sourceAccountId,
        int $targetAccountId,
        string|int $amount,
        DateTime $timestamp = new DateTime
    ): ActivityObjectEntity {
        $activity = new ActivityObjectEntity;
        $activity->setTimestamp($timestamp)
            ->setOwnerAccountId($ownerAccountId)
            ->setSourceAccountId($sourceAccountId)
            ->setTargetAccountId($targetAccountId)
            ->setAmount($amount);
        return $activity;
    }
}
