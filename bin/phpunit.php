#!/usr/bin/env php
<?php

define('PHPUnit_MAIN_METHOD', 'PHPUnit_TextUI_Command::main');

if (strpos('@php_bin@', '@php_bin') === 0) {
    require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'PHPUnit' . DIRECTORY_SEPARATOR . 'Autoload.php';
} else {
    require '@php_dir@' . DIRECTORY_SEPARATOR . 'PHPUnit' . DIRECTORY_SEPARATOR . 'Autoload.php';
}

\PHPUnitRandomizer\PHPUnitRandomizerCommand::main();