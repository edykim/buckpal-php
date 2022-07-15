<?php

namespace BuckPal\Account\Adapter\Out\Persistence;

use BuckPal\Account\Adapter\Out\Persistence\AccountObjectEntityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccountObjectEntityRepository::class)]
#[ORM\Table(name: 'account')]
class AccountObjectEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
