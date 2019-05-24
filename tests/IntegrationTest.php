<?php

namespace Phive\Twig\Extensions\Tests\Deferred;

use Phive\Twig\Extensions\Deferred\DeferredExtension;
use Twig\Extension\StringLoaderExtension;
use Twig\Test\IntegrationTestCase;

class IntegrationTest extends IntegrationTestCase
{
    public function getExtensions()
    {
        return [
            new DeferredExtension(),
            new TestExtension(),
            new StringLoaderExtension(),
        ];
    }

    public function getFixturesDir()
    {
        return __DIR__.'/Fixtures';
    }
}
