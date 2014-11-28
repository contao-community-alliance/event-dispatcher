<?php

/**
 * This file is part of contao-community-alliance/event-dispatcher.
 *
 * (c) Contao Community Alliance <https://c-c-a.org>
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/<project-name>
 * @author     Tristan Lins <t.lins@c-c-a.org>
 * @copyright  Contao Community Alliance <https://c-c-a.org>
 * @link       https://github.com/contao-community-alliance/event-dispatcher
 * @license    http://opensource.org/licenses/LGPL-3.0 LGPL-3.0+
 * @filesource
 */

/** @var Pimple $container */

$container['event-dispatcher.initializer'] = $container->share(
    function () {
        return new \ContaoCommunityAlliance\Contao\EventDispatcher\EventDispatcherInitializer();
    }
);

$container['event-dispatcher.factory.default'] = $container->protect(
    function () {
        return new \Symfony\Component\EventDispatcher\EventDispatcher();
    }
);

$container['event-dispatcher.configurator.default'] = $container->protect(
    function (\Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher) {
        global $container;

        /** @var \ContaoCommunityAlliance\Contao\EventDispatcher\EventDispatcherInitializer $initializer */
        $initializer = $container['event-dispatcher.initializer'];

        /** @var \Contao\Config $config */
        $config = $container['config'];

        $initializer->configure($eventDispatcher, $config);
    }
);

if (!isset($container['event-dispatcher.factory'])) {
    $container['event-dispatcher.factory'] = function($container) {
        return $container['event-dispatcher.factory.default'];
    };
}

if (!isset($container['event-dispatcher.configurator'])) {
    $container['event-dispatcher.configurator'] = function($container) {
        return $container['event-dispatcher.configurator.default'];
    };
}

$container['event-dispatcher'] = $container->share(
    function ($container) {
        /** @var \ContaoCommunityAlliance\Contao\EventDispatcher\EventDispatcherInitializer $initializer */
        $initializer = $container['event-dispatcher.initializer'];

        /** @var \Closure $factory */
        $factory = $container['event-dispatcher.factory'];
        /** @var \Closure $configurator */
        $configurator = $container['event-dispatcher.configurator'];

        return $initializer->create($factory, $configurator);
    }
);
