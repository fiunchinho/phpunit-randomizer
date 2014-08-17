<?php
namespace PHPUnitRandomizer;

class TestSuite extends \PHPUnit_Framework_TestSuite
{
	/**
	 * For some reason, PHPUnit only executes setUpBeforeClass and tearDownAfterClass
	 * only when testCase is set to true. So we manually set it here.
	 */
	public function __construct()
	{
		$this->testCase = true;
	}
}