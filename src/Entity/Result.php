<?php

namespace App\Entity;

use App\Repository\ResultRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResultRepository::class)
 */
class Result
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Driver::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $pole;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $time;

    /**
     * @ORM\OneToOne(targetEntity=Event::class, inversedBy="result", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Driver::class)
     */
    private $finishedFirst;

    /**
     * @ORM\ManyToOne(targetEntity=Driver::class)
     */
    private $finishedSecond;

    /**
     * @ORM\ManyToOne(targetEntity=Driver::class)
     */
    private $finishedThird;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPole(): ?Driver
    {
        return $this->pole;
    }

    public function setPole(?Driver $pole): self
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

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getFinishedFirst(): ?Driver
    {
        return $this->finishedFirst;
    }

    public function setFinishedFirst(?Driver $finishedFirst): self
    {
        $this->finishedFirst = $finishedFirst;

        return $this;
    }

    public function getFinishedSecond(): ?Driver
    {
        return $this->finishedSecond;
    }

    public function setFinishedSecond(?Driver $finishedSecond): self
    {
        $this->finishedSecond = $finishedSecond;

        return $this;
    }

    public function getFinishedThird(): ?Driver
    {
        return $this->finishedThird;
    }

    public function setFinishedThird(?Driver $finishedThird): self
    {
        $this->finishedThird = $finishedThird;

        return $this;
    }
}
