<?php

namespace src;


/**
 * Class SocketClient
 *
 * @package src
 */
class SocketClient
{
    /**
     * @var
     */
    private $connection;
    /**
     * @var string
     */
    private $address;
    /**
     * @var string
     */
    private $port;

    /**
     * SocketClient constructor.
     *
     * @param $connection
     */
    public function __construct( $connection ) {
        $address = '';
        $port = '';
        socket_getsockname($connection, $address, $port);
        $this->address = $address;
        $this->port = $port;
        $this->connection = $connection;
    }

    /**
     * @param $message
     */
    public function send( $message ) {
        socket_write($this->connection, $message, strlen($message));
    }

    /**
     * @param int $len
     *
     * @return string|null
     */
    public function read($len = 1024) {
        if ( ( $buf = @socket_read( $this->connection, $len, PHP_BINARY_READ  ) ) === false ) {
            return null;
        }

        return trim($buf);
    }

    /**
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getPort() {
        return $this->port;
    }

    /**
     *
     */
    public function close() {
        socket_shutdown( $this->connection );
        socket_close( $this->connection );
    }
}