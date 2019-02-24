<?php

namespace src\Commands;


interface Command
{
    public function handle($string) : string;

    public function getDescription() : string;

    public function getHandleMessage() : string ;
}