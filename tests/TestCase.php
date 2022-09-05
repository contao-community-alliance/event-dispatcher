<?php

/**
 * This file is part of contao-community-alliance/event-dispatcher.
 *
 * (c) 2013-2018 Contao Community Alliance <https://c-c-a.org>
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/event-dispatcher
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  2013-2018 Contao Community Alliance <https://c-c-a.org>
 * @license    https://github.com/contao-community-alliance/event-dispatcher/LICENSE LGPL-3.0+
 * @link       https://github.com/contao-community-alliance/event-dispatcher
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\EventDispatcher\Test;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * Temporary directory.
     *
     * @var string
     */
    protected $tempDir;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->tempDir = sys_get_temp_dir() . '/' . uniqid('cca-dic-test');
        mkdir($this->tempDir, 0700, true);
    }

    /**
     * {@inheritDoc}
     *
     * @SuppressWarnings(PHPMD.MissingImport)
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    protected function tearDown()
    {
        if (!file_exists($this->tempDir)) {
            return;
        }
        $children = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->tempDir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($children as $child) {
            if ($child->isDir()) {
                rmdir($child);
            } else {
                unlink($child);
            }
        }
        rmdir($this->tempDir);
    }
}
