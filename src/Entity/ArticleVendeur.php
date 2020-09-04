<?php

namespace App\Entity;

use App\Repository\ArticleVendeurRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleVendeurRepository::class)
 */
class ArticleVendeur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $plancher;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="articleVendeurs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    /**
     * @ORM\ManyToOne(targetEntity=Etat::class, inversedBy="articleVendeurs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlancher(): ?string
    {
        return $this->plancher;
    }

    public function setPlancher(string $plancher): self
    {
        $this->plancher = $plancher;

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

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

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
}
