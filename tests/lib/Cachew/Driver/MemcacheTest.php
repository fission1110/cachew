<?php

namespace Cachew\Driver;

/**
 * Test class for Memcache
 *
 * @pacakge    Cachew
 * @subpackage Tests
 */
class MemcacheTest extends \PHPUnit_Framework_TestCase
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
	 * @var    Memcache
	 */
	protected $object;

	/**
	 * Sets up the fixture
	 *
	 * @return   void             No value is returned
	 */
	protected function setUp()
	{
		$memcache = new \Memcache;
		$memcache->addServer('localhost', 11211);

		$this->key    = 'cachewTests_';
		$this->object = new Memcache($this->key, __DIR__);
	}

	/**
	 * @covers   Memcache::set
	 * @return   void             No value is returned
	 */
	public function testSet()
	{
		$this->assertTrue($this->object->set('exists', 'true'));
	}

	/**
	 * @covers   Memcache::exists
	 * @return   void             No value is returned
	 */
	public function testExists()
	{
		$this->assertFalse($this->object->exists('not-exists'));
		$this->assertTrue($this->object->exists('exists'));
	}

	/**
	 * @covers   Memcache::retrieve
	 * @return   void             No value is returned
	 */
	public function testRetrieve()
	{
		$this->assertEquals($this->object->retrieve('exists'), 'true');
	}

	/**
	 * @covers   Memcache::forget
	 * @return   void             No value is returned
	 */
	public function testForget()
	{
		$this->assertTrue($this->object->forget('exists'));
		$this->assertTrue($this->object->forget('exists'));
	}

	/**
	 * @covers   Memcache::exists
	 * @return   void             No value is returned
	 */
	public function testExistsAfterForget()
	{
		$this->assertFalse($this->object->exists('not-exists'));
		$this->assertFalse($this->object->exists('exists'));
	}
}

/* End of file MemcacheTest.php */