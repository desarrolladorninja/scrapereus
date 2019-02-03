<?php
namespace Tests\Util;

use PHPUnit\Framework\TestCase;
use Scrapereus\Scraper;

class ScraperTest extends TestCase
{
    public function testgetProxy()
    {
        $scraper = new Scraper(['proxies' => [$proxyTest]]);
        $item = $scraper->getProxy();

        $this->assertEquals($item, $proxyTest);
    }
}