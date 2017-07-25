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

use ContaoCommunityAlliance\Contao\EventDispatcher\Configuration\ResourceLocator;
use ContaoCommunityAlliance\Contao\EventDispatcher\DependencyInjection\Compiler\AddConfiguratorPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * This is the bundle for the legacy event dispatcher.
 */
class CcaEventDispatcherBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $rootDir = dirname($container->getParameter('kernel.root_dir'));
        $bundles = $container->getParameter('kernel.bundles');

        $listenerLocator = new ResourceLocator($rootDir, $bundles, 'event_listeners.php');
        $container->setParameter(
            'cca.event_dispatcher.legacy_listeners',
            $listenerLocator->getResourcePaths()
        );

        $subscriberLocator = new ResourceLocator($rootDir, $bundles, 'event_subscribers.php');
        $container->setParameter(
            'cca.event_dispatcher.legacy_subscribers',
            $subscriberLocator->getResourcePaths()
        );

        $container->addCompilerPass(new AddConfiguratorPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION);
    }
}
