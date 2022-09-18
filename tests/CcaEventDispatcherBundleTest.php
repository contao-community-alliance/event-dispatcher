<?php

/**
 * This file is part of contao-community-alliance/event-dispatcher.
 *
 * (c) 2013-2018 Contao Community Alliance <https://c-c-a.org>
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/event-dispatcher
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  2013-2018 Contao Community Alliance <https://c-c-a.org>
 * @license    https://github.com/contao-community-alliance/event-dispatcher/LICENSE LGPL-3.0+
 * @link       https://github.com/contao-community-alliance/event-dispatcher
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\EventDispatcher\Test;

use ContaoCommunityAlliance\Contao\EventDispatcher\CcaEventDispatcherBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * This class tests the CcaEventDispatcherBundle
 */
class CcaEventDispatcherBundleTest extends TestCase
{
    /**
     * Test that the build method works correctly.
     *
     * @return void
     */
    public function testBuild(): void
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.bundles', []);
        $container->setParameter('kernel.project_dir', $this->tempDir);

        $bundle = new CcaEventDispatcherBundle();

        $container->getCompiler()->getPassConfig()->setBeforeOptimizationPasses([]);
        $bundle->build($container);

        $passes   = $container->getCompiler()->getPassConfig()->getBeforeOptimizationPasses();
        $lastPass = end($passes);

        $this->assertInstanceOf(
            'ContaoCommunityAlliance\Contao\EventDispatcher\DependencyInjection\Compiler\AddConfiguratorPass',
            $lastPass
        );
        $this->assertSame([], $container->getParameter('cca.event_dispatcher.legacy_listeners'));
        $this->assertSame([], $container->getParameter('cca.event_dispatcher.legacy_subscribers'));
    }
}
