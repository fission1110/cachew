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
 * APC driver class
 *
 * @package    Cachew
 * @subpackage Driver
 */
class APC extends Specification
{
	/**
	 * Application specific key to prepend to store item names, preventing
	 * collisions with other applications on the same system.
	 *
	 * @var    string
	 */
	private $key;

	/**
	 * Default constructor, implicitly called on each newly-created object.
	 *
	 * @param    string           Application key
	 * @return   void             No value is returned
	 */
	public function __construct($key = null)
	{
		if(!\extension_loaded('apc'))
		{
			throw new \RuntimeException('Cachew\\Driver\\APC could not locate the APC extension on this system');
		}

		$this->key = $key;
	}

	/**
	 * Determine if an item exists in the cache
	 *
	 * @param    string           The key to check
	 * @return   boolean          Returns true if the cached key exists, otherwise false
	 */
	public function exists($key)
	{
		return \apc_exists($this->key.$key);
	}

	/**
	 * Retrieve an item from the cache
	 *
	 * @param    string           The key to retrieve
	 * @return   mixed|null       Returns the contents of the cached key, or null if empty
	 */
	public function retrieve($key)
	{
		if(($cache = \apc_fetch($this->key.$key)) !== null)
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
		return \apc_store($this->key.$key, $value, ($minutes * 60));
	}

	/**
	 * Delete an item from the cache
	 *
	 * @param    string           The key to delete
	 * @return   boolean          Returns true if the cached key was successfully removed, otherwise false
	 */
	public function forget($key)
	{
		return \apc_delete($this->key.$key);
	}
}

/* End of file APC.php */