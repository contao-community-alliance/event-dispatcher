<?php

/**
 * This file is part of contao-community-alliance/event-dispatcher.
 *
 * (c) 2013-2017 Contao Community Alliance <https://c-c-a.org>
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/event-dispatcher
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2013-2017 Contao Community Alliance <https://c-c-a.org>
 * @license    https://github.com/contao-community-alliance/event-dispatcher/LICENSE LGPL-3.0+
 * @link       https://github.com/contao-community-alliance/event-dispatcher
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\EventDispatcher\Test\DependencyInjection\Compiler;

use ContaoCommunityAlliance\Contao\EventDispatcher\DependencyInjection\Compiler\AddConfiguratorPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * This class tests the AddConfiguratorPass
 */
class AddConfiguratorPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that the compiler pass adds the references.
     *
     * @return void
     */
    public function testProcess()
    {
        $container = new ContainerBuilder();

        $dispatcherDefinition = new Definition();
        $populatorDefinition = new Definition();

        $container->setParameter('kernel.debug', false);
        $container->setDefinition('event_dispatcher', $dispatcherDefinition);
        $container->setDefinition('contao_community_alliance.event_dispatcher.populator', $populatorDefinition);

        $compilerPass = new AddConfiguratorPass();

        $compilerPass->process($container);

        $configurator = $dispatcherDefinition->getConfigurator();
        $this->assertTrue($container->hasParameter('kernel.debug'));
        $this->assertNotTrue($container->getParameter('kernel.debug'));
        $this->assertInternalType('array', $configurator);
        $this->assertInstanceOf('Symfony\Component\DependencyInjection\Reference', $configurator[0]);
        $this->assertSame('contao_community_alliance.event_dispatcher.populator', (string) $configurator[0]);
        $this->assertSame('populate', $configurator[1]);
    }

    /**
     * Test that the compiler pass adds the references.
     *
     * @return void
     */
    public function testProcessWithConfigurator()
    {
        $container = new ContainerBuilder();

        $dispatcherDefinition = new Definition();
        $dispatcherDefinition->setConfigurator($realConfigurator = ['Some\Class', 'someMethod']);
        $populatorDefinition = new Definition();

        $container->setParameter('kernel.debug', false);
        $container->setDefinition('event_dispatcher', $dispatcherDefinition);
        $container->setDefinition('contao_community_alliance.event_dispatcher.populator', $populatorDefinition);

        $compilerPass = new AddConfiguratorPass();

        $compilerPass->process($container);

        $configurator = $dispatcherDefinition->getConfigurator();
        $this->assertTrue($container->hasParameter('kernel.debug'));
        $this->assertNotTrue($container->getParameter('kernel.debug'));
        $this->assertInternalType('array', $configurator);
        $this->assertInstanceOf('Symfony\Component\DependencyInjection\Reference', $configurator[0]);
        $this->assertSame('contao_community_alliance.event_dispatcher.populator', (string) $configurator[0]);
        $this->assertSame('populate', $configurator[1]);
        $this->assertSame([['setConfigurator', [$realConfigurator]]], $populatorDefinition->getMethodCalls());
    }
}
