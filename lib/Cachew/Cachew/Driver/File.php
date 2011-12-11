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
 * File driver class
 *
 * @package    Cachew
 * @subpackage Driver
 */
class File extends \Cachew\Driver
{
	/**
	 * Application specific key to prepend to store item names, preventing
	 * collisions with other applications on the same system.
	 *
	 * @var    string
	 */
	private $key;

	/**
	 * The storeable path for cache files
	 *
	 * @var    string
	 */
	protected $path;

	/**
	 * Default constructor, implicitly called on each newly-created object.
	 *
	 * @param    string           Application key
	 * @return   void             No value is returned
	 */
	public function __construct($key = null, $path)
	{
		$path = \realpath($path);

		if((!\is_dir($path) or $this->is_really_writable($path) === false) and !\mkdir($path))
		{
			throw new \RuntimeException('The storage path "'.$path.'" is not writable');
		}

		if(\substr($path, -1) !== \DIRECTORY_SEPARATOR)
		{
			$path .= \DIRECTORY_SEPARATOR;
		}
		$this->key = $key;
		$this->path = $path;
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
		if(!\file_exists($this->path.$this->key.$key))
		{
			return null;
		}

		// File based caches store have the expiration timestamp stored in
		// UNIX format prepended to their contents. This timestamp is then
		// extracted and removed when the cache is read to determine if the
		// file is still valid
		if(\time() >= \substr($cache = \file_get_contents($this->path.$this->key.$key), 0, 10))
		{
			return $this->forget($key);
		}

		return \unserialize(\substr($cache, 10));
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
		$value = (\time() + ($minutes * 60)).\serialize($value);
		return (bool) \file_put_contents($this->path.$this->key.$key, $value, \LOCK_EX);
	}

	/**
	 * Delete an item from the cache
	 *
	 * @param    string           The key to delete
	 * @return   boolean          Returns true if the cached key was successfully removed, otherwise false
	 */
	public function forget($key)
	{
		if(\file_exists($this->path.$this->key.$key))
		{
			return (bool) @\unlink($this->path.$this->key.$key);
		}

		return true;
	}

	/**
	 * Tests for file writability
	 *
	 * The is_writable() returns true on Windows servers when you really can't
	 * write to the file, based on the read-only attribute. The method is also
	 * unreliable on Unix servers if safe_mode is on.
	 *
	 * @param    string           The path to test
	 * @return   boolean          Returns true if the path is really writable, otherwise false
	 */
	private function is_really_writable($file)
	{
		// If we're on a Unix server with safe_mode off, we can just call
		// is_writable
		if(\DIRECTORY_SEPARATOR == '/' and @\ini_get('safe_mode') == false)
		{
			return \is_writable($file);
		}

		// For Windows servers or safe_mode "On" installations, we'll actually
		// write a file and read it.. Ugh..
		if(\is_dir($file))
		{
			$file = \rtrim($file, \DIRECTORY_SEPARATOR).\DIRECTORY_SEPARATOR.\md5(\mt_rand(1,100).\mt_rand(1,100));

			if(($fp = @\fopen($file, 'ab')) == false)
			{
				return false;
			}

			\fclose($fp);
			@\chmod($file, 0777);
			@\unlink($file);

			return true;
		}
		elseif(!\is_file($file) or ($fp = @\fopen($file, 'ab')) === false)
		{
			return false;
		}

		\fclose($fp);

		return true;
	}
}

/* End of file File.php */