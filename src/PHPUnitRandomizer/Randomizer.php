<?php

namespace PHPUnitRandomizer;

class Randomizer
{
    /**
     * Order the TestSuite tests in a random order.
     *
     * @param  \PHPUnit\Framework\Test $suite The suite to randomize.
     * @param  integer                 $seed  Seed used for PHP to randomize the suite.
     * @return \PHPUnit\Framework\Test
     */
    public function randomizeTestSuite(\PHPUnit\Framework\Test $suite, $seed)
    {
        if ($this->testSuiteContainsOtherSuites($suite))
        {
            $this->randomizeSuiteThatContainsOtherSuites($suite, $seed);
        }
        else
        {
            $this->randomizeSuite($suite, $seed, 0);
        }

        return $suite;
    }

    /**
     * Randomize each Test Suite inside the main Test Suite.
     *
     * @param  [type] $suite Main Test Suite to randomize.
     * @param  [type] $seed  Seed to use.
     * @return \PHPUnit\Framework\Test
     */
    private function randomizeSuiteThatContainsOtherSuites($suite, $seed)
    {
        $order = 0;
        foreach ($suite->tests() as $test) {
            $this->randomizeSuite($test, $seed, $order);
            $order++;
        }
        return $this->randomizeSuite($suite, $seed, $order, false);
    }

    /**
     * Test Suites can contain other Test Suites or just Test Cases.
     *
     * @param  \PHPUnit\Framework\Test $suite [description]
     * @return Boolean
     */
    private function testSuiteContainsOtherSuites($suite)
    {
        $tests = $suite->tests();
        return isset($tests[0]) && $tests[0] instanceof \PHPUnit\Framework\TestSuite;
    }

    /**
     * Randomize the test cases inside a TestSuite, with the given seed.
     *
     * @param  \PHPUnit\Framework\Test     $suite Test suite to randomize.
     * @param  integer                     $seed  Seed to be used for the random funtion.
     * @param  integer                     $order Arbitrary value to "salt" the seed.
     * @param  bool                        $fix_depends [=false]
     * @return \PHPUnit\Framework\Test
     */
    private function randomizeSuite($suite, $seed, $order = 0, $fix_depends = true)
    {
        $reflected = new \ReflectionObject($suite);
        $property = $reflected->getProperty('tests');
        $property->setAccessible(true);
        $property->setValue($suite, $this->randomizeTestsCases($suite->tests(), $seed, $order, $fix_depends));

        return $suite;
    }

    /**
     * Randomize an array of TestCases.
     *
     * @param  array    $tests       TestCases to randomize.
     * @param  integer  $seed        Seed used for PHP to randomize the array.
     * @param  integer  $order       A salt so it doesn't randomize all the classes in the same "random" order.
     * @param  bool     $fix_depends [=false]
     * @return array    Randomized array
     */
    private function randomizeTestsCases(array $tests, $seed, $order, $fix_depends = false)
    {
        srand($seed + $order);
        shuffle($tests);
        return $fix_depends ? $this->fixDependencies($tests) : $tests;
    }

    /**
     * fix tests order because of @depends annotations
     *
     * @param  array     $tests  TestCases to randomize.
     * @return array     Fixed   randomized array
     */
    private function fixDependencies(array $tests)
    {
        $tests_dependencies = $tests_methods = [];

        foreach ($tests as $i => $test) {
            $reflected = new \ReflectionObject($test);
            $name_field = $dependencies_field = null;

            while ($reflected = $reflected->getParentClass()) {
                try {
                    if (empty($dependencies_field)) {
                        $dependencies_field = $reflected->getProperty('dependencies');
                        $dependencies_field->setAccessible(true);
                    }
                    if (empty($name_field)) {
                        $name_field = $reflected->getProperty('name');
                        $name_field->setAccessible(true);
                    }
                } catch (\ReflectionException $e ){
                    //do nothing
                }
            }

            if (empty($name_field)) {
                return $tests;
            }

            $tests_methods[$name_field->getValue($test)] = $i;

            if (empty($dependencies = $dependencies_field->getValue($test))){
                continue;
            }
            foreach ($dependencies as $dependency) {
                $tests_dependencies[$name_field->getValue($test)] = $dependency;
            }
        }

        if (empty($tests_dependencies)) {
            return $tests;
        }

        $new_order = $this->setOrder($tests_dependencies, $tests_methods);
        $new_tests_order = [];
        foreach ($new_order as $test_name) {
            $new_tests_order[] = $tests[$tests_methods[$test_name]];
        }

        return $new_tests_order;
    }

    /**
     * preapare test methods required order
     *
     * @param  array     $tests_dependencies  tests dependencies array
     * @param  array     $tests_methods       tests (shuffled) array
     * @return array     array
     */
    private function setOrder($tests_dependencies, $tests_methods)
    {
        $new_order = [];
        foreach ($tests_methods as $method_name => $order) {
            if (isset($tests_dependencies[$method_name]) && !in_array($tests_dependencies[$method_name], $new_order)) {
                $new_order[] = $tests_dependencies[$method_name];
                $this->isDependant($new_order, $tests_dependencies, $tests_methods, $tests_dependencies[$method_name]);
            }

            if (!in_array($method_name, $new_order)) {
                $new_order[] = $method_name;
            }
        }

        return $new_order;
    }

    /**
     * check if dependant method has another dependant method (recursive)
     *
     * @param  array     $new_order           tests fixed (shuflled, with dependencies) array
     * @param  array     $tests_dependencies  tests dependencies array
     * @param  array     $tests_methods       tests (shuffled) array
     * @return void
     */
    private function isDependant(&$new_order, $tests_dependencies, $tests_methods, $method)
    {
        foreach ($tests_dependencies as $dependant => $depends) {
            if ($method == $dependant && !in_array($depends, $new_order)) {
                array_splice($new_order, array_search($method, $new_order), 0, [$depends]);
                $this->isDependant($new_order, $tests_dependencies, $tests_methods, $depends);
            }
        }
    }

}
