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

namespace ContaoCommunityAlliance\Contao\EventDispatcher\Test\DependencyInjection;

use ContaoCommunityAlliance\Contao\EventDispatcher\DependencyInjection\CcaEventDispatcherExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**+
 * This tests the CcaEventDispatcherExtension class.
 */
class CcaEventDispatcherExtensionTest extends TestCase
{
    /**
     * Test that the yaml file get's loaded.
     *
     * @return void
     */
    public function testLoad()
    {
        $extension = new CcaEventDispatcherExtension();

        $extension->load([], $container = new ContainerBuilder());

        $this->assertTrue($container->hasDefinition('cca.event_dispatcher.populator'));
    }
}
