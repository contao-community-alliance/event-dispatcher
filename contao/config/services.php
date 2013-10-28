<?php

/**
 * Event dispatcher for Contao
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  (c) 2013 Contao Community Alliance
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    event-dispatcher
 * @license    LGPL
 * @filesource
 */

/** @var Pimple $container */

$container['event-dispatcher.factory.default'] = $container->protect(
	function() {
		return new \Symfony\Component\EventDispatcher\EventDispatcher();
	}
);

$container['event-dispatcher.configurator.default'] = $container->protect(
	function($eventDispatcher) {
		if (isset($GLOBALS['TL_EVENTS']) && is_array($GLOBALS['TL_EVENTS'])) {
			foreach ($GLOBALS['TL_EVENTS'] as $eventName => $listeners) {
				foreach ($listeners as $listener) {
					if (is_array($listener) && count($listener) === 2 && is_int($listener[1])) {
						list($listener, $priority) = $listener;
					}
					else {
						$priority = 0;
					}
					$eventDispatcher->addListener($eventName, $listener, $priority);
				}
			}
		}

		if (isset($GLOBALS['TL_EVENT_SUBSCRIBERS']) && is_array($GLOBALS['TL_EVENT_SUBSCRIBERS'])) {
			foreach ($GLOBALS['TL_EVENT_SUBSCRIBERS'] as $eventSubscriber) {
				if (!is_object($eventSubscriber)) {
					if (is_callable($eventSubscriber)) {
						$eventSubscriber = call_user_func($eventSubscriber, $eventDispatcher);
					}
					else {
						$eventSubscriber = new $eventSubscriber();
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

		$eventDispatcher = $factory();
		$configurator($eventDispatcher);

		$event = new CreateEventDispatcherEvent($eventDispatcher);
		$eventDispatcher->dispatch($event::NAME, $event);
		$eventDispatcher = $event->getEventDispatcher();

		return $eventDispatcher;
	}
);
