<?php

namespace App\Entity;

use App\Repository\ScoreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ScoreRepository::class)
 */
class Score
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $season;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"rankings_global"})
     */
    private $total;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="scores")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"rankings"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="scores")
     */
    private $lastEvent;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"rankings_qualifying"})
     */
    private $qualifyingScore;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"rankings_race"})
     */
    private $raceScore;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $qualifyingWins;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $qualifyingSecond;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $qualifyingThird;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $raceWins;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $raceSecond;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $raceThird;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $eventWins;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $eventSecond;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $eventThird;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeason(): ?string
    {
        return $this->season;
    }

    public function setSeason(string $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(?int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getLastEvent(): ?Event
    {
        return $this->lastEvent;
    }

    public function setLastEvent(?Event $lastEvent): self
    {
        $this->lastEvent = $lastEvent;

        return $this;
    }

    public function getQualifyingScore(): ?int
    {
        return $this->qualifyingScore;
    }

    public function setQualifyingScore(?int $qualifyingScore): self
    {
        $this->qualifyingScore = $qualifyingScore;

        return $this;
    }

    public function getRaceScore(): ?int
    {
        return $this->raceScore;
    }

    public function setRaceScore(?int $raceScore): self
    {
        $this->raceScore = $raceScore;

        return $this;
    }

    public function getQualifyingWins(): ?int
    {
        return $this->qualifyingWins;
    }

    public function setQualifyingWins(?int $qualifyingWins): self
    {
        $this->qualifyingWins = $qualifyingWins;

        return $this;
    }

    public function getQualifyingSecond(): ?int
    {
        return $this->qualifyingSecond;
    }

    public function setQualifyingSecond(?int $qualifyingSecond): self
    {
        $this->qualifyingSecond = $qualifyingSecond;

        return $this;
    }

    public function getQualifyingThird(): ?int
    {
        return $this->qualifyingThird;
    }

    public function setQualifyingThird(?int $qualifyingThird): self
    {
        $this->qualifyingThird = $qualifyingThird;

        return $this;
    }

    public function getRaceWins(): ?int
    {
        return $this->raceWins;
    }

    public function setRaceWins(?int $raceWins): self
    {
        $this->raceWins = $raceWins;

        return $this;
    }

    public function getRaceSecond(): ?int
    {
        return $this->raceSecond;
    }

    public function setRaceSecond(?int $raceSecond): self
    {
        $this->raceSecond = $raceSecond;

        return $this;
    }

    public function getRaceThird(): ?int
    {
        return $this->raceThird;
    }

    public function setRaceThird(?int $raceThird): self
    {
        $this->raceThird = $raceThird;

        return $this;
    }

    public function getEventWins(): ?int
    {
        return $this->eventWins;
    }

    public function setEventWins(?int $eventWins): self
    {
        $this->eventWins = $eventWins;

        return $this;
    }

    public function getEventSecond(): ?int
    {
        return $this->eventSecond;
    }

    public function setEventSecond(?int $eventSecond): self
    {
        $this->eventSecond = $eventSecond;

        return $this;
    }

    public function getEventThird(): ?int
    {
        return $this->eventThird;
    }

    public function setEventThird(?int $eventThird): self
    {
        $this->eventThird = $eventThird;

        return $this;
    }
}
