<?php

namespace BuckPal\Account\Adapter\Out\Persistence;

use BuckPal\Account\Adapter\Out\Persistence\ActivityObjectEntityRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityObjectEntityRepository::class)]
#[ORM\Table(name: 'activity')]
class ActivityObjectEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private DateTime $timestamp;

    #[ORM\Column(type: 'integer')]
    private int $ownerAccountId;

    #[ORM\Column(type: 'integer')]
    private int $sourceAccountId;

    #[ORM\Column(type: 'integer')]
    private int $targetAccountId;

    #[ORM\Column(type: 'decimal', precision: 16, scale: 2)]
    private float $amount;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTimestamp(): DateTime
    {
        return $this->timestamp;
    }

    public function getOwnerAccountId(): int
    {
        return $this->ownerAccountId;
    }

    public function getSourceAccountId(): int
    {
        return $this->sourceAccountId;
    }

    public function getTargetAccountId(): int
    {
        return $this->targetAccountId;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }


    public function setId(int $value): self
    {
        $this->id = $value;
        return $this;
    }

    public function setTimestamp(DateTime $value): self
    {
        $this->timestamp = $value;
        return $this;
    }

    public function setOwnerAccountId(int $value): self
    {
        $this->ownerAccountId = $value;
        return $this;
    }

    public function setSourceAccountId(int $value): self
    {
        $this->sourceAccountId = $value;
        return $this;
    }

    public function setTargetAccountId(int $value): self
    {
        $this->targetAccountId = $value;
        return $this;
    }

    public function setAmount(string $value): self
    {
        $this->amount = $value;
        return $this;
    }
}
