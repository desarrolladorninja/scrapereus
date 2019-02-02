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
}