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
        $this->order                    = 'defined';
    }

    public static function main($exit = TRUE)
    {
        return parent::main($exit);
    }

    private function setSeedToPrinter($seed)
    {
        if (isset($this->arguments['printer']) && $this->arguments['printer'] instanceof ResultPrinter )
        {
            $this->arguments['printer']->setSeed($seed);
        }
    }

    /**
     * Parses arguments to know if random order is desired, and if seed was chosen.
     * 
     * @param  string $order String from command line parameter.
     * @return array
     */
    private function getOrderAndSeed($order)
    {
        @list($order, $seed) = explode(':', $order, 2);

        if (empty($seed)) {
            $seed = $this->seed;
        }

        if (!is_numeric($seed)) {
            $this->showError("Could not use '$seed' as seed.");
        }

        return array($order, $seed);
    }

    protected function orderHandler($order_parameter)
    {
        list($order, $seed)         = $this->getOrderAndSeed($order_parameter);
        $this->order                = $order;
        $this->arguments['order']   = $order;
        $this->seed                 = $seed;
        $this->arguments['seed']    = $seed;
        $this->setSeedToPrinter($this->seed);
    }

    protected function createRunner()
    {
        return new TestRunner($this->arguments['loader'], null, $this->seed);
    }

    public function showHelp()
    {
        parent::showHelp();

        print <<<EOT

  --order <rand[:seed]>     Randomize the order of the tests. Optionally you can pass a seed to run a specific order.

EOT;
    }

    private function showError($message)
    {
        print \PHPUnit_Runner_Version::getVersionString() . "\n\n";
        print $message . "\n";
        exit(\PHPUnit_TextUI_TestRunner::FAILURE_EXIT);
    }
}