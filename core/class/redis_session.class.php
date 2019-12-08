<?php

use Predis\ClientInterface;

class redis_session implements SessionHandlerInterface {

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var ClientInterface
     */
    private $activeClient;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @param ClientInterface $client
     * @param string $prefix
     */
    public function __construct(ClientInterface $client, string $prefix = 'phpsessid:')
    {
        $this->client = $client;
        $this->prefix = $prefix;
    }

    /**
     * @inheritDoc
     */
    public function close()
    {
        $this->activeClient = null;

        return true;
    }

    /**
     * @inheritDoc
     */
    public function destroy($session_id)
    {
        var_dump('destroy');
        $this->activeClient->del([$this->prefix . $session_id]);

        return 1;
    }

    /**
     * @inheritDoc
     */
    public function gc($maxlifetime)
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function open($save_path, $name)
    {
        $this->activeClient = $this->client;

        return true;
    }

    /**
     * @inheritDoc
     */
    public function read($session_id)
    {
        $val = $this->activeClient->get($this->prefix . $session_id);

        return unserialize($val);
    }

    /**
     * @inheritDoc
     */
    public function write($session_id, $session_data)
    {
        var_dump($session_data); die;
        $this->activeClient->set($this->prefix . $session_id, serialize($session_data));
    }
}