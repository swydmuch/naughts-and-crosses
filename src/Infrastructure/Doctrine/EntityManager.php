<?php
declare(strict_types=1);

namespace NAC\Infrastructure\Doctrine;

use NAC\Application\Persistence\Board\EntityManagerInterface;
use Doctrine\ORM\EntityManager as doctrineEntityManger;
use NAC\Domain\Board\Board;
use NAC\Domain\Board\BoardInterface;

class EntityManager implements EntityManagerInterface
{
    private $doctrineEntityManager;

    public function __construct(doctrineEntityManger $doctrineEntityManager)
    {
        $this->doctrineEntityManager = $doctrineEntityManager;
    }

    public function persist(BoardInterface $board): void
    {
        $this->doctrineEntityManager->persist($board);
    }

    public function flush(): void
    {
        $this->doctrineEntityManager->flush();
    }

    public function getRepository()
    {
        return $this->doctrineEntityManager->getRepository(Board::class);
    }

}