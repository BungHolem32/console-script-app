<?php

namespace src\Exceptions;


use Exception;

/**
 * Class CommandException
 *
 * @package src\Exceptions
 */
class CommandException extends Exception
{
    /**
     *
     */
    const CHOICE_OUT_OF_RANGE = 1;

    /**
     * @var array
     */
    public $messages = array(
        self::CHOICE_OUT_OF_RANGE => "\n Choice \"%s\" is out of range, please reEnter one of the following numbers 1 to 4.\n\n",
    );

    /**
     * CommandException constructor.
     *
     * @param      $code
     * @param bool $params
     */
    public function __construct( $code, $params = false ) {
        if( $params ) {
            $args = array( $this->messages[ $code ], $params );
            $message = call_user_func_array('sprintf', $args );
        }
        else {
            $message = $this->messages[ $code ];
        }

        parent::__construct( $message, $code );
    }
}