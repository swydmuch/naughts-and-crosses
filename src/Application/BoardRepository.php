<?php
declare(strict_types=1);
namespace NAC\Application;

use NAC\Domain\Board\BoardInterface;

interface BoardRepository
{
    public function getById(string $id): BoardInterface;
}
