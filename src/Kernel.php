<?php namespace src;

use src\Commands\Command;

/**
 * Class Kernel
 *
 * @package src
 */
class Kernel
{
    /**
     * @var array
     */
    protected $commands = [];

    /**
     * @param Command $command
     *
     * @return Kernel
     */
    public function registerCommand(Command $command)
    {
        array_push($this->commands, $command);

        return $this;
    }

    /**
     * @return string
     */
    public function getMenu(): string
    {
        $output = "************ Console script application ******************\n";
        foreach ($this->commands as $idx => $command) {
            $index  = $idx + 1;
            $output .= "{$index}. {$command->getDescription()} \n";
        }

        $output .= "************ Console script application ******************\n";
        $output .= "Enter your choice from 1 to 4: \n";

        return $output;
    }

    /*
     *
     */
    /**
     * @param $idx
     *
     * @return mixed
     */
    public function getCommand($idx)
    {
        $index = (int) $idx - 1;
        if (!isset($this->commands[$index])) {
            return null;
        }

        return $this->commands[$index];
    }
}