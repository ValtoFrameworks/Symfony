<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bridge\Twig\Tests\Extension;

use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Asset\Preload\PreloadManager;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class AssetExtensionTest extends TestCase
{
    public function testGetAndPreloadAssetUrl()
    {
        if (!class_exists(PreloadManager::class)) {
            $this->markTestSkipped('Requires Asset 3.3+.');
        }

        $preloadManager = new PreloadManager();
        $extension = new AssetExtension(new Packages(), $preloadManager);

        $this->assertEquals('/foo.css', $extension->preload('/foo.css', 'style', true));
        $this->assertEquals('</foo.css>; rel=preload; as=style; nopush', $preloadManager->buildLinkValue());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testNoConfiguredPreloadManager()
    {
        $extension = new AssetExtension(new Packages());
        $extension->preload('/foo.css');
    }
}
