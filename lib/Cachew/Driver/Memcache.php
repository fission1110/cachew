<?php

/**
 * Cache driver namespace. This namespace controls the cache driver
 * implementations, as outlined by the abstract cache driver class.
 *
 * @package    Cachew
 * @subpackage Driver
 */
namespace Cachew\Driver;

/**
 * Memcache driver class
 *
 * @see        http://php.net/memcache
 * @package    Cachew
 * @subpackage Driver
 */
class Memcache extends Specification
{
	/**
	 * Application specific key to prepend to store item names, preventing
	 * collisions with other applications on the same system.
	 *
	 * @var    string
	 */
	private $key;

	/**
	 * The Memcache instance to utilize
	 *
	 * @var    \Memcache
	 */
	private $memcache;

	/**
	 * Default constructor, implicitly called on each newly-created object.
	 *
	 * @param    string           Application key
	 * @return   void             No value is returned
	 */
	public function __construct($key = null, \Memcache $memcache)
	{
		$this->key = $key;
		$this->memcache = $memcache;
	}

	/**
	 * Determine if an item exists in the cache
	 *
	 * @param    string           The key to check
	 * @return   boolean          Returns true if the cached key exists, otherwise false
	 */
	public function exists($key)
	{
		return ($this->get($key) !== null);
	}

	/**
	 * Retrieve an item from the cache
	 *
	 * @param    string           The key to retrieve
	 * @return   mixed|null       Returns the contents of the cached key, or null if empty
	 */
	public function retrieve($key)
	{
		if(($cache = $this->memcache->get($this->key.$key)) !== false)
		{
			return $cache;
		}
	}

	/**
	 * Write an item to the cache for a given number of minutes
	 *
	 * @param    string           The key to write
	 * @param    mixed            The data to be stored
	 * @param    integer          The amount of time, in minutes, to store the cached item
	 * @return   boolean          Returns true if the data was successfully cached, otherwise false
	 */
	public function set($key, $value = null, $minutes = 60)
	{
		return $this->memcache->set($this->key.$key, $value, 0, ($minutes * 60));
	}

	/**
	 * Delete an item from the cache
	 *
	 * @param    string           The key to delete
	 * @return   boolean          Returns true if the cached key was successfully removed, otherwise false
	 */
	public function forget($key)
	{
		return $this->memcache->delete($this->key.$key);
	}
}

/* End of file Memcache.php */