<?php namespace src;

use src\Exceptions\SocketException;

/**
 * @property resource spawn
 */
abstract class Connection
{
    /** @var
     * config @abstract Array - an array of configuration information used by the server.
     */
    protected $config;
    /**
     * @var resource
     */
    protected $connection;
    /**
     * @var resource
     */
    protected $socket;

    /**
     * @var bool
     */

    protected $listenLoop;

    /**
     * Every Class that inherit from connection must have init method
     */
    public abstract function init();

    /**
     * Connection constructor.
     *
     * @param string $host
     * @param int    $port
     * @param int    $timout
     */
    public function __construct(string $host = '127.0.0.1', int $port = 1111, $timout = 0)
    {
        $this->config         = [];
        $this->config['host'] = $host;
        $this->config['port'] = $port;
        $this->listenLoop     = false;
        set_time_limit($timout);
    }

    /**
     */
    public function create()
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($this->socket == false) {
            throw  new SocketException(
                SocketException::CANT_CREATE_SOCKET,
                socket_strerror(socket_last_error()));
        }
        socket_set_option($this->socket, SOL_SOCKET, SO_REUSEADDR, 1);
    }

    /**
     * @param mixed $connection
     *
     * @return Connection
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->config['host'];
    }

    /**
     * @param $host
     */
    public function setHost($host)
    {
        $this->config['host'] = $host;
    }

    /**
     * @param $port
     */
    public function setPort($port)
    {
        $this->config['port'] = $port;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->config['port'];
    }
}