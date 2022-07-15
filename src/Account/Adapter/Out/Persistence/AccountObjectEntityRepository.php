<?php

namespace BuckPal\Account\Adapter\Out\Persistence;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AccountObjectEntity>
 *
 * @method AccountObjectEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccountObjectEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccountObjectEntity[]    findAll()
 * @method AccountObjectEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountObjectEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccountObjectEntity::class);
    }

    public function add(AccountObjectEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AccountObjectEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findById($id): ?AccountObjectEntity
    {
        return $this->getEntityManager()->find(AccountObjectEntity::class, $id);
    }
}
