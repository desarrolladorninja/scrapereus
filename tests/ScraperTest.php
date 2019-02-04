<?php
namespace Tests\Util;

use PHPUnit\Framework\TestCase;
use Scrapereus\Scraper;

class ScraperTest extends TestCase
{
    public function testrequest()
    {
        $scraper = new Scraper();
        $item = $scraper->request('https://desarrollador.ninja/scraper.html');
        $this->assertEquals($item, "<html>

<body><p>scraper</p> test</body>
</html>
");
    }
}