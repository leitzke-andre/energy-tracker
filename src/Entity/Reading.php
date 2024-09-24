<?php

namespace App\Entity;

use App\Repository\ReadingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReadingRepository::class)]
class Reading
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $readingDate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 7, scale: 1)]
    private ?string $valueinKwh = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    #[ORM\ManyToOne(inversedBy: 'readings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contract $contract = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReadingDate(): ?\DateTimeImmutable
    {
        return $this->readingDate;
    }

    public function setReadingDate(\DateTimeImmutable $readingDate): static
    {
        $this->readingDate = $readingDate;

        return $this;
    }

    public function getValueinKwh(): ?string
    {
        return $this->valueinKwh;
    }

    public function setValueinKwh(string $valueinKwh): static
    {
        $this->valueinKwh = $valueinKwh;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getContract(): ?Contract
    {
        return $this->contract;
    }

    public function setContract(?Contract $contract): static
    {
        $this->contract = $contract;

        return $this;
    }
}
