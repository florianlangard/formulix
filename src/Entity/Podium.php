<?php

namespace App\Entity;

use App\Repository\PodiumRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PodiumRepository::class)
 */
class Podium
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Event::class, inversedBy="podium", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity=Prediction::class)
     */
    private $qualifyingFirst;

    /**
     * @ORM\ManyToOne(targetEntity=Prediction::class)
     */
    private $qualifyingSecond;

    /**
     * @ORM\ManyToOne(targetEntity=Prediction::class)
     */
    private $qualifyingThird;

    /**
     * @ORM\ManyToOne(targetEntity=Prediction::class)
     */
    private $raceFirst;

    /**
     * @ORM\ManyToOne(targetEntity=Prediction::class)
     */
    private $raceSecond;

    /**
     * @ORM\ManyToOne(targetEntity=Prediction::class)
     */
    private $raceThird;

    /**
     * @ORM\ManyToOne(targetEntity=Prediction::class)
     */
    private $eventFirst;

    /**
     * @ORM\ManyToOne(targetEntity=Prediction::class)
     */
    private $eventSecond;

    /**
     * @ORM\ManyToOne(targetEntity=Prediction::class)
     */
    private $eventThird;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getQualifyingFirst(): ?Prediction
    {
        return $this->qualifyingFirst;
    }

    public function setQualifyingFirst(?Prediction $qualifyingFirst): self
    {
        $this->qualifyingFirst = $qualifyingFirst;

        return $this;
    }

    public function getQualifyingSecond(): ?Prediction
    {
        return $this->qualifyingSecond;
    }

    public function setQualifyingSecond(?Prediction $qualifyingSecond): self
    {
        $this->qualifyingSecond = $qualifyingSecond;

        return $this;
    }

    public function getQualifyingThird(): ?Prediction
    {
        return $this->qualifyingThird;
    }

    public function setQualifyingThird(?Prediction $qualifyingThird): self
    {
        $this->qualifyingThird = $qualifyingThird;

        return $this;
    }

    public function getRaceFirst(): ?Prediction
    {
        return $this->raceFirst;
    }

    public function setRaceFirst(?Prediction $raceFirst): self
    {
        $this->raceFirst = $raceFirst;

        return $this;
    }

    public function getRaceSecond(): ?Prediction
    {
        return $this->raceSecond;
    }

    public function setRaceSecond(?Prediction $raceSecond): self
    {
        $this->raceSecond = $raceSecond;

        return $this;
    }

    public function getRaceThird(): ?Prediction
    {
        return $this->raceThird;
    }

    public function setRaceThird(?Prediction $raceThird): self
    {
        $this->raceThird = $raceThird;

        return $this;
    }

    public function getEventFirst(): ?Prediction
    {
        return $this->eventFirst;
    }

    public function setEventFirst(?Prediction $eventFirst): self
    {
        $this->eventFirst = $eventFirst;

        return $this;
    }

    public function getEventSecond(): ?Prediction
    {
        return $this->eventSecond;
    }

    public function setEventSecond(?Prediction $eventSecond): self
    {
        $this->eventSecond = $eventSecond;

        return $this;
    }

    public function getEventThird(): ?Prediction
    {
        return $this->eventThird;
    }

    public function setEventThird(?Prediction $eventThird): self
    {
        $this->eventThird = $eventThird;

        return $this;
    }
}
