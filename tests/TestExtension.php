<?php

namespace Phive\Twig\Extensions\Tests\Deferred;

class TestExtension extends \Twig_Extension
{
    public $assets = array();

    public function getFunctions()
    {
        $self = $this;

        return array(
            new \Twig_SimpleFunction('asset_add', function($asset) use ($self) {
                $self->assets[] = $asset;
            }),
            new \Twig_SimpleFunction('asset_all', function() use ($self) {
                echo implode(',', $self->assets);
            }),
        );
    }

    public function getName()
    {
        return 'test';
    }
}
