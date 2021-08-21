<?php

namespace App\Entity;

use App\Repository\SourcesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SourcesRepository::class)
 */
class Sources
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Sensors::class, inversedBy="source")
     */
    private $sensor;

    /**
     * @ORM\OneToMany(targetEntity=HourlyFlow::class, mappedBy="source", orphanRemoval=true)
     */
    private $hourlyFlows;

    /**
     * @ORM\OneToMany(targetEntity=DailyFlow::class, mappedBy="source", orphanRemoval=true)
     */
    private $dailyFlows;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="sourcesAdmin")
     * @ORM\JoinTable(name="sources_admin")
     */
    private $AdminUser;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="sourcesView")
     * @ORM\JoinTable(name="sources_view")
     */
    private $ViewUser;

    public function __construct()
    {
        $this->hourlyFlows = new ArrayCollection();
        $this->dailyFlows = new ArrayCollection();
        $this->AdminUser = new ArrayCollection();
        $this->ViewUser = new ArrayCollection();
    }

    public function __toString() {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

    /**
     * @return Collection|HourlyFlow[]
     */
    public function getHourlyFlows(): Collection
    {
        return $this->hourlyFlows;
    }

    public function addHourlyFlow(HourlyFlow $hourlyFlow): self
    {
        if (!$this->hourlyFlows->contains($hourlyFlow)) {
            $this->hourlyFlows[] = $hourlyFlow;
            $hourlyFlow->setSource($this);
        }

        return $this;
    }

    public function removeHourlyFlow(HourlyFlow $hourlyFlow): self
    {
        if ($this->hourlyFlows->removeElement($hourlyFlow)) {
            // set the owning side to null (unless already changed)
            if ($hourlyFlow->getSource() === $this) {
                $hourlyFlow->setSource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DailyFlow[]
     */
    public function getDailyFlows(): Collection
    {
        return $this->dailyFlows;
    }

    public function addDailyFlow(DailyFlow $dailyFlow): self
    {
        if (!$this->dailyFlows->contains($dailyFlow)) {
            $this->dailyFlows[] = $dailyFlow;
            $dailyFlow->setSource($this);
        }

        return $this;
    }

    public function removeDailyFlow(DailyFlow $dailyFlow): self
    {
        if ($this->dailyFlows->removeElement($dailyFlow)) {
            // set the owning side to null (unless already changed)
            if ($dailyFlow->getSource() === $this) {
                $dailyFlow->setSource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getAdminUser(): Collection
    {
        return $this->AdminUser;
    }

    public function addAdminUser(User $adminUser): self
    {
        if (!$this->AdminUser->contains($adminUser)) {
            $this->AdminUser[] = $adminUser;
        }

        return $this;
    }

    public function removeAdminUser(User $adminUser): self
    {
        $this->AdminUser->removeElement($adminUser);

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getViewUser(): Collection
    {
        return $this->ViewUser;
    }

    public function addViewUser(User $viewUser): self
    {
        if (!$this->ViewUser->contains($viewUser)) {
            $this->ViewUser[] = $viewUser;
        }

        return $this;
    }

    public function removeViewUser(User $viewUser): self
    {
        $this->ViewUser->removeElement($viewUser);

        return $this;
    }
}
