<?php

/**
 * This file is part of contao-community-alliance/event-dispatcher.
 *
 * (c) 2013-2018 Contao Community Alliance <https://c-c-a.org>
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/event-dispatcher
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  2013-2018 Contao Community Alliance <https://c-c-a.org>
 * @license    https://github.com/contao-community-alliance/event-dispatcher/LICENSE LGPL-3.0+
 * @link       https://github.com/contao-community-alliance/event-dispatcher
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\EventDispatcher\Test\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerBundle\ContaoManagerBundle;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use ContaoCommunityAlliance\Contao\EventDispatcher\CcaEventDispatcherBundle;
use ContaoCommunityAlliance\Contao\EventDispatcher\ContaoManager\Plugin;
use DependencyInjection\Container\CcaDependencyInjectionBundle;
use PHPUnit\Framework\TestCase;

/**
 * Tests the Plugin class.
 */
class PluginTest extends TestCase
{
    /**
     * Tests the object instantiation.
     */
    public function testInstantiation()
    {
        $plugin = new Plugin();

        $this->assertInstanceOf('ContaoCommunityAlliance\Contao\EventDispatcher\ContaoManager\Plugin', $plugin);
    }

    /**
     * Tests the getBundles() method.
     */
    public function testGetBundles()
    {
        $parser = $this->getMockBuilder(ParserInterface::class)->getMockForAbstractClass();

        /** @var BundleConfig $config */
        $config = (new Plugin())->getBundles($parser)[0];

        $this->assertInstanceOf('Contao\ManagerPlugin\Bundle\Config\BundleConfig', $config);
        $this->assertSame(CcaEventDispatcherBundle::class, $config->getName());
        $this->assertSame(
            [
                ContaoCoreBundle::class,
                ContaoManagerBundle::class,
                CcaDependencyInjectionBundle::class,
            ],
            $config->getLoadAfter()
        );
    }
}
