<?php
declare(strict_types=1);

namespace NAC\Application\UseCases\GetGameStatus;

class Query
{
    private $boardId;

    public function __construct(string $boardId)
    {
        $this->boardId = $boardId;
    }

    public function getBoardId(): string
    {
        return $this->boardId;
    }
}
