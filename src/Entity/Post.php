<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $content;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $createdAt;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $upVote;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $downVote;

    #[ORM\ManyToOne(targetEntity: Thread::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private Thread $thread;

    public function __construct(){
        $this->downVote = 0;
        $this->upVote = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpVote(): ?int
    {
        return $this->upVote;
    }

    public function setUpVote(?int $upVote): self
    {
        $this->upVote = $upVote;

        return $this;
    }

    public function getDownVote(): ?int
    {
        return $this->downVote;
    }

    public function setDownVote(?int $downVote): self
    {
        $this->downVote = $downVote;

        return $this;
    }

    public function getThread(): ?Thread
    {
        return $this->thread;
    }

    public function setThread(?Thread $thread): self
    {
        $this->thread = $thread;

        return $this;
    }
}
