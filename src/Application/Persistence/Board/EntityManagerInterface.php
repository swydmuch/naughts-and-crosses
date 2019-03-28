<?php
declare(strict_types=1);
namespace NAC\Application\Persistence\Board;

use NAC\Domain\Board\BoardInterface;

interface EntityManagerInterface
{
    public function persist(BoardInterface $board): void;

    public function flush(): void;
}
