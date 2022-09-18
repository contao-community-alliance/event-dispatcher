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

namespace ContaoCommunityAlliance\Contao\EventDispatcher\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This adds the event dispatcher populator.
 */
class AddConfiguratorPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container): void
    {
        $dispatcherService = !$container->getParameter('kernel.debug') ? 'event_dispatcher' : 'debug.event_dispatcher';

        // Compatibility for Symfony 4. In this Version the definition debug.event_dispatcher no more exists.
        if ($container->getParameter('kernel.debug') && !$container->hasDefinition('debug.event_dispatcher')) {
            $dispatcherService = 'event_dispatcher';
        }

        $definition   = $container->findDefinition($dispatcherService);
        $configurator = $definition->getConfigurator();

        $definition->setConfigurator(
            [new Reference('cca.event_dispatcher.populator'), 'populate']
        );

        if ($configurator) {
            $populator = $container->findDefinition('cca.event_dispatcher.populator');
            $populator->addMethodCall('setConfigurator', [$configurator]);
        }
    }
}
