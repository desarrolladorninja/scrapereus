<?php
namespace Tests\Util;

use PHPUnit\Framework\TestCase;
use Scrapereus\Scraper;

class ScraperTest extends TestCase
{
    protected $html = "<html>

<body><p>scraper</p> test</body>
</html>
";
    public function testrequest()
    {
        $scraper = new Scraper();
        $item = $scraper->request('https://desarrollador.ninja/scraper.html');
        $this->assertEquals($item, $this->html);

        $scraper = new Scraper();
        for ($i=0;$i<100; $i++) {
            $item = $scraper->request('https://desarrollador.ninja/scraper.html');
            $this->assertEquals($item, $this->html);
        }

        $config = 
            [
                'wait' => 0,
                'proxy' => [
                    '103.73.225.26:56509',
                    '142.93.185.165:80',
                    '212.95.180.50:53281',
                    '154.0.14.246:39431',
                    '189.51.98.182:56906',
                    '103.85.228.9:52487',
                    '185.37.213.76:30695',
                    'S162.144.72.176:49238',
                    '91.202.240.208:51678',
                    'S182.18.16.246:7302',
                    '188.162.235.76:51801',
                    '103.229.247.202:45281',
                    '1.20.103.253:59193',
                    '1.20.103.252:41689',
                    '1.20.103.248:57055',
                    '84.22.154.76:8080',
                    '1.20.103.111:33168',
                    '1.20.103.169:47609',
                    '109.248.62.207:32244',
                    '147.75.125.29:8181',
                    '176.192.107.26:43898',
                    '117.20.28.38:8080',
                    '190.90.140.59:55269',
                    '178.69.191.120:49295',
                    '182.52.238.111:60021',
                    '182.52.238.118:45217',
                    '182.52.238.119:48912',
                    '182.52.238.122:38175'
                ]
            ];

        $scraper = new Scraper($config);
        for ($i=0; $i<1000; $i++) {
            // echo $this->convert(memory_get_usage(true)); // 123 kbº
            // echo "\n";
            $item = $scraper->request('https://desarrollador.ninja/scraper.html?a='.$i);
            $this->assertEquals($item, $this->html);
        }
    }

    public function convert($size)
    {
        $unit=array('b','kb','mb','gb','tb','pb');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }

}