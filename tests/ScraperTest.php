<?php
namespace Tests\Util;

use PHPUnit\Framework\TestCase;
use Scrapereus\Scraper;

class ScraperTest extends TestCase
{
    protected function setUp()
    {
        $this->su = new Scraper();
    }

    public function testGetUseragentRandom()
    {
        $items = $this->su->getUseragentRandom();
        $this->assertEquals(1, count($items));
    }
}