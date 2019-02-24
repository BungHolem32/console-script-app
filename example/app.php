<?php

use src\Commands\AveragePingCommand;
use src\Commands\DiskSpaceCommand;
use src\Commands\ExitCommand;
use src\Commands\GoogleSearchCommand;
use src\Kernel;
use src\SocketServer;

require '../vendor/autoload.php';

/**
 * @param \src\SocketClient $client
 *
 * @return bool|void
 */
function onConnect( $client ) {

    $kernel = new Kernel();
    $kernel->registerCommand(new DiskSpaceCommand())
        ->registerCommand(new AveragePingCommand())
        ->registerCommand(new GoogleSearchCommand())
        ->registerCommand(new ExitCommand());

    $menu = $kernel->getMenu();

    //iterate over menu
    while (true) {

        //Print Menu To the user;
        $client->send($menu);
        $string = false;
        $choice = $client->read();
        $command = $kernel->getCommand($choice);

        if (!$command) {
            $client->send("\n Choice {$choice} is out of range, please reEnter one of the following numbers 1 to 4.\n\n");
            continue;
        }

        //Ask User to enter string to search for
        if ($choice == 3) {
            $client->send("Enter string you want to search in google:");
            $string = $client->read();
        }

        //Exit menu and close connection
        if ($choice == 4) {
            $client->send("\n Goodbye \n\n");
            $client->close();
            break;
        }

        $client->send($command->getHandleMessage());
        $output = $command->handle($string);
        $client->send($output);
    }
}

$server = new SocketServer('127.0.0.1','1111',0);
$server->init();
$server->setConnectionHandler('onConnect');
$server->listen();