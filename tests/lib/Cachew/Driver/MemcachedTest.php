<?php

namespace Cachew\Driver;

/**
 * Test class for Memcached
 *
 * @pacakge    Cachew
 * @subpackage Tests
 */
class MemcachedTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Cache test key
	 *
	 * @var    string
	 */
	protected $key;

	/**
	 * Test object instance
	 *
	 * @var    Memcached
	 */
	protected $object;

	/**
	 * Sets up the fixture
	 *
	 * @return   void             No value is returned
	 */
	protected function setUp()
	{
		$Memcached = new \Memcached;
		$Memcached->addServer('localhost', 11211);

		$this->key    = 'cachewTests_';
		$this->object = new Memcached($this->key, __DIR__);
	}

	/**
	 * @covers   Memcached::set
	 * @return   void             No value is returned
	 */
	public function testSet()
	{
		$this->assertTrue($this->object->set('exists', 'true'));
	}

	/**
	 * @covers   Memcached::exists
	 * @return   void             No value is returned
	 */
	public function testExists()
	{
		$this->assertFalse($this->object->exists('not-exists'));
		$this->assertTrue($this->object->exists('exists'));
	}

	/**
	 * @covers   Memcached::retrieve
	 * @return   void             No value is returned
	 */
	public function testRetrieve()
	{
		$this->assertEquals($this->object->retrieve('exists'), 'true');
	}

	/**
	 * @covers   Memcached::forget
	 * @return   void             No value is returned
	 */
	public function testForget()
	{
		$this->assertTrue($this->object->forget('exists'));
		$this->assertTrue($this->object->forget('exists'));
	}

	/**
	 * @covers   Memcached::exists
	 * @return   void             No value is returned
	 */
	public function testExistsAfterForget()
	{
		$this->assertFalse($this->object->exists('not-exists'));
		$this->assertFalse($this->object->exists('exists'));
	}
}

/* End of file MemcachedTest.php */