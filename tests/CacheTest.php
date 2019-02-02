<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Scrapereus\Cache;

class CacheTest extends TestCase
{
    public function testconstruct()
    {
        $cache = new Cache();

        $this->assertTrue(true);
    }

    public function testget()
    {
        $cache = new Cache();
        $item = $cache->save('test', 'testvalue');
        $this->assertEquals($item, 'testvalue');

        $item = $cache->get('test');
        $this->assertEquals($item, 'testvalue');

        $item = $cache->isMiss('test');
        $this->assertFalse($item);

        $item = $cache->isMiss('xxxx');
        $this->assertTrue($item);
    }
}