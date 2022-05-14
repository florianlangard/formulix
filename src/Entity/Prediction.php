<?php

namespace App\Entity;

use App\Repository\PredictionRepository;
use DateTime;
use DateTimeZone;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PredictionRepository::class)
 */
class Prediction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Driver")
     */
    private $pole;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Regex(pattern="/^\d[:]\d{2}[.]\d{3}$/", message="le format est invalide")
     */
    private $time;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $score;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="predictions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="predictions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity=Driver::class)
     */
    private $finishFirst;

    /**
     * @ORM\ManyToOne(targetEntity=Driver::class)
     */
    private $finishSecond;

    /**
     * @ORM\ManyToOne(targetEntity=Driver::class)
     */
    private $finishThird;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $raceCreatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $raceUpdatedAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $raceScore;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $totalScore;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPole(): ?Driver
    {
        return $this->pole;
    }

    public function setPole(Driver $pole): self
    {
        $this->pole = $pole;

        return $this;
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(string $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;

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

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getFinishFirst(): ?Driver
    {
        return $this->finishFirst;
    }

    public function setFinishFirst(?Driver $finishFirst): self
    {
        $this->finishFirst = $finishFirst;

        return $this;
    }

    public function getFinishSecond(): ?Driver
    {
        return $this->finishSecond;
    }

    public function setFinishSecond(?Driver $finishSecond): self
    {
        $this->finishSecond = $finishSecond;

        return $this;
    }

    public function getFinishThird(): ?Driver
    {
        return $this->finishThird;
    }

    public function setFinishThird(?Driver $finishThird): self
    {
        $this->finishThird = $finishThird;

        return $this;
    }

    public function getRaceCreatedAt(): ?\DateTimeInterface
    {
        return $this->raceCreatedAt;
    }

    public function setRaceCreatedAt(?\DateTimeInterface $raceCreatedAt): self
    {
        $this->raceCreatedAt = $raceCreatedAt;

        return $this;
    }

    public function getRaceUpdatedAt(): ?\DateTimeInterface
    {
        return $this->raceUpdatedAt;
    }

    public function setRaceUpdatedAt(?\DateTimeInterface $raceUpdatedAt): self
    {
        $this->raceUpdatedAt = $raceUpdatedAt;

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

    public function getTotalScore(): ?int
    {
        return $this->totalScore;
    }

    public function setTotalScore(?int $totalScore): self
    {
        $this->totalScore = $totalScore;

        return $this;
    }
}
