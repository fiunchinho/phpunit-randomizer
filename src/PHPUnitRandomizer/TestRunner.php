<?php
namespace PHPUnitRandomizer;

class TestRunner extends \PHPUnit\TextUI\TestRunner
{
    /**
     * Uses a random test suite to randomize the given test suite, and in case that no printer
     * has been selected, uses printer that shows the random seed used to randomize.
     *
     * @param  PHPUnit\Framework\Test $suite     TestSuite to execute
     * @param  array                  $arguments Arguments to use
     */
    public function doRun(\PHPUnit\Framework\Test $suite, array $arguments = array(), $exit = true)
    {
        $localArguments = $arguments;

        $this->handleConfiguration($localArguments);
        if (isset($localArguments['order']))
        {
            $this->addPrinter($localArguments);
            $randomizer = new Randomizer();
            $randomizer->randomizeTestSuite($suite, $localArguments['seed']);
        }

        return parent::doRun($suite, $arguments);
    }

    /**
     * Sets printer to show the seed used to randomize the TestSuite.
     * @param array $arguments Arguments to use.
     */
    private function addPrinter($arguments)
    {
        $this->printer = new ResultPrinter(
            NULL,
            $arguments['verbose'],
            $arguments['colors'],
            $arguments['debug'],
            $arguments['seed']
        );

        if (isset($arguments['printer']) &&
            $arguments['printer'] instanceof \PHPUnit\Util\Printer) {
            $this->printer = $arguments['printer'];
        }
    }
}
