<?php

namespace App\Entity;

use App\Repository\ContractRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContractRepository::class)]
class Contract
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $provider = null;

    #[ORM\Column(length: 4000, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $startDate = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $endDate = null;

    // Currently assuming that values are added in Eurocents.
    #[ORM\Column]
    private ?int $pricePerKwh = null;

    #[ORM\Column]
    private ?int $additionalCosts = null;

    #[ORM\Column]
    private ?int $initialReading = null;

    /**
     * @var Collection<int, Reading>
     */
    #[ORM\OneToMany(targetEntity: Reading::class, mappedBy: 'contract')]
    private Collection $readings;

    public function __construct()
    {
        $this->readings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function setProvider(string $provider): static
    {
        $this->provider = $provider;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeImmutable $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeImmutable $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getPricePerKwh(): ?int
    {
        return $this->pricePerKwh;
    }

    public function setPricePerKwh(int $pricePerKwh): static
    {
        $this->pricePerKwh = $pricePerKwh;

        return $this;
    }

    public function getAdditionalCosts(): ?int
    {
        return $this->additionalCosts;
    }

    public function setAdditionalCosts(int $additionalCosts): static
    {
        $this->additionalCosts = $additionalCosts;

        return $this;
    }

    public function getInitialReading(): ?int
    {
        return $this->initialReading;
    }

    public function setInitialReading(?int $initialReading): Contract
    {
        $this->initialReading = $initialReading;
        return $this;
    }

    /**
     * @return Collection<int, Reading>
     */
    public function getReadings(): Collection
    {
        return $this->readings;
    }

    public function addReading(Reading $reading): static
    {
        if (!$this->readings->contains($reading)) {
            $this->readings->add($reading);
            $reading->setContract($this);
        }

        return $this;
    }

    public function removeReading(Reading $reading): static
    {
        if ($this->readings->removeElement($reading)) {
            // set the owning side to null (unless already changed)
            if ($reading->getContract() === $this) {
                $reading->setContract(null);
            }
        }

        return $this;
    }
}
