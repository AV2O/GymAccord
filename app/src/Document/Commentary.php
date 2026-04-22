<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Attribute as ODM; // On ajoute les Attributs
use App\Repository\CommentaryRepository;
// use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(repositoryClass: CommentaryRepository::class)]
class Commentary
{
    #[ODM\Id]
    protected $id;

    #[ODM\Field(type: "string")]
    protected $contenu;

    #[ODM\Field(type: "date_immutable")]
    protected $createdAt;

    #[ODM\Field(type: "int")]
    protected $userId;

    #[ODM\Field(type: "string")]
    protected $authorName;

    public function __construct() {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?string { return $this->id; }

    public function getContenu(): ?string { return $this->contenu; }

    public function setContenu(string $contenu): self { 
        $this->contenu = $contenu; 
        return $this; 
    }

    public function getAuthorName(): ?string { return $this->authorName; }

    public function setAuthorName(string $authorName): self { 
        $this->authorName = $authorName; 
        return $this; 
    }

    public function getUserId(): ?int { return $this->userId; }
    
    public function setUserId(int $userId): self { 
        $this->userId = $userId; 
        return $this; 
    }

    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
}