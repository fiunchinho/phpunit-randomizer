<?php

class ExampleTest extends \PHPUnit_Framework_TestCase
{
	public static $setUpBeforeClass = -1;
	public $setUp;

	public static function setUpBeforeClass()
	{
		self::$setUpBeforeClass = true;
	}

	public static function tearDownAfterClass()
	{
		self::$setUpBeforeClass = false;
	}

	public function setUp()
	{
		$this->setUp = true;
	}

	public function tearDown()
	{
		$this->setUp = false;
	}

	public function test1()
	{
		$this->assertTrue(self::$setUpBeforeClass);
		echo __METHOD__ . "\n";
	}

	public function test2()
	{
		$this->assertTrue($this->setUp);
		print __METHOD__ . "\n";
	}

	public function test3()
	{
		echo __METHOD__ . "\n";
	}

	public function test4()
	{
		echo __METHOD__ . "\n";
	}

	public function test5()
	{
		echo __METHOD__ . "\n";
	}
}