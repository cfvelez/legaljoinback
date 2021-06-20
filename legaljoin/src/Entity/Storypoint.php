<?php

namespace App\Entity;

use App\Repository\StorypointRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StorypointRepository::class)
 */
class Storypoint
{
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
}
