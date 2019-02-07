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
            'time' => 30
        ];

        $this->config = $config + $defaults;
    }

    public function request($url)
    {
        $key = $this->getBrowser();
        $browser = $this->browserList[$key]['browser'];

        try {
            $data = $browser->request($url);
        } catch (\Exception $e) {
            $proxy = $browser->getProxy();
            if (!$this->proxy->check($proxy)) {
                unset($this->browserList[$key]);
            }

            return $this->request($url);
        }
        
        return $data;
    }

    public function getBrowser()
    {
        if ($browser = $this->newBrowser()) {
            $time = new \DateTime();
            $time->modify(sprintf('+%s seconds', $this->config['time']));

            $this->browserList[] = ['time' => $time, 'browser' => $browser];
            end($this->browserList);

            return key($this->browserList);
        }

        while (!$browser && count($this->browserList) > 0) {
            foreach ($this->browserList as $key => $value) {
                $now = new \DateTime();
                if ($now > $value['time']) {
                    $time = new \DateTime();
                    $time->modify(sprintf('+%s seconds', $this->config['time']));

                    $this->browserList[$key] = ['time' => $time, 'browser' => $value['browser']];

                    var_dump($value['browser']->getUserAgent(), $value['browser']->getProxy());

                    return $key;
                }
            }
            sleep($this->config['time']);
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

    public function getCache()
    {

    }
}