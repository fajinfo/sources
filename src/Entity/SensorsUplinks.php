<?php

namespace App\Entity;

use App\Repository\SensorsUplinksRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SensorsUplinksRepository::class)
 */
class SensorsUplinks
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Sensors::class, inversedBy="uplinks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sensor;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $battery;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $waterFlowRate;

    public function __toString()
    {
        return $this->getDate()->format('d/m/Y H:i');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSensor(): ?Sensors
    {
        return $this->sensor;
    }

    public function setSensor(?Sensors $sensor): self
    {
        $this->sensor = $sensor;

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

    public function getBattery(): ?float
    {
        return $this->battery;
    }

    public function setBattery(?float $battery): self
    {
        $this->battery = $battery;

        return $this;
    }

    public function getWaterFlowRate(): ?float
    {
        return $this->waterFlowRate;
    }

    public function setWaterFlowRate(?float $waterFlowRate): self
    {
        $this->waterFlowRate = $waterFlowRate;

        return $this;
    }
}
