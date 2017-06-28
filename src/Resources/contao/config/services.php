<?php

/**
 * This file is part of contao-community-alliance/event-dispatcher.
 *
 * (c) 2013-2017 Contao Community Alliance <https://c-c-a.org>
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/event-dispatcher
 * @author     Tristan Lins <tristan@lins.io>
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2013-2017 Contao Community Alliance <https://c-c-a.org>
 * @license    https://github.com/contao-community-alliance/event-dispatcher/LICENSE LGPL-3.0+
 * @link       https://github.com/contao-community-alliance/event-dispatcher
 * @filesource
 */

use ContaoCommunityAlliance\Contao\EventDispatcher\Configuration\ResourceLocator;
use ContaoCommunityAlliance\Contao\EventDispatcher\EventDispatcherPopulator;
use DependencyInjection\Container\PimpleGate;
use Symfony\Component\EventDispatcher\EventDispatcher;

/** @var PimpleGate $container */

// Contao 4 code.
if ($container->isContao4()) {
    $container->provideSymfonyService('event-dispatcher', 'event_dispatcher');
    return;
}

// Contao 3 code.
$container['event-dispatcher'] = $container->share(
    function () {
        $dispatcher = new EventDispatcher();

        // Collect all system paths
        $bundles = [];
        foreach (ModuleLoader::getActive() as $module) {
            $bundles[$module] = 'Contao\CoreBundle\HttpKernel\Bundle\ContaoModuleBundle';
        }

        $populator = new EventDispatcherPopulator(
            $dispatcher,
            (new ResourceLocator(TL_ROOT, $bundles, 'event_listeners.php'))->getResourcePaths(),
            (new ResourceLocator(TL_ROOT, $bundles, 'event_subscribers.php'))->getResourcePaths()
        );

        $populator->populate();

        return $dispatcher;
    }
);
