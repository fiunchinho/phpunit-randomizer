phpunit-randomizer
==================

A PHPUnit extension that allows you to execute your test cases in a random order. This way you can identify tests that depend on other tests because they share some state, like object state, or even database state.
PHPUnit has an option to execute test cases in process isolation, but that takes a lot of time when you have many tests.

With this library, you don't have to modify any PHPUnit code. Just install it, and use the executable to run your tests, instead of using the default phpunit command.


Installing Dependencies
-----------------------

```bash
$> curl -s https://getcomposer.org/installer | php
$> php composer.phar install
```

Usage
-------
The executable binary is under the bin folder and it works exactly the same as the default phpunit file, accepting the same arguments

```bash
$> bin/phpunit-randomizer -h
```

PHPUnit
------------

- [PHPUnit Documentation](http://phpunit.de/manual/3.7/en/index.html)
