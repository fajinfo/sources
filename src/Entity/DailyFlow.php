<?php

namespace App\Entity;

use App\Repository\DailyFlowRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DailyFlowRepository::class)
 */
class DailyFlow
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Sources::class, inversedBy="dailyFlows")
     * @ORM\JoinColumn(nullable=false)
     */
    private $source;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="float")
     */
    private $mediumFlowrate;

    /**
     * @ORM\Column(type="float")
     */
    private $maximumFlowrate;

    /**
     * @ORM\Column(type="float")
     */
    private $minimumFlowrate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSource(): ?Sources
    {
        return $this->source;
    }

    public function setSource(?Sources $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getMediumFlowrate(): ?float
    {
        return $this->mediumFlowrate;
    }

    public function setMediumFlowrate(float $mediumFlowrate): self
    {
        $this->mediumFlowrate = $mediumFlowrate;

        return $this;
    }

    public function getMaximumFlowrate(): ?float
    {
        return $this->maximumFlowrate;
    }

    public function setMaximumFlowrate(float $maximumFlowrate): self
    {
        $this->maximumFlowrate = $maximumFlowrate;

        return $this;
    }

    public function getMinimumFlowrate(): ?float
    {
        return $this->minimumFlowrate;
    }

    public function setMinimumFlowrate(float $minimumFlowrate): self
    {
        $this->minimumFlowrate = $minimumFlowrate;

        return $this;
    }
}
