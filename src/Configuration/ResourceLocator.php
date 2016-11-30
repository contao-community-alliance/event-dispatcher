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

namespace ContaoCommunityAlliance\Contao\EventDispatcher\Configuration;

/**
 * This class locates all resources from the installation.
 */
class ResourceLocator
{
    /**
     * The app root.
     *
     * @var string
     */
    private $appRoot;

    /**
     * The list of bundle classes.
     *
     * @var string[]
     */
    private $bundles;

    /**
     * The resource name to locate.
     *
     * @var string
     */
    private $fileName;

    /**
     * Create a new instance.
     *
     * @param string    $appRoot  The application root.
     * @param \string[] $bundles  The list of registered bundles.
     * @param string    $fileName The name of the resource files to search.
     */
    public function __construct($appRoot, array $bundles, $fileName)
    {
        $this->appRoot  = $appRoot;
        $this->bundles  = $bundles;
        $this->fileName = $fileName;
    }

    /**
     * Returns the Contao resource paths as array.
     *
     * @return array
     */
    public function getResourcePaths()
    {
        $paths = [];

        foreach ($this->bundles as $name => $class) {
            if (null !== ($path = $this->getResourcePathFromBundle($this->appRoot, $name, $class))) {
                $paths[] = $path;
            }
        }

        if (is_readable($file = $this->appRoot . '/app/Resources/contao/config/' . $this->fileName)) {
            $paths[] = $file;
        }

        if (is_readable($file = $this->appRoot . '/system/config/' . $this->fileName)) {
            $paths[] = $file;
        }

        return $paths;
    }

    /**
     * Generate the path from the bundle (if any exists).
     *
     * @param string $rootDir The app root dir.
     * @param string $name    The name of the bundle.
     * @param string $class   The bundle class name.
     *
     * @return string|null
     */
    private function getResourcePathFromBundle($rootDir, $name, $class)
    {
        if ('Contao\CoreBundle\HttpKernel\Bundle\ContaoModuleBundle' === $class) {
            $path = sprintf('%s/system/modules/%s', $rootDir, $name);
        } else {
            $path = $this->getResourcePathFromClassName($class);
        }

        if (null !== $path && is_readable($file = $path . '/config/' . $this->fileName)) {
            return $file;
        }

        return null;
    }

    /**
     * Returns the resources path from the class name.
     *
     * @param string $class The class name of the bundle.
     *
     * @return string|null
     */
    private function getResourcePathFromClassName($class)
    {
        $reflection = new \ReflectionClass($class);

        if (is_dir($dir = dirname($reflection->getFileName()).'/Resources/contao')) {
            return $dir;
        }

        return null;
    }
}
