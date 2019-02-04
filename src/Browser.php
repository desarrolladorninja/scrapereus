<?php
namespace Scrapereus;
use GuzzleHttp;

class Browser
{
    public $config = [];

    public function __construct(array $config = [])
    {
        $defaults = [
            'proxy' => [
                'host' => null,
                'port' => null,
                'user' => null,
                'pass' => null
            ],
            'userAgent' => 'Mozilla/5.1 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',
        ];

        $this->config = $config + $defaults;

        $this->loadUserAgent();
    }

    public function loadUserAgent()
    {
        $file = __DIR__.'/../var/useragent.txt';
        $agentsData = file_get_contents($file);
        $agents = explode("\n", $agentsData);

        $this->config['userAgents'] = $agents;
    }

    public function getUserAgent($name)
    {
        switch ($name) {
            case 'google':
                    return 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)';
                break;

                case 'random':
                    $id = rand(0, count($this->config['userAgents'])-1);
                    return $this->config['userAgents'][$id];
                break;
            
            default:
                    return $name;
                break;
        }
    }

    public function request($url)
    {
        $clientParameters = [
            'headers' => [
                'User-Agent' => $this->getUserAgent($this->config['userAgent']),
            ],
        ];

        if (isset($this->config['proxy']['host'])) {
            $clientParameters['curl'][CURLOPT_PROXY] = $this->config['proxy']['host'];
        }

        if (isset($this->config['proxy']['port'])) {
            $clientParameters['curl'][CURLOPT_PROXYPORT] = $this->config['proxy']['port'];
        }

        if (isset($this->config['proxy']['user'])) {
            $clientParameters['curl'][CURLOPT_PROXYUSERPWD] = $this->config['proxy']['user'].':'.$this->config['proxy']['pass'];
        }

        try {
            $client = new GuzzleHttp\Client(['http_errors' => false]);
            $response = $client->request('GET', $url, $clientParameters);
            $data = $response->getBody();
        } catch (\Exception $e) {
            return false;
        }

        return (string) $data;
    }
}