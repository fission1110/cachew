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
 * Memcache class
 *
 * The Memcache class provides a singleton interface for PHP's Memcache
 * extension, for easily configurable connections!
 *
 * @see        http://php.net/Memcache
 * @package    Cachew
 * @subpackage Library
 */
class Memcache
{
	/**
	 * Memcache server configurations
	 *
	 * Memcache is a free and open source, high-performance, distributed memory
	 * object caching system, generic in nature, but intended for use in
	 * speeding up dynamic web applications by alleviating database load.
	 *
	 * For more information about Memcache, check out: http://Memcache.org/
	 *
	 * Each value of this array should be an array containing the configurations
	 * described to connect to Memcache in the PHP Manual:
	 * http://php.net/manual/en/Memcache.addservers.php
	 *
	 * @var    array
	 */
	public static $configuration = array(

		array(

			/**
			 * The hostname of the memcache server. If the hostname is invalid,
			 * data-related operations will set
			 * `Memcache::RES_HOST_LOOKUP_FAILURE` result code.
			 *
			 * @var    string
			 */
			'host' => '127.0.0.1',

			/**
			 * The port on which memcache is running. Usually, this is 11211
			 *
			 * @var    integer
			 */
			'port' => 11211,

			/**
			 * The weight of the server relative to the total weight of all the
			 * servers in the pool. This controls the probability of the server
			 * being selected for operations. This is used only with consistent
			 * distribution option and usually corresponds to the amount of
			 * memory available to memcache on that server
			 *
			 * @var    integer
			 */
			'weight' => 100,
		),
	);

	/**
	 * The magic call static method is triggered when invoking inaccessible
	 * methods in a static context.
	 *
	 * This method provides a convenient API for a developer to quickly access
	 * methods on the Memcache instance
	 *
	 * @param    string           The method name being called
	 * @param    array            Parameters passed to the called method
	 * @return   mixed            Returns the value of the intercepted method call
	 */
	public static function __callStatic($method, $parameters)
	{
		return \call_user_func_array(array(static::instance(), $method), $parameters);
	}

	/**
	 * The Memcache connection instance
	 *
	 * @var    Memcache
	 */
	protected static $instance;

	/**
	 * Initializes a new Memcache object instance. This implements the
	 * singleton pattern and can be called from any context and the same object
	 * is returned
	 *
	 * @return   Memcache        An instance to the Memcache object
	 */
	public static function instance()
	{
		if(static::$instance === null)
		{
			static::$instance = static::connect(static::$configuration);
		}

		return static::$instance;
	}

	/**
	 * Create a new Memcache connection instance
	 *
	 * The configuration array passed to this method should be an array of
	 * server hosts/ports, like those defined in the default $configuration
	 * array contained in this class.
	 *
	 * @param    array             The configuration array
	 * @return   Memcache         Returns the newly created Memcache instance
	 */
	public static function connect($servers = array())
	{
		$memcache = new \Memcache;

		foreach((array) $servers as $server)
		{
			$memcache->addServer($server['host'], $server['port'], true, $server['weight']);
		}

		if($memcache->getVersion() === false)
		{
			throw new \RuntimeException('Could not establish a connecton to Memcache.');
		}

		return $memcache;
	}
}

/* End of file Memcache.php */