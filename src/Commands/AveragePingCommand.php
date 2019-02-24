<?php
/**
 * Created by PhpStorm.
 * User: ilanvac
 * Date: 2/23/2019
 * Time: 6:57 PM
 */

namespace src\Commands;

/**
 * Class AveragePingCommand
 *
 * @package src\Commands
 */
class AveragePingCommand implements Command
{
    /**
     * @var string
     */
    protected $description = 'Get average ping result from ip (8.8.8.8).';

    /**
     * @var string
     */
    protected $handleMessage = "\n Calculating 8.8.8.8 ping average....\n\n";

    /**
     * @param $string
     *
     * @return string
     */
    public function handle($string): string
    {
        $output = '';
        $output .= "\n Ping average is: ";
        $output .= exec("ping -c 4 8.8.8.8 | tail -1| awk '{print $4}' | cut -d '/' -f 2 ");
        $output .= "\n\n";

        return $output;
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
    public  function getHandleMessage(): string {
        return $this->handleMessage;
    }
}