<?php

namespace Cachew\Driver;

/**
 * Test class for File
 *
 * @pacakge    Cachew
 * @subpackage Tests
 */
class FileTest extends \PHPUnit_Framework_TestCase
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
	 * @var    File
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
		$this->object = new File($this->key, __DIR__);
	}

	/**
	 * Tears down the fixture
	 *
	 * @return   void             No value is returned
	 */
	protected function tearDown()
	{
		// Removes the left over file, incase forgetting it didn't work
		@unlink(__DIR__.\DIRECTORY_SEPARATOR.$this->key.'exists');
	}

	/**
	 * @covers   File::set
	 * @return   void             No value is returned
	 */
	public function testSet()
	{
		$this->assertTrue($this->object->set('exists', 'true'));
	}

	/**
	 * @covers   File::exists
	 * @return   void             No value is returned
	 */
	public function testExists()
	{
		$this->assertFalse($this->object->exists('not-exists'));
		$this->assertTrue($this->object->exists('exists'));
	}

	/**
	 * @covers   File::retrieve
	 * @return   void             No value is returned
	 */
	public function testRetrieve()
	{
		$this->assertEquals($this->object->retrieve('exists'), 'true');
	}

	/**
	 * @covers   File::forget
	 * @return   void             No value is returned
	 */
	public function testForget()
	{
		$this->assertTrue($this->object->forget('exists'));
		$this->assertTrue($this->object->forget('exists'));
	}

	/**
	 * @covers   File::exists
	 * @return   void             No value is returned
	 */
	public function testExistsAfterForget()
	{
		$this->assertFalse($this->object->exists('not-exists'));
		$this->assertFalse($this->object->exists('exists'));
	}
}

/* End of file FileTest.php */