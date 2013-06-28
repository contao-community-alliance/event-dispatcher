<?php

/**
 * Doctrine DBAL Bridge
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    doctrine-dbal
 * @license    LGPL
 * @filesource
 */

/** @var Pimple $container */

$container['doctrine.cache.default'] = $container->share(
	function ($container) {
		if ($container['doctrine.cache.impl.default'] == 'auto') {
			if (extension_loaded('apc')) {
				$cache = new \Doctrine\Common\Cache\ApcCache();
			} elseif (extension_loaded('xcache')) {
				$cache = new \Doctrine\Common\Cache\XcacheCache();
			} elseif (extension_loaded('memcache')) {
				$memcache = new \Memcache();
				$memcache->connect('127.0.0.1');
				$cache = new \Doctrine\Common\Cache\MemcacheCache();
				$cache->setMemcache($memcache);
			} elseif (extension_loaded('redis')) {
				$redis = new \Redis();
				$redis->connect('127.0.0.1');
				$cache = new \Doctrine\Common\Cache\RedisCache();
				$cache->setRedis($redis);
			} else {
				$cache = new \Doctrine\Common\Cache\ArrayCache();
			}
		}
		else {
			$url = parse_url($container['doctrine.cache.impl.default']);
			if (empty($url['scheme'])) {
				$url['scheme'] = $url['path'];
			}
			switch ($url['scheme']) {
				case 'apc':
					$cache = new \Doctrine\Common\Cache\ApcCache();
					break;
				case 'xcache':
					$cache = new \Doctrine\Common\Cache\XcacheCache();
					break;
				case 'memcache':
					$memcache = new \Memcache();
					$memcache->connect(
						empty($url['host']) ? '127.0.0.1' : $url['host'],
						empty($url['port']) ? null : $url['port']
					);
					$cache = new \Doctrine\Common\Cache\MemcacheCache();
					$cache->setMemcache($memcache);
					break;
				case 'redis':
					$redis = new \Redis();
					if (empty($url['path'])) {
						$redis->connect(
							empty($url['host']) ? '127.0.0.1' : $url['host'],
							empty($url['port']) ? 6379 : $url['port']
						);
					}
					else {
						$redis->connect($url['path']);
					}
					$cache = new \Doctrine\Common\Cache\RedisCache();
					$cache->setRedis($redis);
					break;
				case 'array':
					$cache = new \Doctrine\Common\Cache\ArrayCache();
					break;
				default:
					throw new RuntimeException('Invalid doctrine cache impl ' . $container['doctrine.cache.impl.default']);
			}
		}
		return $cache;
	}
);

$container['doctrine.cache.impl.default'] = 'auto';

$container['doctrine.cache.ttl.default'] = 0;

$container['doctrine.cache.key.default'] = 'contao_default_connection';

$container['doctrine.cache.profile.default'] = $container->share(
	function ($container) {
		return new \Doctrine\DBAL\Cache\QueryCacheProfile(
			$container['doctrine.cache.ttl.default'],
			$container['doctrine.cache.key.default'],
			$container['doctrine.cache.default']
		);
	}
);

$container['doctrine.connection.default'] = $container->share(
	function ($container) {
		// reuse existing connection if the driver adapter is used
		if (strtolower($GLOBALS['TL_CONFIG']['dbDriver']) == 'doctrinemysql') {
			return \Database::getInstance()->getConnection();
		}

		$config = new \Doctrine\DBAL\Configuration();

		// set cache
		$cache = $container['doctrine.cache.default'];
		if ($cache) {
			$config->setResultCacheImpl($cache);
		}

		// build connection parameters
		$connectionParameters = array(
			'dbname'   => $GLOBALS['TL_CONFIG']['dbDatabase'],
			'user'     => $GLOBALS['TL_CONFIG']['dbUser'],
			'password' => $GLOBALS['TL_CONFIG']['dbPass'],
			'host'     => $GLOBALS['TL_CONFIG']['dbHost'],
			'port'     => $GLOBALS['TL_CONFIG']['dbPort'],
		);

		switch (strtolower($GLOBALS['TL_CONFIG']['dbDriver'])) {
			case 'mysql':
			case 'mysqli':
				$connectionParameters['driver']  = 'pdo_mysql';
				$connectionParameters['charset'] = $GLOBALS['TL_CONFIG']['dbCharset'];
				if (!empty($GLOBALS['TL_CONFIG']['dbSocket'])) {
					$connectionParameters['unix_socket'] = $GLOBALS['TL_CONFIG']['dbSocket'];
				}
				break;
			default:
				throw new RuntimeException('Database driver ' . $GLOBALS['TL_CONFIG']['dbDriver'] . ' not known by doctrine.');
		}

		if (!empty($GLOBALS['TL_CONFIG']['dbPdoDriverOptions'])) {
			$connectionParameters['driverOptions'] = deserialize($GLOBALS['TL_CONFIG']['dbPdoDriverOptions'], true);
		}

		// Register types
		foreach ($GLOBALS['DOCTRINE_TYPES'] as $name => $className) {
			\Doctrine\DBAL\Types\Type::addType($name, $className);
		}

		// Call hook prepareDoctrineConnection
		if (array_key_exists('TL_HOOK', $GLOBALS) && array_key_exists('prepareDoctrineConnection', $GLOBALS['TL_HOOK']) && is_array($GLOBALS['TL_HOOK']['prepareDoctrineConnection'])) {
			foreach ($GLOBALS['TL_HOOK']['prepareDoctrineConnection'] as $callback) {
				$object = method_exists($callback[0], 'getInstance') ? call_user_func(array($callback[0], 'getInstance')) : new $callback[0];
				$object->$callback[1]($connectionParameters, $config);
			}
		}

		// establish connection
		$connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParameters, $config);

		// Call hook doctrineConnect
		if (array_key_exists('TL_HOOK', $GLOBALS) && array_key_exists('doctrineConnect', $GLOBALS['TL_HOOK']) && is_array($GLOBALS['TL_HOOK']['doctrineConnect'])) {
			foreach ($GLOBALS['TL_HOOK']['doctrineConnect'] as $callback) {
				$object = method_exists($callback[0], 'getInstance') ? call_user_func(array($callback[0], 'getInstance')) : new $callback[0];
				$object->$callback[1]($connectionParameters, $config);
			}
		}

		return $connection;
	}
);
