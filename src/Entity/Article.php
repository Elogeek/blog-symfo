<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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

    #[ORM\Column(type: 'string', length: 255)]
    private $slug;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Level cannot be empty")]
    private $level;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: "Preparation time cannot be empty")]
    private $preparationTime;

    #[ORM\Column(type: 'text')]
    private $content;

    #[ORM\Column(type: 'boolean')]
    private $active;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $picture;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $updatedAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private $category;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'articles')]
    private $author;

    #[ORM\ManyToMany(targetEntity: Comment::class, mappedBy: 'article')]
    private $comments;

    #[ORM\Column(type: 'boolean')]
    private ?bool $isPublished;

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
     * @return mixed
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug): void {
        $this->slug = $slug;
    }

    /**
     * @return string|null
     */
    public function getLevel(): ?string {
        return $this->level;
    }

    /**
     * @param string $level
     * @return $this
     */
    public function setLevel(string $level): self {
        $this->level = $level;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPreparationTime() {
        return $this->preparationTime;
    }

    /**
     * @param mixed $preparationTime
     */
    public function setPreparationTime($preparationTime): void {
        $this->preparationTime = $preparationTime;
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

    public function getActive(): ?bool {
        return $this->active;
    }

    public function setActive(bool $active): self {
        $this->active = $active;

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


    public function getUpdatedAt(): ?\DateTimeImmutable {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self {
        $this->createdAt = $createdAt;

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
     * Return name of the category
     */
    public function __toString(){
        return $this->title;
    }

    /**
     * @return bool|null
     */
    public function getIsPublished(): ?bool {
        return $this->isPublished;
    }

    /**
     * @param bool|null $isPublished
     */
    public function setIsPublished(?bool $isPublished): void {
        $this->isPublished = $isPublished;
    }

}
