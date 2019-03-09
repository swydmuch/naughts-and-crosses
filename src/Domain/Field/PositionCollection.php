<?php
declare(strict_types=1);
namespace NAC\Domain\Field;

class PositionCollection implements \Iterator
{
    private $storage;

    public function __construct()
    {
        $this->storage = new \SplObjectStorage();
    }

    public function __clone()
    {
        $this->storage = clone $this->storage;
    }

    public function attach(Position $position):void
    {
        $this->storage->attach($position);
    }

    public function detach(Position $position):void
    {
        $this->storage->detach($position);
    }

    public function current(): Position
    {
        return $this->storage->current();
    }

    public function key(): int
    {
        return $this->storage->key();
    }

    public function next(): void
    {
        $this->storage->next();
    }

    public function rewind(): void
    {
        $this->storage->rewind();
    }

    public function valid(): bool
    {
        return $this->storage->valid();
    }
}