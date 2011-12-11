<?php

namespace Cachew\Driver;

/**
 * Test class for APC
 *
 * @pacakge    Cachew
 * @subpackage Tests
 */
class APCTest extends \PHPUnit_Framework_TestCase
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
	 * @var    APC
	 */
	protected $object;

	/**
	 * Sets up the fixture
	 *
	 * @return   void             No value is returned
	 */
	protected function setUp()
	{
		$this->key    = 'cachewTests_';
		$this->object = new APC($this->key);
	}

	/**
	 * @covers   APC::set
	 * @return   void             No value is returned
	 */
	public function testSet()
	{
		$this->assertTrue($this->object->set('exists', 'true'));
	}

	/**
	 * @covers   APC::exists
	 * @return   void             No value is returned
	 */
	public function testExists()
	{
		$this->assertFalse($this->object->exists('not-exists'));
		$this->assertTrue($this->object->exists('exists'));
	}

	/**
	 * @covers   APC::retrieve
	 * @return   void             No value is returned
	 */
	public function testRetrieve()
	{
		$this->assertEquals($this->object->retrieve('exists'), 'true');
	}

	/**
	 * @covers   APC::forget
	 * @return   void             No value is returned
	 */
	public function testForget()
	{
		$this->assertTrue($this->object->forget('exists'));
		$this->assertTrue($this->object->forget('exists'));
	}

	/**
	 * @covers   APC::exists
	 * @return   void             No value is returned
	 */
	public function testExistsAfterForget()
	{
		$this->assertFalse($this->object->exists('not-exists'));
		$this->assertFalse($this->object->exists('exists'));
	}
}

/* End of file APCTest.php */