<?php
declare(strict_types=1);
namespace NAC\Application\Persistence\Board;

use NAC\Domain\Board\BoardInterface;

interface RepositoryInterface
{
    public function getById(string $id): BoardInterface;
}
