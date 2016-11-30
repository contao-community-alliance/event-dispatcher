<?php

/**
 * This file is part of contao-community-alliance/event-dispatcher.
 *
 * (c) 2013-2016 Contao Community Alliance <https://c-c-a.org>
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/event-dispatcher
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  2013-2016 Contao Community Alliance <https://c-c-a.org>
 * @license    https://github.com/contao-community-alliance/event-dispatcher/LICENSE LGPL-3.0+
 * @link       https://github.com/contao-community-alliance/event-dispatcher
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\EventDispatcher;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Populates the event dispatcher.
 */
class EventDispatcherPopulator
{
    /**
     * The dispatcher.
     *
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * The files building the listeners.
     *
     * @var string[]
     */
    private $listenerFiles;

    /**
     * The files building the subscribers.
     *
     * @var string[]
     */
    private $subscriberFiles;

    /**
     * The configurator to call before populating.
     *
     * @var callable
     */
    private $configurator;

    /**
     * Create a new instance.
     *
     * @param EventDispatcherInterface $dispatcher      The event dispatcher.
     * @param string[]                 $listenerFiles   File list containing the listener code.
     * @param string[]                 $subscriberFiles File list containing the subscriber code.
     */
    public function __construct(EventDispatcherInterface $dispatcher, $listenerFiles, $subscriberFiles)
    {
        $this->dispatcher      = $dispatcher;
        $this->listenerFiles   = $listenerFiles;
        $this->subscriberFiles = $subscriberFiles;
    }

    /**
     * Set configurator.
     *
     * @param callable $configurator The new value.
     *
     * @return EventDispatcherPopulator
     */
    public function setConfigurator($configurator)
    {
        $this->configurator = $configurator;

        return $this;
    }

    /**
     * Populate the event dispatcher.
     *
     * @return void
     */
    public function populate()
    {
        if ($this->configurator) {
            call_user_func($this->configurator);
        }

        $this->populateListeners();
        $this->populateSubscribers();
    }

    /**
     * Add all listeners.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    private function populateListeners()
    {
        if (isset($GLOBALS['TL_EVENTS']) && is_array($GLOBALS['TL_EVENTS'])) {
            $this->addListeners($GLOBALS['TL_EVENTS']);
        }

        foreach ($this->listenerFiles as $file) {
            $events = (array) include $file;
            $this->addListeners($events);
        }
    }

    /**
     * Add listeners to the event dispatcher.
     *
     * @param array $events A collection of event names as keys and an array of listeners as values.
     *
     * @return static
     */
    private function addListeners(array $events)
    {
        foreach ($events as $eventName => $listeners) {
            foreach ($listeners as $listener) {
                if (is_array($listener) && count($listener) === 2 && is_int($listener[1])) {
                    list($listener, $priority) = $listener;
                } else {
                    $priority = 0;
                }
                $this->dispatcher->addListener($eventName, $listener, $priority);
            }
        }

        return $this;
    }

    /**
     * Add all listeners.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    private function populateSubscribers()
    {
        if (isset($GLOBALS['TL_EVENT_SUBSCRIBERS']) && is_array($GLOBALS['TL_EVENT_SUBSCRIBERS'])) {
            $this->addSubscribers($GLOBALS['TL_EVENT_SUBSCRIBERS']);
        }

        foreach ($this->listenerFiles as $file) {
            $events = (array) include $file;
            $this->addSubscribers($events);
        }
    }

    /**
     * Add subscribers to the event dispatcher.
     *
     * @param array|string[]|\Closure[]|EventSubscriberInterface[] $eventSubscribers A collection of subscriber class
     *                                                                               names, factory functions or
     *                                                                               subscriber objects.
     *
     * @return static
     */
    private function addSubscribers(array $eventSubscribers)
    {
        foreach ($eventSubscribers as $eventSubscriber) {
            if (is_string($eventSubscriber)) {
                $eventSubscriber = new $eventSubscriber();
            } elseif (is_callable($eventSubscriber)) {
                $eventSubscriber = call_user_func($eventSubscriber, $this->dispatcher);
            }

            $this->dispatcher->addSubscriber($eventSubscriber);
        }

        return $this;
    }
}
