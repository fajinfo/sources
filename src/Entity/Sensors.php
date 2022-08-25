<?php

namespace App\Entity;

use App\Repository\SensorsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SensorsRepository::class)
 */
class Sensors
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Sources::class, inversedBy="sensor")
     */
    private $source;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $devEui;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastSeen;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $lastBattery;

      /**
     * @ORM\OneToMany(targetEntity=SensorsUplinks::class, mappedBy="sensor", orphanRemoval=true)
     */
    private $uplinks;

    public function __construct()
    {
          $this->uplinks = new ArrayCollection();
    }

    public function __toString(){
        return $this->devEui;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param Sources $source
     */
    public function setSource(Sources $source = null): void
    {
        $this->source = $source;
    }

    public function getDevEui(): ?string
    {
        return $this->devEui;
    }

    public function setDevEui(string $devEui): self
    {
        $this->devEui = $devEui;

        return $this;
    }

    public function getLastSeen(): ?\DateTimeInterface
    {
        return $this->lastSeen;
    }

    public function setLastSeen(?\DateTimeInterface $lastSeen): self
    {
        $this->lastSeen = $lastSeen;

        return $this;
    }

    public function getLastBattery(): ?float
    {
        return $this->lastBattery;
    }

    public function getLastBatteryPercent(): ?float
    {
        $batteryV = round($this->lastBattery, 1);
        $batteryCurve = [3.5 => 1, 3.4 => 0.98, 3.3 => 0.90, 3.2 => 0.70];
        if($batteryV > 3.6){
            return 1.01;
        }

        if($batteryV < 3.1){
            return 0;
        }

        return $batteryCurve[$batteryV];
    }

    public function setLastBattery(?float $lastBattery): self
    {
        $this->lastBattery = $lastBattery;

        return $this;
    }

  
    /**
     * @return Collection|SensorsUplinks[]
     */
    public function getUplinks(): Collection
    {
        return $this->uplinks;
    }

    public function addUplink(SensorsUplinks $uplink): self
    {
        if (!$this->uplinks->contains($uplink)) {
            $this->uplinks[] = $uplink;
            $uplink->setSensor($this);
        }

        return $this;
    }

    public function removeUplink(SensorsUplinks $uplink): self
    {
        if ($this->uplinks->removeElement($uplink)) {
            // set the owning side to null (unless already changed)
            if ($uplink->getSensor() === $this) {
                $uplink->setSensor(null);
            }
        }

        return $this;
    }
}
