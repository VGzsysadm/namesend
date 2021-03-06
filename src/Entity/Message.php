<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private $message;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="boolean")
     */
    private $protection;

    /**
     * @ORM\OneToOne(targetEntity=MessagePassword::class, mappedBy="mess", cascade={"persist", "remove"})
     */
    private $messagePassword;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function __toString() {
        return $this->getUrl();
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }
    public function __construct()
    {
        $this->status = false;
    }

    public function getProtection(): ?bool
    {
        return $this->protection;
    }

    public function setProtection(bool $protection): self
    {
        $this->protection = $protection;

        return $this;
    }

    public function getMessagePassword(): ?MessagePassword
    {
        return $this->messagePassword;
    }

    public function setMessagePassword(?MessagePassword $messagePassword): self
    {
        $this->messagePassword = $messagePassword;

        // set (or unset) the owning side of the relation if necessary
        $newMess = null === $messagePassword ? null : $this;
        if ($messagePassword->getMess() !== $newMess) {
            $messagePassword->setMess($newMess);
        }

        return $this;
    }
}
