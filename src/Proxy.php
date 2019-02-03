<?php
namespace Scrapereus;

use Scrapereus\ProxyChecker;

class Proxy
{
    public $config = [];

    public function __construct(array $config = [])
    {
        $defaults = [
            'proxies' => [],
        ];

        $this->config = $config + $defaults;
    }

    public function get()
    {
        if (count($this->config['proxies']) <= 0) {
            return false;
        }

        do {
            $proxy = array_shift($this->config['proxies']);
            if ($ok = $this->check($proxy)) {
                return $proxy;
            }
        } while (!$ok && count($this->config['proxies']) > 0);

        return false;
    }

    public function check($proxy)
    {
        $proxy = $this->parse($proxy);
        $proxyStr = $proxy['host'].':'.$proxy['port'];
        if ($proxy['user'] && $proxy['pass']) {
            $proxyStr .= ','.$proxy['user'].':'.$proxy['pass'];
        }
        $url = 'https://desarrollador.ninja/ping.php';

        try {
            $proxyChecker = new ProxyChecker($url);
            $results = $proxyChecker->checkProxy($proxyStr);
        } catch(\Exception $err) {
            return false;
        }

        if (in_array('get', $results['allowed'])) {
            return true;
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