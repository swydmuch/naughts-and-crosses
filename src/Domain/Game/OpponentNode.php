<?php
declare(strict_types=1);
namespace NAC\Domain\Game;

class OpponentNode extends AbstractNode
{
    const VALUE_FOR_VICTORY = -1;

    protected function getBestChild(): AbstractNode
    {
        return $this->getMaxChild();
    }
}

