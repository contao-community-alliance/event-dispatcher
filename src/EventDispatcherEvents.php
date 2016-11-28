<?php

/**
 * This file is part of contao-community-alliance/event-dispatcher.
 *
 * (c) 2013-2016 Contao Community Alliance <https://c-c-a.org>
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/event-dispatcher
 * @author     Tristan Lins <t.lins@c-c-a.org>
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  2013-2016 Contao Community Alliance <https://c-c-a.org>
 * @license    https://github.com/contao-community-alliance/event-dispatcher/LICENSE LGPL-3.0+
 * @link       https://github.com/contao-community-alliance/event-dispatcher
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\EventDispatcher;

/**
 * Collection of event names.
 */
class EventDispatcherEvents
{
    /**
     * The CREATE_EVENT_DISPATCHER event occurs when the event dispatcher gets created.
     *
     * This event allows you to add additional listeners and subscribers directly to the event dispatcher.
     * It is also possible to replace the event dispatcher completely, e.g. to decorate it.
     * The event listener method receives a
     * ContaoCommunityAlliance\Contao\EventDispatcher\Event\CreateEventDispatcherEvent instance.
     *
     * @Event
     *
     * @var string
     *
     * @api
     */
    const CREATE_EVENT_DISPATCHER = 'ContaoCommunityAlliance\Contao\EventDispatcher\Event\CreateEventDispatcher';
}
