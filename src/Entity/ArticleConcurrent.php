<?php

namespace App\Entity;

use App\Repository\ArticleConcurrentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleConcurrentRepository::class)
 */
class ArticleConcurrent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="articleConcurrents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    /**
     * @ORM\ManyToOne(targetEntity=Concurrent::class, inversedBy="articleConcurrents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $concurrent;

    /**
     * @ORM\ManyToOne(targetEntity=Etat::class, inversedBy="articleConcurrents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etat;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $prix;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getConcurrent(): ?Concurrent
    {
        return $this->concurrent;
    }

    public function setConcurrent(?Concurrent $concurrent): self
    {
        $this->concurrent = $concurrent;

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }
}
