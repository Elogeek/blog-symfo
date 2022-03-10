<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 */
#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'text')]
    private $content;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private $category;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'articles')]
    private $author;

    #[ORM\Column(type: 'date')]
    private $datePostArticle;

    #[ORM\ManyToMany(targetEntity: Comment::class, mappedBy: 'article')]
    private $comments;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $picture;

    public function __construct() {
        $this->comments = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string {
        return $this->content;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): self {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category {
        return $this->category;
    }

    /**
     * @param Category|null $category
     * @return $this
     */
    public function setCategory(?Category $category): self {
        $this->category = $category;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getAuthor(): ?User {
        return $this->author;
    }

    /**
     * @param User|null $author
     * @return $this
     */
    public function setAuthor(?User $author): self {
        $this->author = $author;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDatePostArticle(): ?\DateTimeInterface {
        return $this->datePostArticle;
    }

    /**
     * @param \DateTime $datetime
     * @return $this
     */
    public function setDatePostArticle(\DateTimeInterface $datetime): self {
        $this->datePostArticle = $datetime;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection {
        return $this->comments;
    }

    /**
     * @param Comment $comment
     * @return $this
     */
    public function addComment(Comment $comment): self {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->addArticle($this);
        }

        return $this;
    }

    /**
     * @param Comment $comment
     * @return $this
     */
    public function removeComment(Comment $comment): self {
        if ($this->comments->removeElement($comment)) {
            $comment->removeArticle($this);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPicture() {
        return $this->picture;
    }

    /**
     * @param mixed $picture
     */
    public function setPicture($picture): void {
        $this->picture = $picture;
    }

}
