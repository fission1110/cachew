<?php

/**
 * Core Cachew library namespace. This namespace contains all of the fundamental
 * components of the Cachew project, plus additional utilities that are provided
 * by default. Some of these default components have sub-namespaces if they
 * provide child objects.
 *
 * @package    Cachew
 * @subpackage Library
 */
namespace Cachew;

/**
 * Cachew class
 *
 * The Cachew class provides a gateway into available cache drivers, without
 * having to explicitly call the driver. Additionally, it allows you to interact
 * with cache drivers in a static context.
 *
 * @package    Cachew
 * @subpackage Library
 */
class Cachew
{
	/**
	 * Cachew configurations
	 *
	 * @var    array
	 */
	public static $configuration = array(

		/**
		 * The default cache driver to utilize in the event one is not specified
		 * during instancing
		 *
		 * @var    string
		 */
		'default' => 'file',

		/**
		 * Application specific key to prepend to store item names, preventing
		 * collisions with other applications on the same system.
		 *
		 * @var    string
		 */
		'key' => 'cachew_',

		/**
		 * The writable directory path to store file-based caches
		 *
		 * @var    string
		 */
		'path' => null,
	);

	/**
	 * The loaded cache drivers. This array provides a registry of instances
	 * so that only single instances exist for each driver loaded
	 *
	 * @var    array
	 */
	private static $drivers = array();

	/**
	 * Retrieve a singleton instance to a cache driver
	 *
	 * If no driver name is specified, the configured default will be used
	 *
	 * @param    string           The instance name to retrieve
	 * @return   Cache\Driver\*   Returns the singleton cache driver instance
	 */
	public static function instance($driver = null)
	{
		empty($driver) and $driver = static::$configuration['default'];

		if(($driver = \strtolower($driver)) and !isset(static::$drivers[$driver]))
		{
			static::$drivers[$driver] = static::driver($driver);
		}

		return static::$drivers[$driver];
	}

	/**
	 * Create a new cache driver instance, based on its lowercase identifier
	 *
	 * @param    string|array     s
	 * @param    string           The lowercase cache driver name
	 * @return   Cache\Driver\*   A new instance to the cache driver
	 * @throws   \BadMethodCallException   Thrown when the
	 */
	private static function driver($driver)
	{
		switch($driver)
		{
			case 'apc':
				return new Driver\APC(static::$configuration['key']);
			case 'file':
				return new Driver\File(static::$configuration['key'], static::$configuration['path']);
			case 'memcache':
				return new Driver\Memcache(static::$configuration['key'], Memcache::instance());
			case 'memcached':
				return new Driver\Memcached(static::$configuration['key'], Memcached::instance());
			default:
				throw new \InvalidArgumentException('Cachew driver ['.$driver.'] is not currently supported');
			break;
		}
	}

	/**
	 * The magic callStatic method is triggered when invoking inaccessible
	 * methods in a static context.
	 *
	 * This method provides a convenient API for a developer to quickly access
	 * methods on the default cache driver.
	 *
	 * @param    string           The method name being called
	 * @param    array            The arguments for the called method
	 * @return   mixed            Returns the value of the interpretted method call
	 */
	public static function __callStatic($method, $parameters)
	{
		return \call_user_func_array(array(static::instance(), $method), $parameters);
	}
}

/* End of file Cachew.php */