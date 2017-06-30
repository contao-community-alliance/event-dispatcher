<?php

/**
 * This file is part of contao-community-alliance/event-dispatcher.
 *
 * (c) 2013-2016 Contao Community Alliance <https://c-c-a.org>
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/event-dispatcher
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  2013-2016 Contao Community Alliance <https://c-c-a.org>
 * @license    https://github.com/contao-community-alliance/event-dispatcher/LICENSE LGPL-3.0+
 * @link       https://github.com/contao-community-alliance/event-dispatcher
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\EventDispatcher\Test\Configuration;

use ContaoCommunityAlliance\Contao\EventDispatcher\Configuration\ResourceLocator;
use ContaoCommunityAlliance\Contao\EventDispatcher\Test\TestCase;

class ResourceLocatorTest extends TestCase
{
    /**
     * Test that all configs are to be found.
     *
     * @return void
     */
    public function testFindsAllConfigs()
    {
        mkdir($this->tempDir . '/system/modules/foobar/config', 0700, true);
        touch($this->tempDir . '/system/modules/foobar/config/event_listeners.php');
        mkdir($this->tempDir . '/app/Resources/contao/config', 0700, true);
        touch($this->tempDir . '/app/Resources/contao/config/event_listeners.php');
        mkdir($this->tempDir . '/system/config', 0700, true);
        touch($this->tempDir . '/system/config/event_listeners.php');

        $mockBundleNameSpace = 'ContaoCommunityAlliance\Contao\EventDispatcher\Test\Mocks\Bundles';
        $locator = new ResourceLocator(
            $this->tempDir,
            [
                'TestBundle'            => $mockBundleNameSpace . '\TestBundle\TestBundle',
                'TestBundleNoResources' => $mockBundleNameSpace . '\TestBundleNoResources\TestBundleNoResources',
                'foobar'                => 'Contao\CoreBundle\HttpKernel\Bundle\ContaoModuleBundle',
            ],
            'event_listeners.php'
        );

        $this->assertSame(
            [
                dirname(__DIR__) . '/Mocks/Bundles/TestBundle/Resources/contao/config/event_listeners.php',
                $this->tempDir . '/system/modules/foobar/config/event_listeners.php',
                $this->tempDir . '/app/Resources/contao/config/event_listeners.php',
                $this->tempDir . '/system/config/event_listeners.php'
            ],
            $locator->getResourcePaths()
        );
    }
}
