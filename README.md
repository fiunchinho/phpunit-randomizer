phpunit-randomizer
==================

A PHPUnit extension that allows you to execute your test cases in a random order. This way you can identify tests that depend on other tests because they share some state, like object state, or even database state.
PHPUnit has an option to execute test cases in process isolation, but that takes a lot of time when you have many tests.

With this library, you don't have to modify any PHPUnit code. Just install it, and use the binary from the vendor folder (vendor/fiunchinho/phpunit-randomizer/bin/phpunit-randomizer) to run your tests, instead of using the default phpunit command.


Installing Dependencies
-----------------------

```bash
$> curl -s https://getcomposer.org/installer | php
$> php composer.phar install
```

Usage
-------
The executable binary is under the bin folder and it works exactly the same as the default phpunit file, accepting the same arguments, except for one: The `order` argument.

```bash
$> bin/phpunit-randomizer -h
```

When you execute your tests using this library, you will get the same results as using a regular PHPUnit installation unless you use the `--order rand` parameter. Try executing the following:

```bash
$> bin/phpunit-randomizer --order rand
```

If you look to the output, you'll see that it says that the tests have been randomized using a random seed. If you don't specify any seed (like the example above), the seed will be calculated randomly every time you execute your tests. But if you want to repeat an specific order that is interesting to you (because it failed, for example), you can re-run that order specifing the seed in your `order` argument.

For example, let's try to execute the example tests (that print the name of the test case so we can see the order in which they are executed) twice, and see how the order differ.

![Executing randomly your tests](http://i.imgur.com/CM3RoQz.png)

Here you can see how the order of the test cases is different in the two executions. Also, you can see the seed used in both cases (8639 in the first one, 4674 in the second). Now, if we want to repeat the order of the second execution, we can select the same seed.

![Executing randomly your tests](http://i.imgur.com/uTWtspK.png)

As you can see, the order is exactly the same.

PHPUnit
------------

- [PHPUnit Documentation](http://phpunit.de/manual/3.7/en/index.html)
