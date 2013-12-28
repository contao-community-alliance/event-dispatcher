<?php

/**
 * Event dispatcher for Contao
 * Copyright (C) 2013 Contao Community Alliance
 *
 * PHP version 5
 *
 * @copyright  (c) 2013 Contao Community Alliance
 * @author         Tristan Lins <tristan.lins@bit3.de>
 * @package        event-dispatcher
 * @license        LGPL
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\EventDispatcher\Factory;

use ContaoCommunityAlliance\Contao\EventDispatcher\Event\CreateOptionsEvent;
use ContaoCommunityAlliance\Contao\EventDispatcher\Helper\CreateOptionsEventCallbackHelper;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 *
 */
class CreateOptionsEventCallbackFactory
{
	/**
	 * @param string $eventName
	 * @param string $class
	 */
	static public function createCallback($eventName, $class = null)
	{
		$callback = function ($dc) use ($eventName, $class) {
			if (!$class) {
				$class = 'ContaoCommunityAlliance\Contao\EventDispatcher\Event\CreateOptionsEvent';
			}

			/** @var CreateOptionsEvent $event */
			$event = new $class($dc);

			/** @var EventDispatcher $eventDispatcher */
			$eventDispatcher = $GLOBALS['container']['event-dispatcher'];
			$eventDispatcher->dispatch($eventName, $event);

			return $event->getOptions()
				->getArrayCopy();
		};

		if (version_compare(VERSION, '3.2', '<')) {
			return CreateOptionsEventCallbackHelper::registerEventCallback($callback);
		}
		else {
			return $callback;
		}
	}
}
