[![Version](http://img.shields.io/packagist/v/contao-community-alliance/event-dispatcher.svg?style=flat-square)](https://packagist.org/packages/contao-community-alliance/event-dispatcher)
[![Stable Build Status](http://img.shields.io/travis/contao-community-alliance/event-dispatcher/master.svg?style=flat-square&label=stable build)](https://travis-ci.org/contao-community-alliance/event-dispatcher)
[![Upstream Build Status](http://img.shields.io/travis/contao-community-alliance/event-dispatcher/develop.svg?style=flat-square&label=dev build)](https://travis-ci.org/contao-community-alliance/event-dispatcher)
[![License](http://img.shields.io/packagist/l/contao-community-alliance/event-dispatcher.svg?style=flat-square)](http://spdx.org/licenses/LGPL-3.0+)
[![Downloads](http://img.shields.io/packagist/dt/contao-community-alliance/event-dispatcher.svg?style=flat-square)](https://packagist.org/packages/contao-community-alliance/event-dispatcher)

# Event dispatcher for Contao Open Source CMS

Why an event dispatcher for Contao Open Source CMS, are the hooks not enough?
First you need to understand, there is no real difference between hooks and events.
The are both notifications from within the system.

But events are more elastic than hooks. They can be hand round, consumed, stopped or bubble upon a hierarchy.

The real big reasons, why an event dispatcher exists for Contao are:

1. [Events](http://en.wikipedia.org/wiki/Event_%28computing%29) are standard paradigm in software design.
2. [Hooking](http://en.wikipedia.org/wiki/Hooking) is a paradigm to alter the behavior of a software, is it not designed for notifications.
3. Hooks are only a special form of events.
4. The [symfony event dispatcher](https://github.com/symfony/EventDispatcher) this extension based on is widely used.
5. The event dispatcher can handle every form of callbacks, like closures or static methods.

## Listen on events

The event dispatcher provide two ways to listen on events.

First and mostly used is an event listener. It is designed to listen on a single event.

Second the event subscriber is designed to listen on multiple events.

### Event listener per configuration

Since version 1.3 there are two ways to define your listeners per configuration.

#### /config/event_listeners.php

**We recommend to use this method!**

The file `/config/event_listeners.php` must return an array of event names as keys and listeners as values.

```php
<?php
return array(
    // With a closure
    'event-name' => array(
        function($event) {
            // event code
        }
    ),
    
    // With a static callable
    'event-name' => array(
        array('MyEventListener', 'myCallable')
    ),
    
    // With an object callable
    'event-name' => array(
        array(new MyEventListener(), 'myCallable')
    ),
    
    // With a service object
    'event-name' => array(
        array($GLOBALS['container']['my_event_listener'], 'myCallable')
    ),
    
    // You can wrap the listener into an array with a priority
    'event-name' => array(
        array($listener, $priority)
    ),
);
```

#### /config/config.php

In your `/config/config.php` use `$GLOBALS['TL_EVENTS']` to register your event handlers.

With a closure:
```php
$GLOBALS['TL_EVENTS']['event-name'][] = function($event) {
    // event code
};
```

With a static callable:
```php
$GLOBALS['TL_EVENTS']['event-name'][] = array('MyEventListener', 'myCallable');
```

With an object callable:
```php
$GLOBALS['TL_EVENTS']['event-name'][] = array(new MyEventListener(), 'myCallable');
```

#### Handle with priority

To define the priority, you can use an array with the listener as first and the priority as second element.

```php
$GLOBALS['TL_EVENTS']['event-name'][] = array($listener, $priority);
```

### Event listener per code

```php
$container['event-dispatcher']->addListener('event-name', $listener);
```

### Event subscriber per configuration

Since version 1.3 there are two ways to define your listeners per configuration.

#### /config/event_subscribers.php

**We recommend to use this method!**

The file `/config/event_subscribers.php` must return an array of subscribers.

```php
<?php
return array(
    // With a factory
    function($eventDispatcher) {
        return new MyEventSubscriber();
    },
    
    // With an object class name
    'MyEventSubscriber',
    
    // With an object instance
    new MyEventSubscriber(),
    
    // With a service object
    $GLOBALS['container']['my_event_subscriber'],
);
```

#### /config/config.php

In your `/config/config.php` use `$GLOBALS['TL_EVENT_SUBSCRIBERS']` to register your subscribers.

With a factory:
```php
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = function($eventDispatcher) {
    return new MyEventSubscriber();
};
```

With an object class name:
```php
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'MyEventSubscriber';
```

With an object instance:
```php
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = new MyEventSubscriber();
```

### Event subscriber per code

```php
$container['event-dispatcher']->addSubscriber(new MyEventSubscriber());
```
