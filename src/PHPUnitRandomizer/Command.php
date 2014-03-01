<?php
namespace PHPUnitRandomizer;

class Command extends \PHPUnit_TextUI_Command
{
    /**
     * Seed for the randomizer.
     *
     * Defaults to -1 until customized or when default value gets set.
     *
     * @var int
     */
    protected $seed = -1;

    public function __construct()
    {
        $this->longOptions['seed='] = 'seed';
    }

	public static function main($exit = TRUE)
    {
        $command = new self;
        return $command->run($_SERVER['argv'], $exit);
    }

    protected function seed($seed)
    {
        if (!is_numeric($seed)) {
            echo("Invalid seed number, seed must be an integer." . PHP_EOL);
            exit(\PHPUnit_TextUI_TestRunner::FAILURE_EXIT);
        }

        $this->seed = intval($seed);
    }

    protected function handleArguments(array $argv)
    {
        parent::handleArguments($argv);

        if ($this->seed === -1) {
            // Default seed for randomizer, unless overridden.
            // Used for printing the seed so it can be re-used.
            $this->seed = rand(0, 9999);
        }

        if (!isset($this->arguments['printer'])) {
            $printer = new ResultPrinter();
            $printer->setSeed($this->seed);
            $this->arguments['printer'] = $printer;
        }
    }

    protected function createRunner()
    {
        return new TestRunner($this->arguments['loader'], null, $this->seed);
    }

    public function showHelp()
    {
        parent::showHelp();

        print <<<EOT

  --seed <seed>             Seed the randomizer with a specific seed.

EOT;
    }
}