<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
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
    private $commenterName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commenterEmail;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentBody;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    private $post;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommenterName(): ?string
    {
        return $this->commenterName;
    }

    public function setCommenterName(?string $commenterName): self
    {
        $this->commenterName = $commenterName;

        return $this;
    }

    public function getCommenterEmail(): ?string
    {
        return $this->commenterEmail;
    }

    public function setCommenterEmail(?string $commenterEmail): self
    {
        $this->commenterEmail = $commenterEmail;

        return $this;
    }

    public function getCommentBody(): ?string
    {
        return $this->commentBody;
    }

    public function setCommentBody(?string $commentBody): self
    {
        $this->commentBody = $commentBody;

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

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param mixed $post
     */
    public function setPost($post): void
    {
        $this->post = $post;
    }
}
