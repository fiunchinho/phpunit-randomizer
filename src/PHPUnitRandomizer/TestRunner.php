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
        $this->handleConfiguration($arguments);

        if ($this->printer === NULL) {
            if (isset($arguments['printer']) &&
                $arguments['printer'] instanceof PHPUnit_Util_Printer) {
                $this->printer = $arguments['printer'];
            } else {
                $this->printer = new ResultPrinter(
                  NULL,
                  $arguments['verbose'],
                  $arguments['colors'],
                  $arguments['debug']
                );
                $this->printer->setSeed($this->seed);
            }
        }

        $test 	= new Decorator(
            $suite,
            $this->seed,
            $arguments['filter'],
            $arguments['groups'],
            $arguments['excludeGroups'],
            $arguments['processIsolation']
        );
        $suite 	= new \PHPUnit_Framework_TestSuite();
        $suite->addTest($test, $arguments['groups']);

        parent::doRun( $suite, $arguments );
    }
}