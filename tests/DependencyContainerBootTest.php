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
 * @copyright  2013-2017 Contao Community Alliance <https://c-c-a.org>
 * @license    https://github.com/contao-community-alliance/event-dispatcher/LICENSE LGPL-3.0+
 * @link       https://github.com/contao-community-alliance/event-dispatcher
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\EventDispatcher\Test;

use Contao\System;
use DependencyInjection\Container\ContainerInitializer;
use DependencyInjection\Container\PimpleGate;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DependencyContainerBootTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that the services.php get's loaded correctly.
     *
     * @return void
     */
    public function testLoadServicesPhp()
    {
        System::setContainer(
            $container = $this->getMockForAbstractClass(ContainerInterface::class)
        );

        $container
            ->expects($this->once())
            ->method('getParameter')
            ->with('cca.legacy_dic')
            ->willReturn([__DIR__ . '/../src/Resources/contao/config/services.php']);

        $dispatcher = $this->getMockForAbstractClass(EventDispatcherInterface::class);
        $container
            ->expects($this->once())
            ->method('get')
            ->with('event_dispatcher')
            ->willReturn($dispatcher);

        $GLOBALS['container'] = new PimpleGate([], $container);

        $initializer = $this->getMock(
            ContainerInitializer::class,
            ['getInstanceOf']
        );

        /** @var ContainerInitializer $initializer */
        $initializer->init();

        $this->assertTrue($GLOBALS['container']->offsetExists('event-dispatcher'));
        $this->assertSame($dispatcher, $GLOBALS['container']['event-dispatcher']);
    }
}
