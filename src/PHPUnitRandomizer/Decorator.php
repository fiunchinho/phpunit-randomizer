<?php
namespace PHPUnitRandomizer;

class Decorator extends \PHPUnit_Extensions_TestDecorator
{
    protected $filter;
    protected $groups;
    protected $excludeGroups;
    protected $processIsolation;
    protected $seed;

    public function __construct(\PHPUnit_Framework_Test $test, $seed = null, $filter = FALSE, array $groups = array(), array $excludeGroups = array(), $processIsolation = FALSE)
    {
        $this->filter           = $filter;
        $this->groups           = $groups;
        $this->excludeGroups    = $excludeGroups;
        $this->processIsolation = $processIsolation;
        $this->seed             = $seed;

        $test = $this->randomizeTestSuite($test, $seed);

        return parent::__construct($test);
    }

    /**
     * Creates a new TestSuite with given TestCases in a random order.
     *
     * @param  PHPUnit_Framework_TestSuite  $tests Former TestSuite.
     * @param  int                          $seed  Seed to use when ordering.
     * @param  int                          $order Salt for the seed.
     * @return PHPUnit_Framework_TestSuite        New TestSuite with random order.
     */
    private function createNewRandomTestSuite(\PHPUnit_Framework_TestSuite $suite, $seed, $order)
    {
        $shuffle = array();
        foreach ($suite->tests() as $t)
        {
            $shuffle[] = $t;
        }

        return $this->createTestSuite($this->randomizeTests($shuffle, $seed, $order));
    }

    /**
     * Randomize the order of the TestCases inside a TestSuite, using a given seed.
     * 
     * @param  PHPUnit_Framework_TestSuite  $suite The suite to randomize.
     * @param  int                          $seed  Seed to use for PHP.
     * @return PHPUnit_Framework_TestSuite         Randomized suite.
     */
    private function randomizeTestSuite( $suite, $seed )
    {
        $empty_test_suite = new \PHPUnit_Framework_TestSuite($suite->getName());

        $test_cases = array();
        $order = 0;
        foreach ($suite->tests() as $test)
        {
            if ($test instanceof \PHPUnit_Framework_TestSuite)
            {
                $random_test_suite = $this->createNewRandomTestSuite($test, $seed, $order);
                $random_test_suite->setName($test->getName());
                $empty_test_suite->addTestSuite($random_test_suite);
                $order++;
            }
            else
            {
                $test_cases[] = $test;
            }
        }

        if (!empty($test_cases))
        {
            $random_test_suite = $this->createTestSuite($this->randomizeTests($test_cases, $seed, $order));
            $random_test_suite->setName($suite->getName());

            return $random_test_suite;
        }

        return $empty_test_suite;
    }

    /**
     * Randomize an array of TestCases.
     *
     * @param  array    $tests  TestCases to randomize.
     * @param  int      $seed   Seed used for PHP to randomize the array.
     * @param  int      $order  A salt so it doesn't randomize all the classes in the same "random" order.
     * @return array            Randomized array
     */
    private function randomizeTests(array $tests, $seed, $order)
    {
        srand($seed + $order);
        shuffle($tests);
        return $tests;
    }

    /**
     * Creates a new test suite with the array of tests from the arguments.
     * 
     * @param  array    $tests  An array of TestCases
     * @return TestSuite        The new test suite.
     */
    private function createTestSuite(array $tests)
    {
        $suite = new TestSuite();
        foreach ($tests as $t)
        {
            $suite->addTest($t);
        }

        return $suite;
    }
}