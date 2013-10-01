<?php
namespace PHPUnitRandomizer;

class PHPUnitRandomizerTestRunner extends \PHPUnit_TextUI_TestRunner
{
	public function doRun(\PHPUnit_Framework_Test $suite, array $arguments = array())
    {
        $test 	= new PHPUnitRandomizerDecorator( $suite );
        $suite 	= new \PHPUnit_Framework_TestSuite();
        $suite->addTest($test);

        parent::doRun( $suite, $arguments );
    }
}