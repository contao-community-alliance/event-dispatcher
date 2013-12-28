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

namespace ContaoCommunityAlliance\Contao\EventDispatcher\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 *
 */
class CreateOptionsEvent extends Event
{
	/**
	 * @var \DataContainer
	 */
	protected $dataContainer;

	/**
	 * @var \ArrayObject
	 */
	protected $options;

	function __construct(\DataContainer $dataContainer, \ArrayObject $options = null)
	{
		$this->dataContainer = $dataContainer;

		if ($this->options) {
			$this->options = $options;
		}
		else {
			$this->options = new \ArrayObject();
		}
	}

	/**
	 * @return \DataContainer
	 */
	public function getDataContainer()
	{
		return $this->dataContainer;
	}

	/**
	 * @param \ArrayObject $options
	 */
	public function setOptions($options)
	{
		$this->options = $options;
		return $this;
	}

	/**
	 * @return \ArrayObject
	 */
	public function getOptions()
	{
		return $this->options;
	}
}
