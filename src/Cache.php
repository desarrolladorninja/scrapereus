<?php
namespace Scrapereus;
use Stash;

class Cache
{
    public $config = [];
    public $stash;

    public function __construct(array $config = [])
    {
        $defaults = [
            'path' => __DIR__.'/../var/cache'
        ];

        $this->config = $config + $defaults;

        $driver = new Stash\Driver\FileSystem(['path' => $this->config['path']]);
        $this->stash = new Stash\Pool($driver);
        $this->stash->purge();
    }

    public function get($key)
    {
        $key = urlencode($key);
        $item = $this->stash->getItem($key);
        $data = $item->get();

        if(!$item->isMiss()) {
            return (string) $data;
        }

        return false;
    }

    public function save($key, $value)
    {
        $key = urlencode($key);
        $item = $this->stash->getItem($key);
        $item->lock();

        $status = $this->stash->save($item->set($value));

        return $value;
    }

    public function isMiss($key)
    {
        $item = $this->stash->getItem($key);

        return (bool) $item->isMiss();
    }
}