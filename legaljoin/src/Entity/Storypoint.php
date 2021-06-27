<?php

namespace App\Entity;

use App\Repository\StorypointRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=StorypointRepository::class)
 */
class Storypoint
{   
    use TimestampableEntity;
    
    /** 
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $appointment_time;

    /**
     * @ORM\ManyToOne(targetEntity=Story::class, inversedBy="storypoints")
     * @ORM\JoinColumn(nullable=false)
     */
    private $story;

    /**
     * @ORM\OneToMany(targetEntity=Resource::class, mappedBy="storypoint")
     */
    private $resourceslist;

    public function __construct()
    {
        $this->resource = new ArrayCollection();
        $this->resourceslist = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAppointmentTime(): ?\DateTimeInterface
    {
        return $this->appointment_time;
    }

    public function setAppointmentTime(?\DateTimeInterface $appointment_time): self
    {
        $this->appointment_time = $appointment_time;

        return $this;
    }

    public function getStory(): ?story
    {
        return $this->story;
    }

    public function setStory(?story $story): self
    {
        $this->story = $story;

        return $this;
    }

    /**
     * @return Collection|Resource[]
     */
    public function getResourceslist(): Collection
    {
        return $this->resourceslist;
    }

    public function addResourceslist(Resource $resourceslist): self
    {
        if (!$this->resourceslist->contains($resourceslist)) {
            $this->resourceslist[] = $resourceslist;
            $resourceslist->setStorypoint($this);
        }

        return $this;
    }

    public function removeResourceslist(Resource $resourceslist): self
    {
        if ($this->resourceslist->removeElement($resourceslist)) {
            // set the owning side to null (unless already changed)
            if ($resourceslist->getStorypoint() === $this) {
                $resourceslist->setStorypoint(null);
            }
        }

        return $this;
    }
}
