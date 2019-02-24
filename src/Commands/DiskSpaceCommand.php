<?php namespace src\Commands;

/**
 * Class DiskSpaceCommand
 *
 * @package src\Commands
 */
class DiskSpaceCommand implements Command
{
    /**
     * @var string
     */
    protected $description = 'Get total disk space on server.';

    /**
     * @var string
     */
    protected $handleMessage = "\n Calculating disk space....\n\n";


    /**
     * @param $string
     *
     * @return string
     */
    public function handle($string): string
    {
        $output = '';
        $diskTotalSpace = disk_total_space("/var/www");
        $diskTotalSpace = $this->formatSize($diskTotalSpace);
        $output .=  $diskTotalSpace . "\n";

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
     * @param $bytes
     *
     * @return string
     */
    public function formatSize($bytes){
        $types = array( 'B', 'KB', 'MB', 'GB', 'TB' );
        for( $i = 0; $bytes >= 1024 && $i < ( count( $types ) -1 ); $bytes /= 1024, $i++ );

        return( round( $bytes, 2 ) . " " . $types[$i] );
    }

    public function getHandleMessage(): string
    {
        return $this->handleMessage;
    }
}