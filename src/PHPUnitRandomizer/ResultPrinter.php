<?php

namespace PHPUnitRandomizer;

class ResultPrinter extends \PHPUnit_TextUI_ResultPrinter {

    protected $seed = null;

    public function setSeed($seed)
    {
        $this->seed = $seed;
    }

    /**
     * Just add to the output the seed used to randomize the test suite.
     * 
     * @param  PHPUnit_Framework_TestResult $result
     */
    protected function printFooter(\PHPUnit_Framework_TestResult $result)
    {
        parent::printFooter($result);

        $this->writeNewLine();
        $this->write("Randomized with seed {$this->seed}");
        $this->writeNewLine();
    }
} 