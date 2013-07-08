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

/** @var Pimple $container */

$container['event-dispatcher'] = $container->share(
	function () {
		$eventDispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();

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
	}
);
