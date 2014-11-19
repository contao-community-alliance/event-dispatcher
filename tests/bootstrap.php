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

error_reporting(E_ALL);

$includeIfExists = function ($file) {
    return file_exists($file) ? include $file : false;
};

if (
    (!$loader = $includeIfExists(__DIR__ . '/../vendor/autoload.php'))
    && (!$loader = $includeIfExists(__DIR__ . '/../../../autoload.php'))
) {
    echo 'You must set up the project dependencies, run the following commands:' . PHP_EOL .
         'curl -sS https://getcomposer.org/installer | php' . PHP_EOL .
         'php composer.phar install' . PHP_EOL;
    exit(1);
}
