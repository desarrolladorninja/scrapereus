<?php
namespace Scrapereus;

use Scrapereus\Browser;
use Scrapereus\Proxy;
use Scrapereus\Cache;

class Scraper
{
    protected $config = [];
    protected $browser;

    public function __construct(array $config = [])
    {
        $this->configureDefaults($config);
        $this->init();
    }

    public function init()
    {
        $config['proxies'] = $this->config['proxies'];
        $this->proxy = new Proxy($config);
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
            'proxies' => [],
            'userAgent' => 'random',
        ];

        $this->config = $config + $defaults;
    }

    public function request($url)
    {
        $browser = $this->getBrowser();
        $data = $browser->request($url);
        
        return $data;
    }

    public function getBrowser()
    {
        if ($browser = $this->newBrowser()) {
            return $browser;
        }

        return false;
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

    public function getCache()
    {

    }
}