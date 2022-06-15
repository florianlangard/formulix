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
}
