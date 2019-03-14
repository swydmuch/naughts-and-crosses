<?php
declare(strict_types=1);
namespace NAC\Application;

use NAC\Domain\Board\BoardInterface;

interface PersistenceManagerInterface
{
    public function persist(BoardInterface $board): void;
}
