<?php
namespace PHPUnitRandomizer;

class PHPUnitRandomizerCommand extends \PHPUnit_TextUI_Command
{
	public static function main($exit = TRUE)
    {
        $command = new self;
        return $command->run($_SERVER['argv'], $exit);
    }

	protected function createRunner()
	{
		return new PHPUnitRandomizerTestRunner($this->arguments['loader']);
	}
}