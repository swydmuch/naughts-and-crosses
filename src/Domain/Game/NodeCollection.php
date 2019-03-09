<?php
declare(strict_types=1);
namespace NAC\Domain\Game;

class NodeCollection implements \Iterator
{
    private $storage;

    public function __construct()
    {
        $this->storage = new \SplObjectStorage();
    }

    public function attach(AbstractNode $node):void
    {
        $this->storage->attach($node);
    }

    public function current(): AbstractNode
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
