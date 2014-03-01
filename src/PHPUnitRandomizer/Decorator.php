<?php
namespace PHPUnitRandomizer;

class Decorator extends \PHPUnit_Extensions_TestDecorator
{
    public function __construct(\PHPUnit_Framework_Test $test, $seed = null)
    {
        $tests = $this->randomizeTests( $test->tests(), $seed );
        $suite = $this->createTestSuite( $tests );

        parent::__construct($suite);
    }

    private function randomizeTests( array $tests, $seed )
    {
        if ($seed)
        {
            srand($seed);
        }

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