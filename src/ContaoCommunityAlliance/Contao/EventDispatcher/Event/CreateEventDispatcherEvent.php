<?php

/**
 * This file is part of contao-community-alliance/event-dispatcher.
 *
 * (c) Contao Community Alliance <https://c-c-a.org>
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/<project-name>
 * @author     Tristan Lins <t.lins@c-c-a.org>
 * @copyright  Contao Community Alliance <https://c-c-a.org>
 * @link       https://github.com/contao-community-alliance/event-dispatcher
 * @license    http://opensource.org/licenses/LGPL-3.0 LGPL-3.0+
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\EventDispatcher\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * This event is dispatched, when the event dispatcher will be created.
 */
class CreateEventDispatcherEvent extends Event
{
    /**
     * The event name.
     *
     * @var string
     * @deprecated Use ContaoCommunityAlliance\Contao\EventDispatcher\EventDispatcherEvents::CREATE_EVENT_DISPATCHER
     *             instead.
     */
    const NAME = 'ContaoCommunityAlliance\Contao\EventDispatcher\Event\CreateEventDispatcher';

    /**
     * The event dispatcher.
     *
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * Create a new event.
     *
     * @param EventDispatcherInterface $eventDispatcher The event dispatcher, that gets created.
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Replace the event dispatcher.
     *
     * @param EventDispatcherInterface $eventDispatcher The event dispatcher.
     *
     * @return static
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        return $this;
    }

    /**
     * Return the event dispatcher.
     *
     * @return EventDispatcherInterface The event dispatcher.
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }
}
