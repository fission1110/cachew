<?php

/**
 * Cachew namespace. This namespace contains the driver specification absract
 * class for cache drivers to handle data within a cache.
 *
 * @package    Cachew
 * @subpackage Cachew
 */
namespace Cachew;

/**
 * Abstract cache driver class
 *
 * The abstract cache driver abstract class outlines the methods that should
 * exist in every cache driver, ensuring consistent functionality for each
 * cache type. Additionally, this abstract class attempts to implement common
 * functionality to remove duplication between drivers.
 *
 * @pacakge    Cachew
 * @subpackage Cachew
 */
abstract class Driver
{
	/**
	 * Get an item from the cache. If the item doesn't exist in the cache,
	 * return a default value.
	 *
	 * @param    string           The cached item key
	 * @param    mixed            A default value to return, if the item does not exist
	 * @return   mixed            Returns the value of the cached item, or $default if it does not exist
	 */
	public function get($key, $default = null)
	{
		if(($item = $this->retrieve($key)) !== null)
		{
			return $item;
		}

		if($default instanceof \Closure)
		{
			$default = $default();
		}

		return $default;
	}

	/**
	 * Get an item from the cache. If the item doesn't exist in the cache, store
	 * the default value in the cache.
	 *
	 * @param    string           The cached item key
	 * @param    mixed            A default value to store, if the item does not exist
	 * @param    integer          The amount of time, in minutes, to store the cached item
	 * @return   mixed            Returns the value of the cached item
	 */
	public function remember($key, $default, $minutes = 60)
	{
		if(($item = $this->retrieve($key)) !== null)
		{
			return $item;
		}

		if($default instanceof \Closure)
		{
			$default = $default();
		}

		$this->put($key, $default, $minutes);

		return $default;
	}

	/**
	 * Determine if an item exists in the cache
	 *
	 * @param    string           The key to check
	 * @return   boolean          Returns true if the cached key exists, otherwise false
	 */
	abstract public function exists($key);

	/**
	 * Retrieve an item from the cache
	 *
	 * @param    string           The key to retrieve
	 * @return   mixed|null       Returns the contents of the cached key, or null if empty
	 */
	abstract public function retrieve($key);

	/**
	 * Write an item to the cache for a given number of minutes
	 *
	 * @param    string           The key to write
	 * @param    mixed            The data to be stored
	 * @param    integer          The amount of time, in minutes, to store the cached item
	 * @return   boolean          Returns true if the data was successfully cached, otherwise false
	 */
	abstract public function set($key, $value = null, $minutes = 60);

	/**
	 * Delete an item from the cache
	 *
	 * @param    string           The key to delete
	 * @return   boolean          Returns true if the cached key was successfully removed, otherwise false
	 */
	abstract public function forget($key);
}

/* End of file Driver.php */