<?php
namespace PHPUnitRandomizer;

class Command extends \PHPUnit_TextUI_Command
{
	public static function main($exit = TRUE)
    {
        $command = new self;
        return $command->run($_SERVER['argv'], $exit);
    }

	protected function createRunner()
	{
		return new TestRunner($this->arguments['loader']);
	}
}