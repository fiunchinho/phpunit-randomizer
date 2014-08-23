<?php
namespace PHPUnitRandomizer;

class Command extends \PHPUnit_TextUI_Command
{
    public function __construct()
    {
        $this->longOptions['order=']    = 'orderHandler';
    }

    public static function main($exit = TRUE)
    {
        return parent::main($exit);
    }

    /**
     * Only called when 'order' argument is used.
     * 
     * @param  string $order_parameter The order argument passed in command line.
     */
    protected function orderHandler($order_parameter)
    {
        list($order, $seed)         = $this->getOrderAndSeed($order_parameter);
        $this->arguments['order']   = $order;
        $this->arguments['seed']    = $seed;
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
            $seed = $this->getRandomSeed();
        }

        if (!is_numeric($seed)) {
            $this->showError("Could not use '$seed' as seed.");
        }

        return array($order, $seed);
    }

    private function getRandomSeed()
    {
        return rand(0, 9999);
    }

    protected function createRunner()
    {
        return new TestRunner($this->arguments['loader']);
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