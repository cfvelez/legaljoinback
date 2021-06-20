<?php

namespace App\Entity;

use App\Repository\StoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=StoryRepository::class)
 */
class Story
{   
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Contact::class, inversedBy="stories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contact;

    /**
     * @ORM\OneToMany(targetEntity=Storypoint::class, mappedBy="story", orphanRemoval=true)
     */
    private $storypoints;

    public function __construct()
    {
        $this->storypoints = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * @return Collection|Storypoint[]
     */
    public function getStorypoints(): Collection
    {
        return $this->storypoints;
    }

    public function addStorypoint(Storypoint $storypoint): self
    {
        if (!$this->storypoints->contains($storypoint)) {
            $this->storypoints[] = $storypoint;
            $storypoint->setStory($this);
        }

        return $this;
    }

    public function removeStorypoint(Storypoint $storypoint): self
    {
        if ($this->storypoints->removeElement($storypoint)) {
            // set the owning side to null (unless already changed)
            if ($storypoint->getStory() === $this) {
                $storypoint->setStory(null);
            }
        }

        return $this;
    }

}
