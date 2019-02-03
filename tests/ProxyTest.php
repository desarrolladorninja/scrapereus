<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Scrapereus\Proxy;

class ProxyTest extends TestCase
{
    public function testparse()
    {
        $proxy = new Proxy();
        $item = $proxy->parse('a:b:c:d');
        $this->assertEquals($item, ['host' => 'a', 'port' => 0, 'user' => 'c', 'pass' => 'd']);

        $item = $proxy->parse('a:b');
        $this->assertEquals($item, ['host' => 'a', 'port' => 0, 'user' => null, 'pass' => null]);
    }

    public function testcheck()
    {
        $proxy = new Proxy();

        $item = $proxy->check('a:b:c:d');
        $this->assertFalse($item);
    }

    public function testget()
    {
        $proxy = new Proxy(['proxies' => [
            'a:b:c:d',
            'a:b:c:d',
        ]]);
        $item = $proxy->get();
        $this->assertFalse($item);
        $item = $proxy->get();
        $this->assertFalse($item);
        $item = $proxy->get();
        $this->assertFalse($item);
        $item = $proxy->get();
        $this->assertFalse($item);
    }
}