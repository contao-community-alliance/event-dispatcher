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

namespace ContaoCommunityAlliance\Contao\EventDispatcher;

use ContaoCommunityAlliance\Contao\EventDispatcher\Event\CreateEventDispatcherEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Create and configure the event dispatcher.
 */
class EventDispatcherInitializer
{
    /**
     * Create a new event dispatcher, using the given factory and configurator.
     *
     * @param callable|\Closure $factory      The factory service.
     * @param callable|\Closure $configurator The configurator service.
     *
     * @return EventDispatcherInterface
     */
    public function create(
        $factory,
        $configurator
    ) {
        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = $factory();
        $configurator($eventDispatcher);

        $event = new CreateEventDispatcherEvent($eventDispatcher);
        $eventDispatcher->dispatch(
            EventDispatcherEvents::CREATE_EVENT_DISPATCHER,
            $event
        );
        $eventDispatcher = $event->getEventDispatcher();

        return $eventDispatcher;
    }

    /**
     * Configure the event dispatcher.
     *
     * @param EventDispatcherInterface $eventDispatcher The event dispatcher.
     * @param \Config                  $config          The configuration object.
     *
     * @return static
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function configure(EventDispatcherInterface $eventDispatcher, \Config $config)
    {
        if (isset($GLOBALS['TL_EVENTS']) && is_array($GLOBALS['TL_EVENTS'])) {
            $this->addListeners($eventDispatcher, $GLOBALS['TL_EVENTS']);
        }

        $this->addListenersByModules($eventDispatcher, $config);

        if (isset($GLOBALS['TL_EVENT_SUBSCRIBERS']) && is_array($GLOBALS['TL_EVENT_SUBSCRIBERS'])) {
            $this->addSubscribers($eventDispatcher, $GLOBALS['TL_EVENT_SUBSCRIBERS']);
        }

        $this->addSubscribersByModules($eventDispatcher, $config);

        return $this;
    }

    /**
     * Add listeners, defined in each modules ../config/event_listeners.php file.
     *
     * @param EventDispatcherInterface $eventDispatcher The event dispatcher.
     * @param \Config                  $config          The configuration object.
     *
     * @return static
     */
    public function addListenersByModules(EventDispatcherInterface $eventDispatcher, \Config $config)
    {
        // include the module services configurations
        foreach ($config->getActiveModules() as $module) {
            $file = TL_ROOT . '/system/modules/' . $module . '/config/event_listeners.php';

            if (file_exists($file)) {
                $events = (array) include $file;
                $this->addListeners($eventDispatcher, $events);
            }
        }

        // include the local services configuration
        $file = TL_ROOT . '/system/config/event_listeners.php';

        if (file_exists($file)) {
            $events = (array) include $file;
            $this->addListeners($eventDispatcher, $events);
        }

        return $this;
    }

    /**
     * Add listeners to the event dispatcher.
     *
     * @param EventDispatcherInterface $eventDispatcher The event dispatcher.
     * @param array                    $events          A collection of event names as keys and an array of listeners
     *                                                  as values.
     *
     * @return static
     */
    public function addListeners(EventDispatcherInterface $eventDispatcher, array $events)
    {
        foreach ($events as $eventName => $listeners) {
            foreach ($listeners as $listener) {
                if (is_array($listener) && count($listener) === 2 && is_int($listener[1])) {
                    list($listener, $priority) = $listener;
                } else {
                    $priority = 0;
                }
                $eventDispatcher->addListener($eventName, $listener, $priority);
            }
        }

        return $this;
    }

    /**
     * Add listeners, defined in each modules ../config/event_listeners.php file.
     *
     * @param EventDispatcherInterface $eventDispatcher The event dispatcher.
     * @param \Config                  $config          The configuration object.
     *
     * @return static
     */
    public function addSubscribersByModules(EventDispatcherInterface $eventDispatcher, \Config $config)
    {
        // include the module services configurations
        foreach ($config->getActiveModules() as $module) {
            $file = TL_ROOT . '/system/modules/' . $module . '/config/event_subscribers.php';

            if (file_exists($file)) {
                $subscribers = (array) include $file;
                $this->addSubscribers($eventDispatcher, $subscribers);
            }
        }

        // include the local services configuration
        $file = TL_ROOT . '/system/config/event_subscribers.php';

        if (file_exists($file)) {
            $subscribers = (array) include $file;
            $this->addSubscribers($eventDispatcher, $subscribers);
        }

        return $this;
    }

    /**
     * Add subscribers to the event dispatcher.
     *
     * @param EventDispatcherInterface                             $eventDispatcher  The event dispatcher.
     * @param array|string[]|\Closure[]|EventSubscriberInterface[] $eventSubscribers A collection of subscriber class
     *                                                                               names, factory functions or
     *                                                                               subscriber objects.
     *
     * @return static
     */
    public function addSubscribers(EventDispatcherInterface $eventDispatcher, array $eventSubscribers)
    {
        foreach ($eventSubscribers as $eventSubscriber) {
            if (is_string($eventSubscriber)) {
                $eventSubscriber = new $eventSubscriber();
            } elseif (is_callable($eventSubscriber)) {
                $eventSubscriber = call_user_func($eventSubscriber, $eventDispatcher);
            }

            $eventDispatcher->addSubscriber($eventSubscriber);
        }

        return $this;
    }
}
