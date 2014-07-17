<?php

namespace SEOstatsTest\Helper;

use SEOstats\Helper\Url;
use SEOstatsTest\AbstractSEOstatsTestCase;

class UrlTest extends AbstractSEOstatsTestCase
{
    /**
     *
     * @dataProvider providerTestIsRfc
     */
    public function testIsRfc($url, $assertResult)
    {
        $result = Url::isRfc($url);

        $this->assertSame($assertResult, $result);
    }

    /**
     *
     * @dataProvider providerTestParseHost
     */
    public function testParseHost($url, $assertHost)
    {
        $host = Url::parseHost($url);

        $this->assertEquals($assertHost, $host);
    }

    public function providerTestParseHost()
    {
        return array(
            array('github.com','github.com'),
            array('http://github.com','github.com'),
            array('https://github.com','github.com'),

            array('www.github.com','www.github.com'),
            array('http://www.github.com','www.github.com'),
            array('https://www.github.com','www.github.com'),

            array('', false),
            array('/index.php?foo=bar', false),
        );
    }

    public function providerTestIsRfc()
    {
        return array(
            array('http://github.com',true),
            array('https://github.com',true),
            array('https://github.com/file',true),
            array('https://github.com/file#anchor',true),
            array('https://github.com/#anchor',true),
            array('https://github.com/file?query=value',true),
            array('https://github.com/?query=value',true),

            array('http://www.github.com',true),
            array('https://www.github.com',true),
            array('https://www.github.com',true),
            array('https://www.github.com/file',true),
            array('https://www.github.com/file#anchor',true),
            array('https://www.github.com/#anchor',true),
            array('https://www.github.com/file?query=value',true),
            array('https://www.github.com/?query=value',true),

            array('github.com',false),
            array('www.github.com',false),
            array('', false),
            array('.', false),
            array('/index.php?foo=bar', false),
        );
    }
}
