<?php

namespace Phive\Twig\Extensions\Deferred;

use Twig\Node\BlockNode;
use Twig\Node\Node;
use Twig\Parser;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;
use Twig\TokenParser\BlockTokenParser;

class DeferredTokenParser extends AbstractTokenParser
{
    private $blockTokenParser;

    public function setParser(Parser $parser)
    {
        parent::setParser($parser);

        $this->blockTokenParser = new BlockTokenParser();
        $this->blockTokenParser->setParser($parser);
    }

    public function parse(Token $token)
    {
        $stream = $this->parser->getStream();
        $nameToken = $stream->next();
        $deferredToken = $stream->nextIf(Token::NAME_TYPE, 'deferred');
        $stream->injectTokens([$nameToken]);

        $node = $this->blockTokenParser->parse($token);

        if ($deferredToken) {
            $this->replaceBlockNode($nameToken->getValue());
        }

        return $node;
    }

    public function getTag()
    {
        return 'block';
    }

    private function replaceBlockNode($name)
    {
        $block = $this->parser->getBlock($name)->getNode(0);
        $this->parser->setBlock($name, $this->createDeferredBlockNode($block));
    }

    private function createDeferredBlockNode(BlockNode $block)
    {
        $name = $block->getAttribute('name');
        $deferredBlock = new DeferredBlockNode($name, new Node([]), $block->getTemplateLine());

        foreach ($block as $nodeName => $node) {
            $deferredBlock->setNode($nodeName, $node);
        }

        if ($sourceContext = $block->getSourceContext()) {
            $deferredBlock->setSourceContext($sourceContext);
        }

        return $deferredBlock;
    }
}
