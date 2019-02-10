<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Scrapereus\Browser;

class BrowserTest extends TestCase
{
    public function testconstruct()
    {
        $browser = new Browser();

        $this->assertTrue(true);
    }

    public function testrequest()
    {
        $browser = new Browser();
        $html = $browser->request('http://example.com');

        $this->assertTrue(is_string($html));
    }

    public function testloadUserAgent()
    {
        $browser = new Browser();
        $this->assertEquals(512, count($browser->config['userAgents']));
    }

    public function testgetUserAgent()
    {
        $browser = new Browser();

        $item = $browser->getUserAgent('random');
        $this->assertTrue(!empty($item));

        $item = $browser->getUserAgent('google');
        $this->assertEquals($item, 'Scrapereus');
    }
}