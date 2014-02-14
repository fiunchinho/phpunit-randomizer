<?php
namespace PHPUnitRandomizer;

class Decorator extends \PHPUnit_Extensions_TestDecorator
{
    protected $seed = null;

    public function __construct(\PHPUnit_Framework_Test $test, $seed = null)
    {
        $this->seed = $seed;
        $tests = $this->randomizeTests( $test->tests() );
        $suite = $this->createTestSuite( $tests );

        parent::__construct($suite);
    }

    private function randomizeTests( array $tests )
    {
        srand($this->seed);

        $shuffle = array();
        foreach ( $tests as $t )
        {
            if ($t instanceof \PHPUnit_Framework_TestSuite) {
                $shuffle = array_merge($shuffle, $t->tests());
            } else {
                $shuffle[] = $t;
            }
        }

        shuffle($shuffle);

        return $shuffle;
    }

    private function createTestSuite( array $tests )
    {
        $suite = new \PHPUnit_Framework_TestSuite();
        foreach ( $tests as $t ) $suite->addTest($t);

        return $suite;
    }
}