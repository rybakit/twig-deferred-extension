<?php

namespace Phive\Twig\Extensions\Deferred;

class DeferredTokenParser extends \Twig_TokenParser_Block
{
    public function parse(\Twig_Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        $name = $stream->expect(\Twig_Token::NAME_TYPE)->getValue();

        if ($this->parser->hasBlock($name)) {
            throw new \Twig_Error_Syntax(sprintf("The block '$name' has already been defined line %d", $this->parser->getBlock($name)->getLine()), $stream->getCurrent()->getLine(), $stream->getFilename());
        }

        $block = $stream->nextIf(\Twig_Token::NAME_TYPE, 'deferred')
            ? new DeferredBlockNode($name, new \Twig_Node(array()), $lineno)
            : new \Twig_Node_Block($name, new \Twig_Node(array()), $lineno);

        $this->parser->setBlock($name, $block);
        $this->parser->pushLocalScope();
        $this->parser->pushBlockStack($name);

        if ($stream->nextIf(\Twig_Token::BLOCK_END_TYPE)) {
            $body = $this->parser->subparse(array($this, 'decideBlockEnd'), true);
            if ($token = $stream->nextIf(\Twig_Token::NAME_TYPE)) {
                $value = $token->getValue();

                if ($value !== $name) {
                    throw new \Twig_Error_Syntax(sprintf("Expected endblock for block '$name' (but %s given)", $value), $stream->getCurrent()->getLine(), $stream->getFilename());
                }
            }
        } else {
            $body = new \Twig_Node(array(
                new \Twig_Node_Print($this->parser->getExpressionParser()->parseExpression(), $lineno),
            ));
        }

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        $block->setNode('body', $body);
        $this->parser->popBlockStack();
        $this->parser->popLocalScope();

        return new \Twig_Node_BlockReference($name, $lineno, $this->getTag());
    }
}
