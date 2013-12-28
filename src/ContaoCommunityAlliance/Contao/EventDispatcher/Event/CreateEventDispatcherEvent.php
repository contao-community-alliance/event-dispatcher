<?php

/**
 * Event dispatcher for Contao
 * Copyright (C) 2013 Contao Community Alliance
 *
 * PHP version 5
 *
 * @copyright  (c) 2013 Contao Community Alliance
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    event-dispatcher
 * @license    LGPL
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\EventDispatcher\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CreateEventDispatcherEvent extends Event
{
	const NAME = 'ContaoCommunityAlliance\Contao\EventDispatcher\Event\CreateEventDispatcher';

	/**
	 * @var EventDispatcherInterface
	 */
	protected $eventDispatcher;

	function __construct(EventDispatcherInterface $eventDispatcher)
	{
		$this->eventDispatcher = $eventDispatcher;
	}

	/**
	 * @param EventDispatcherInterface $eventDispatcher
	 */
	public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
	{
		$this->eventDispatcher = $eventDispatcher;
		return $this;
	}

	/**
	 * @return EventDispatcherInterface
	 */
	public function getEventDispatcher()
	{
		return $this->eventDispatcher;
	}
}
