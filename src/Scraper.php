<?php
namespace Scrapereus;

use Scrapereus\Browser;
use Scrapereus\Proxy;
use Scrapereus\Cache;

class Scraper
{
    protected $config = [];

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
            ''
        ];

        $this->config = $config + $defaults;
    }

    public function request($url)
    {
        $browser = new Browser();
        return ;
    }

    public function getBrowser()
    {

    }

    public function getProxy()
    {
        $proxy = $this->proxy->get();

        return $proxy;
    }

    public function getCache()
    {

    }
}