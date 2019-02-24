<?php namespace src;


use src\Exceptions\SocketException;

/**
 * Class SocketServer
 *
 * @package App\Models
 */
class SocketServer extends Connection
{
    /**
     * @var
     */
    protected $spawn;

    /**
     * @var
     */
    protected $connectionHandler;

    /**
     */
    public function init()
    {
        $this->create();
        $this->bind();
    }

    /**
     * @param null $socket
     *
     * @return SocketServer
     * @throws SocketException
     */
    public function bind($socket = null)
    {
        $result = socket_bind($socket ?? $this->socket, $this->config['host'], $this->config['port']);
        if ($result == false) {
            throw new SocketException(
                SocketException::CANT_BIND_SOCKET,
                socket_strerror(socket_last_error($this->socket)));
        }
        echo "Socket has been bind \n";

        return $this;
    }

    /**
     * @param null $socket
     *
     * @return void
     * @throws SocketException
     */
    public function listen($socket = null)
    {
        if( socket_listen($socket ?? $this->socket, 5) === false) {
            throw new SocketException(
                SocketException::CANT_LISTEN,
                socket_strerror(socket_last_error( $this->socket ) ) );
        }

        $this->listenLoop = true;
        $this->beforeServerLoop();
        $this->serverLoop();

        socket_close( $this->socket );
    }

    /**
     * @param $handler
     */
    public function setConnectionHandler( $handler ) {
        $this->connectionHandler = $handler;
    }

    /**
     *
     */
    protected function beforeServerLoop() {
        printf( "Listening on %s:%d...\n", $this->config['host'], $this->config['port'] );
    }

    /**
     *
     */
    protected function serverLoop() {
        while( $this->listenLoop ) {
            if( ( $client = @socket_accept( $this->socket ) ) === false ) {
                throw new SocketException(
                    SocketException::CANT_ACCEPT,
                    socket_strerror(socket_last_error( $this->socket ) ) );
                continue;
            }
            $socketClient = new SocketClient( $client );

            if( is_array( $this->connectionHandler ) ) {
                $object = $this->connectionHandler[0];
                $method = $this->connectionHandler[1];
                $object->$method( $socketClient );
            }
            else {
                $function = $this->connectionHandler;
                $function( $socketClient );

            }
        }
    }
}