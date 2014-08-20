<?php
namespace PHPUnitRandomizer;

class TestRunner extends \PHPUnit_TextUI_TestRunner
{
    public function __construct(PHPUnit_Runner_TestSuiteLoader $loader = NULL, PHP_CodeCoverage_Filter $filter = NULL, $seed = null)
    {
        parent::__construct($loader, $filter);
        $this->seed = $seed;
    }

	/**
     * Uses a random test suite to randomize the given test suite, and in case that no printer
     * has been selected, uses printer that shows the random seed used to randomize.
     * 
     * @param  PHPUnit_Framework_Test $suite     TestSuite to execute
     * @param  array                  $arguments Arguments to use
     */
    public function doRun(\PHPUnit_Framework_Test $suite, array $arguments = array())
    {
        $this->handleConfiguration($arguments);
        if
        (
            (isset($arguments['order']) && $arguments['order'] !== 'defined')
            || (isset($arguments['seed']))
        )
        {
            $suite = $this->getRandomTestSuite($suite, $arguments);
        }

        return parent::doRun($suite, $arguments);
    }

    private function getRandomTestSuite($suite, $arguments)
    {
        if ($this->printer === NULL) {
            if (isset($arguments['printer']) &&
                $arguments['printer'] instanceof \PHPUnit_Util_Printer) {
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

        $random_suite   = new Decorator(
            $suite,
            $this->seed,
            $arguments['filter'],
            $arguments['groups'],
            $arguments['excludeGroups'],
            $arguments['processIsolation']
        );

        $suite   = new \PHPUnit_Framework_TestSuite();
        $suite->addTest($random_suite, $arguments['groups']);

        return $suite;
    }
}