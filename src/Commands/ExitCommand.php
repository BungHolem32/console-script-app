<?php

namespace src\Commands;


/**
 * Class ExitCommand
 *
 * @package src\Commands
 */
class ExitCommand implements Command
{
    /**
     * @var string
     */
    protected $description = 'Exit Menu.';

    /**
     * @var string
     */
    protected $handleMessage = "\n Exiting Menu....\n\n";

    /**
     * @param $string
     *
     * @return string
     */
    public function handle($string): string
    {
        return "\n Goodbye you all. \n\n";
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getHandleMessage(): string
    {
        return $this->handleMessage;
    }
}