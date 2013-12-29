<?php

/**
 * Event dispatcher for Contao
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  (c) 2013 Contao Community Alliance
 * @author         Tristan Lins <tristan.lins@bit3.de>
 * @package        event-dispatcher
 * @license        LGPL
 * @filesource
 */

$GLOBALS['TL_CRON']['monthly'][] = array(
	'ContaoCommunityAlliance\Contao\EventDispatcher\Cron\CronDispatcher',
	'monthly'
);
$GLOBALS['TL_CRON']['weekly'][]  = array(
	'ContaoCommunityAlliance\Contao\EventDispatcher\Cron\CronDispatcher',
	'weekly'
);
$GLOBALS['TL_CRON']['daily'][]   = array(
	'ContaoCommunityAlliance\Contao\EventDispatcher\Cron\CronDispatcher',
	'daily'
);
$GLOBALS['TL_CRON']['hourly'][]  = array(
	'ContaoCommunityAlliance\Contao\EventDispatcher\Cron\CronDispatcher',
	'hourly'
);
$GLOBALS['TL_CRON']['minutely'][]  = array(
	'ContaoCommunityAlliance\Contao\EventDispatcher\Cron\CronDispatcher',
	'minutely'
);
