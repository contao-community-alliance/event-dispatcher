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

namespace ContaoCommunityAlliance\Contao\EventDispatcher\Cron;

use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class CronDispatcher
 */
class CronDispatcher
{
	/**
	 * Dispatch monthly cron
	 */
	public function monthly()
	{
		$eventDispatcher = $this->getEventDispatcher();
		$eventDispatcher->dispatch('cron.monthly');
	}

	/**
	 * Dispatch weekly cron
	 */
	public function weekly()
	{
		$eventDispatcher = $this->getEventDispatcher();
		$eventDispatcher->dispatch('cron.weekly');
	}

	/**
	 * Dispatch daily cron
	 */
	public function daily()
	{
		$eventDispatcher = $this->getEventDispatcher();
		$eventDispatcher->dispatch('cron.daily');
	}

	/**
	 * Dispatch hourly cron
	 */
	public function hourly()
	{
		$eventDispatcher = $this->getEventDispatcher();
		$eventDispatcher->dispatch('cron.hourly');
	}

	/**
	 * Dispatch minutely cron
	 */
	public function minutely()
	{
		$eventDispatcher = $this->getEventDispatcher();
		$eventDispatcher->dispatch('cron.minutely');
	}

	/**
	 * Return the event dispatcher.
	 *
	 * @return EventDispatcher
	 */
	public function getEventDispatcher()
	{
		return $GLOBALS['container']['event-dispatcher'];
	}
}
