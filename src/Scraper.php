<?php
namespace Scrapereus;

use Scrapereus\Browser;
use Scrapereus\Proxy;
use Scrapereus\Cache;

class Scraper
{
    protected $config = [];
    protected $browserList = [];

    public function __construct(array $config = [])
    {
        $this->configureDefaults($config);
        $this->init();
    }

    public function init()
    {
        $config['proxy'] = $this->config['proxy'];
        $this->proxy = new Proxy($config);
        $this->cache = new Cache();
    }

    /**
     * Configures the default options for a client.
     *
     * @param array $config
     */
    private function configureDefaults(array $config)
    {
        $defaults = [
            'proxy' => false,
            'userAgent' => 'random',
            'wait' => 30,
            'cache' => true,
        ];

        $this->config = $config + $defaults;
    }

    public function request($url)
    {
        if ($data = $this->getCache($url)) {
            return $data;
        }

        $key = $this->getBrowser();
        $browser = $this->browserList[$key]['browser'];

        try {
            $data = $browser->request($url);
        } catch (\Exception $e) {
            $proxy = $browser->getProxy();
            if (!$this->proxy->check($proxy)) {
                $this->browserList[$key] = null;
                unset($this->browserList[$key]);
            }

            return $this->request($url);
        }

        $this->setCache($url, $data);
        
        return $data;
    }

    public function getBrowser()
    {
        if ($browser = $this->newBrowser()) {
            $time = new \DateTime();
            $time->modify(sprintf('+%s seconds', $this->config['wait']));

            $this->browserList[] = ['time' => $time, 'browser' => $browser];
            end($this->browserList);

            return key($this->browserList);
        }

        while (!$browser && count($this->browserList) > 0) {
            foreach ($this->browserList as $key => $value) {
                $now = new \DateTime();
                if ($now > $value['time']) {
                    $time = new \DateTime();
                    $time->modify(sprintf('+%s seconds', $this->config['wait']));

                    $this->browserList[$key] = ['time' => $time, 'browser' => $value['browser']];

                    return $key;
                }
            }
            sleep(1);
        }
        
        throw new \Exception("No browser", 1);
    }

    public function newBrowser()
    {
        $config = [];
        if ($this->config['proxy']) {
            $proxy = $this->proxy->get();
            if (!$proxy) {
                return false;
            }
            $config['proxy'] = $proxy;
        }
        $config = $config + $this->config;
        $browser = new Browser($config);

        return $browser;
    }

    public function setCache($key, $data)
    {
        if (!$this->config['cache']) {
            return false;
        }

        return $this->cache->save($key, $data);
    }

    public function getCache($key)
    {
        if (!$this->config['cache']) {
            return false;
        }

        return $this->cache->get($key);
    }
}