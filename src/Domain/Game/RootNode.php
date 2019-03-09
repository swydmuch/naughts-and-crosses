<?php
namespace NAC\Domain\Game;

class RootNode extends AbstractNode
{
    public function __construct(AbstractNodeFactory $factory)
    {
        $this->children = new NodeCollection();
        $nodesCollection = $factory->create();
        foreach ($nodesCollection as $node) {
            $this->addChild($node);
        }
    }

    public function getBestChild(): AbstractNode
    {
        return $this->getMaxChild();
    }
}

