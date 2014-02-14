<?php
namespace PHPUnitRandomizer;

class TestRunner extends \PHPUnit_TextUI_TestRunner
{
    public function __construct(PHPUnit_Runner_TestSuiteLoader $loader = NULL, PHP_CodeCoverage_Filter $filter = NULL, $seed = null)
    {
        parent::__construct($loader, $filter);
        $this->seed = $seed;
    }

	public function doRun(\PHPUnit_Framework_Test $suite, array $arguments = array())
    {
        $test 	= new Decorator($suite, $this->seed);
        $suite 	= new \PHPUnit_Framework_TestSuite();
        $suite->addTest($test);

        parent::doRun( $suite, $arguments );
    }
}