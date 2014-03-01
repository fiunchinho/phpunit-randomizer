<?php

namespace PHPUnitRandomizer;

class ResultPrinter extends \PHPUnit_TextUI_ResultPrinter {

    protected $seed = null;

    public function setSeed($seed)
    {
        $this->seed = $seed;
    }

    protected function printFooter(\PHPUnit_Framework_TestResult $result)
    {
        parent::printFooter($result);

        $this->writeNewLine();
        $this->write("Randomized with seed {$this->seed}");
        $this->writeNewLine();
    }
} 