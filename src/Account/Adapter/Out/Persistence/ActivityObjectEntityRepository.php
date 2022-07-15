<?php

namespace BuckPal\Account\Adapter\Out\Persistence;

use BuckPal\Account\Adapter\Out\Persistence\ActivityObjectEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use DateTime;

/**
 * @extends ServiceEntityRepository<ActivityObjectEntity>
 *
 * @method ActivityObjectEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivityObjectEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivityObjectEntity[]    findAll()
 * @method ActivityObjectEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityObjectEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityObjectEntity::class);
    }

    public function add(ActivityObjectEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ActivityObjectEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return ActivityObjectEntity[]
     */
    public function findByOwnerSince(int $id, DateTime $since): array
    {
        return $this->createQueryBuilder('activity')
            ->andWhere('activity.ownerAccountId = :id')
            ->andWhere('activity.timestamp >= :date')
            ->setParameter('id', $id)
            ->setParameter('date', $since)
            ->getQuery()
            ->getResult();
    }

    public function getDepositBalanceUntil(int $accountId, DateTime $until): string
    {
        return $this->createQueryBuilder('activity')
            ->select('sum(activity.amount) as deposit_balance')
            ->andWhere('activity.targetAccountId = :account_id')
            ->andWhere('activity.ownerAccountId = :account_id')
            ->andWhere('activity.timestamp < :until')
            ->setParameter('account_id', $accountId)
            ->setParameter('until', $until)
            ->getQuery()
            ->getSingleScalarResult() ?? 0;
    }

    public function getWithdrawalBalanceUntil(int $accountId, DateTime $until): string
    {
        return $this->createQueryBuilder('activity')
            ->select('sum(activity.amount) as deposit_balance')
            ->andWhere('activity.sourceAccountId = :account_id')
            ->andWhere('activity.ownerAccountId = :account_id')
            ->andWhere('activity.timestamp < :until')
            ->setParameter('account_id', $accountId)
            ->setParameter('until', $until)
            ->getQuery()
            ->getSingleScalarResult() ?? 0;
    }
}
