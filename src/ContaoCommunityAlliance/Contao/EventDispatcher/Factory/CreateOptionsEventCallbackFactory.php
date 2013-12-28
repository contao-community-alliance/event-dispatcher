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
	 * Create a new event driven options callback.
	 *
	 * @param string         $eventName      The event name to dispatch.
	 * @param string|closure $classOrFactory Class name of the event or a factory method to create the event object.
	 *
	 * @return array Return a Contao callback that can be used as options_callback.
	 */
	static public function createCallback($eventName, $classOrFactory = null)
	{
		$callback = function ($dc) use ($eventName, $classOrFactory) {
			if (!$classOrFactory) {
				$event = new CreateOptionsEvent($dc);
			}
			else if (is_callable($classOrFactory)) {
				$event = call_user_func($classOrFactory, $dc);
			}
			else {
				/** @var CreateOptionsEvent $event */
				$event = new $classOrFactory($dc);
			}

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
