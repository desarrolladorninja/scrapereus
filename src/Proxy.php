<?php
namespace Scrapereus;
use Scrapereus\ProxyChecker;

class Proxy
{
    public $config = [];

    public function __construct(array $config = [])
    {
        $defaults = [
            'proxy' => [],
        ];

        $this->config = $config + $defaults;
    }

    public function get()
    {
        if (!is_array($this->config['proxy']) || count($this->config['proxy']) <= 0) {
            return false;
        }

        do {
            $proxy = array_shift($this->config['proxy']);
            if ($ok = $this->check($this->parse($proxy))) {

                return $this->parse($proxy);
            }
        } while (!$ok && count($this->config['proxy']) > 0);

        return false;
    }

    public function check($proxy)
    {
        $proxyStr = $proxy['host'].':'.$proxy['port'];
        if (isset($proxy['user']) && isset($proxy['pass'])) {
            $proxyStr .= ','.$proxy['user'].':'.$proxy['pass'];
        }
        $url = 'https://desarrollador.ninja/ping.php';

        try {
            $proxyChecker = new ProxyChecker($url, ['timeout' => 3]);
            $results = $proxyChecker->checkProxy($proxyStr);
        } catch(\Exception $err) {
            return false;
        }

        if (in_array('get', $results['allowed'])) {
            return $results['allowed'];
        } else {
            return false;
        }
    }

    public function parse($proxy)
    {
        list($host, $port, $user, $pass) = explode(':', $proxy);

        return [
            'host' => $host,
            'port' => (int) $port,
            'user' => $user,
            'pass' => $pass
        ];
    }
}