<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 */
#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $comment;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $date;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'comments')]
    private $author;

    #[ORM\ManyToMany(targetEntity: Article::class, inversedBy: 'comments')]
    private $article;

    public function __construct() {
        $this->article = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(?\DateTime $date): self
    {
        $this->date = $date;

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
     * @return Collection<int, Article>
     */
    public function getArticle(): Collection {
        return $this->article;
    }

    /**
     * @param Article $article
     * @return $this
     */
    public function addArticle(Article $article): self {
        if (!$this->article->contains($article)) {
            $this->article[] = $article;
        }

        return $this;
    }

    /**
     * @param Article $article
     * @return $this
     */
    public function removeArticle(Article $article): self {
        $this->article->removeElement($article);

        return $this;
    }

}

