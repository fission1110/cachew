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
 * Memcached class
 *
 * The Memcached class provides a singleton interface for PHP's Memcached
 * extension, for easily configurable connections!
 *
 * @see        http://php.net/memcached
 * @package    Cachew
 * @subpackage Library
 */
class Memcached
{
	/**
	 * Memcached server configurations
	 *
	 * Memcached is a free and open source, high-performance, distributed memory
	 * object caching system, generic in nature, but intended for use in
	 * speeding up dynamic web applications by alleviating database load.
	 *
	 * For more information about Memcached, check out: http://memcached.org/
	 *
	 * Each value of this array should be an array containing the configurations
	 * described to connect to Memcached in the PHP Manual:
	 * http://php.net/manual/en/memcached.addservers.php
	 *
	 * @var    array
	 */
	public static $configuration = array(

		array(

			/**
			 * The hostname of the memcache server. If the hostname is invalid,
			 * data-related operations will set
			 * `Memcached::RES_HOST_LOOKUP_FAILURE` result code.
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
	 * methods on the Memcached instance
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
	 * The Memcached connection instance
	 *
	 * @var    Memcached
	 */
	protected static $instance;

	/**
	 * Initializes a new Memcached object instance. This implements the
	 * singleton pattern and can be called from any context and the same object
	 * is returned
	 *
	 * @return   Memcached        An instance to the Memcached object
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
	 * Create a new Memcached connection instance
	 *
	 * The configuration array passed to this method should be an array of
	 * server hosts/ports, like those defined in the default $configuration
	 * array contained in this class.
	 *
	 * @param    array             The configuration array
	 * @return   Memcached         Returns the newly created Memcached instance
	 */
	public static function connect($servers = array())
	{
		$memcached = new \Memcached;

		// Remove any duplicate server additions
		// http://us.php.net/manual/en/memcached.addservers.php#102486
		if(\count($serverList = $memcached->getServerList()))
		{
			$servers = \array_diff($serverList, $servers);
		}

		if(\count($servers))
		{
			$memcached->addServers($servers);
		}

		if($memcached->getVersion() === false)
		{
			throw new \RuntimeException('Could not establish a connecton to Memcached.');
		}

		return $memcached;
	}
}

/* End of file Memcached.php */