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
        $this->longOptions['seed=']     = 'seedHandler';
        $this->longOptions['order=']    = 'orderHandler';
        $this->seed                     = rand(0, 9999);
    }

    public static function main($exit = TRUE)
    {
        return parent::main($exit);
    }

    protected function orderHandler($order_parameter)
    {
        if (!is_string($order_parameter)) {
            $this->showError(
                sprintf('Could not use "%s" as order.', $order_parameter)
            );
        }

        $order_parts = explode(':', $order_parameter, 2);
        if (count($order_parts) < 2) {
            $this->order                = $order_parts[0];
            $this->arguments['order']   = $this->order;
            if ($this->order !== 'defined') {
                $this->arguments['seed'] = $this->seed;
                if (isset($this->arguments['printer']) && $this->arguments['printer'] instanceof ResultPrinter )
                {
                    $this->arguments['printer']->setSeed($this->seed);
                }
            }

            return;
        }

        list($order, $seed) = $order_parts;
        if ($order !== 'defined' && is_int($seed)) {
            $this->showError(
                sprintf('Could not use "%s" as order.', $order_parameter)
            );
        }

        $this->order                = $order;
        $this->arguments['order']   = $this->order;
        $this->seed                 = $seed;
        $this->arguments['seed']    = $this->seed;

        if (isset($this->arguments['printer']) && $this->arguments['printer'] instanceof ResultPrinter )
        {
            $this->arguments['printer']->setSeed($this->seed);
        }
    }

    protected function seedHandler($seed)
    {
        if (!is_numeric($seed)) {
            $this->showError(
                sprintf('Could not use "%s" as seed.', $seed)
            );
        }

        if (isset($this->arguments['order'])) {
            $this->showError(
                sprintf('You can\'t use the \'order\' and \'seed\' arguments together')
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

    private function showError($message)
    {
        print \PHPUnit_Runner_Version::getVersionString() . "\n\n";
        print $message . "\n";
        exit(\PHPUnit_TextUI_TestRunner::FAILURE_EXIT);
    }
}