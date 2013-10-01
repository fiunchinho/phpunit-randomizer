<?php

class ExampleTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		echo "SetUp\n";
	}

	public function tearDown()
	{
		echo "TearDown\n\n";
	}

	public function test1()
	{
		echo "Test Number \t1\n";
	}

	public function test2()
	{
		echo "Test Number \t2\n";
	}

	public function test3()
	{
		echo "Test Number \t3\n";
	}

	public function test4()
	{
		echo "Test Number \t4\n";
	}

	public function test5()
	{
		echo "Test Number \t5\n";
	}
}