<?php
namespace PHPUnitRandomizer;

class Decorator extends \PHPUnit_Extensions_TestDecorator
{
    protected $filter = FALSE;
    protected $groups = array();
    protected $excludeGroups = array();
    protected $processIsolation = FALSE;

    public function __construct(\PHPUnit_Framework_Test $test, $seed = null, $filter = FALSE, array $groups = array(), array $excludeGroups = array(), $processIsolation = FALSE)
    {
        $tests = $this->randomizeTests( $test->tests(), $seed );
        $suite = $this->createTestSuite( $tests );

        parent::__construct($suite);

        $this->filter           = $filter;
        $this->groups           = $groups;
        $this->excludeGroups    = $excludeGroups;
        $this->processIsolation = $processIsolation;
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

    public function run(\PHPUnit_Framework_TestResult $result = NULL)
    {
        if ($result === NULL) {
            $result = $this->createResult();
        }

        $this->test->run(
            $result,
            $this->filter,
            $this->groups,
            $this->excludeGroups,
            $this->processIsolation
        );

        return $result;
    }
}