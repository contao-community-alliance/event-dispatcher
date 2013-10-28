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

namespace ContaoCommunityAlliance\Contao\EventDispatcher\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;

class CreateEventDispatcherEvent extends Event
{
	const NAME = 'ContaoCommunityAlliance\Contao\EventDispatcher\Event\CreateEventDispatcher';

	/**
	 * @var EventDispatcher
	 */
	protected $eventDispatcher;

	function __construct(EventDispatcher $eventDispatcher)
	{
		$this->eventDispatcher = $eventDispatcher;
	}

	/**
	 * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
	 */
	public function setEventDispatcher($eventDispatcher)
	{
		$this->eventDispatcher = $eventDispatcher;
		return $this;
	}

	/**
	 * @return \Symfony\Component\EventDispatcher\EventDispatcher
	 */
	public function getEventDispatcher()
	{
		return $this->eventDispatcher;
	}
}
