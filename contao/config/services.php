<?php

/**
 * Doctrine DBAL Bridge
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    doctrine-dbal
 * @license    LGPL
 * @filesource
 */

$closure = function ($eventDispatcher) {
	// We get either no instance at all or the previous instance or the Pimple instance.
	if (!$eventDispatcher || ($eventDispatcher instanceof Pimple)) {
		$eventDispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
	}

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

	return $eventDispatcher;
};

/** @var Pimple $container */

if (isset($container['event-dispatcher']))
{
	$container['event-dispatcher'] = $container->share($container->extend('event-dispatcher', $closure));
} else {
	$container['event-dispatcher'] = $container->share($closure);
}
