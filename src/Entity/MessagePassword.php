<?php

namespace App\Entity;

use App\Repository\MessagePasswordRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessagePasswordRepository::class)
 */
class MessagePassword
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @ORM\OneToOne(targetEntity=Message::class, inversedBy="messagePassword", cascade={"persist", "remove"})
     */
    private $mess;

    /**
     * @ORM\Column(type="smallint")
     */
    private $count;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getMess(): ?Message
    {
        return $this->mess;
    }

    public function setMess(?Message $mess): self
    {
        $this->mess = $mess;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }
}
