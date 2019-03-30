<?php
declare(strict_types=1);

namespace NAC\Infrastructure\Doctrine;

use NAC\Application\Persistence\Board\RepositoryInterface;
use NAC\Domain\Board\BoardInterface;

class Repository implements RepositoryInterface
{
    private $doctrineRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->doctrineRepository = $entityManager->getRepository();
    }

    public function getById(string $id): BoardInterface
    {
        return $this->doctrineRepository->find($id);
    }
}