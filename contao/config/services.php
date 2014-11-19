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

$container['event-dispatcher.factory.default'] = $container->protect(
    function () {
        return new \Symfony\Component\EventDispatcher\EventDispatcher();
    }
);

$container['event-dispatcher.configurator.default'] = $container->protect(
    function (\Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher) {
        if (isset($GLOBALS['TL_EVENTS']) && is_array($GLOBALS['TL_EVENTS'])) {
            foreach ($GLOBALS['TL_EVENTS'] as $eventName => $listeners) {
                foreach ($listeners as $listener) {
                    if (is_array($listener) && count($listener) === 2 && is_int($listener[1])) {
                        list($listener, $priority) = $listener;
                    } else {
                        $priority = 0;
                    }
                    $eventDispatcher->addListener($eventName, $listener, $priority);
                }
            }
        }

        if (isset($GLOBALS['TL_EVENT_SUBSCRIBERS']) && is_array($GLOBALS['TL_EVENT_SUBSCRIBERS'])) {
            foreach ($GLOBALS['TL_EVENT_SUBSCRIBERS'] as $eventSubscriber) {
                if (is_string($eventSubscriber)) {
                    $eventSubscriber = new $eventSubscriber();
                } else {
                    if (is_callable($eventSubscriber)) {
                        $eventSubscriber = call_user_func($eventSubscriber, $eventDispatcher);
                    }
                }

                $eventDispatcher->addSubscriber($eventSubscriber);
            }
        }
    }
);

if (!isset($container['event-dispatcher.factory'])) {
    $container['event-dispatcher.factory'] = $container->raw('event-dispatcher.factory.default');
}

if (!isset($container['event-dispatcher.configurator'])) {
    $container['event-dispatcher.configurator'] = $container->raw('event-dispatcher.configurator.default');
}

$container['event-dispatcher'] = $container->share(
    function ($container) {
        $factory      = $container['event-dispatcher.factory'];
        $configurator = $container['event-dispatcher.configurator'];

        /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = $factory();
        $configurator($eventDispatcher, $container);

        $event = new \ContaoCommunityAlliance\Contao\EventDispatcher\Event\CreateEventDispatcherEvent($eventDispatcher);
        $eventDispatcher->dispatch($event::NAME, $event);
        $eventDispatcher = $event->getEventDispatcher();

        return $eventDispatcher;
    }
);
