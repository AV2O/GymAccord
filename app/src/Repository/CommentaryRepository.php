<?php

namespace App\Repository;

use App\Document\Commentary; 
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\UnitOfWork;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

class CommentaryRepository extends DocumentRepository
{
    // Ajoute ce constructeur pour aider Symfony à faire le lien
    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $class)
    {
        parent::__construct($dm, $uow, $class);
    }

    /**
     * Récupère les derniers commentaires
     */
    public function findLastComments(int $limit = 10)
    {
        return $this->createQueryBuilder() 
            ->sort('createdAt', 'DESC')
            ->limit($limit)
            ->getQuery()
            ->execute();
    }
}