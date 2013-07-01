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
	function ($container) {
		return new \Symfony\Component\EventDispatcher\EventDispatcher();
	}
);
