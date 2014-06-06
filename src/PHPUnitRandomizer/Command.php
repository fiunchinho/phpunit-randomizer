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
        $this->longOptions['seed='] = 'seedHandler';
        $this->seed         = rand(0, 9999);
    }

    public static function main($exit = TRUE)
    {
        $command = new self;
        return $command->run($_SERVER['argv'], $exit);
    }

    protected function seedHandler($seed)
    {
        if (!is_numeric($seed)) {
            \PHPUnit_TextUI_TestRunner::showError(
                sprintf('Could not use "%s" as seed.', $seed)
            );
        }

        $this->seed         = intval($seed);
        $this->arguments['seed']  = $this->seed;

        if (isset($this->arguments['printer']) && $this->arguments['printer'] instanceof ResultPrinter )
        {
            $this->arguments['printer']->setSeed($this->seed);
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