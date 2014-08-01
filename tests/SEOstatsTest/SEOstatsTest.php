<?php

namespace SEOstatsTest;

use SEOstats\SEOstats;

class SEOstatsTest extends AbstractSEOstatsTestCase
{
    /**
     *
     * @var SEOstats
     */
    public $SUT;

    public function setUp()
    {
        $this->SUT = new SEOstats();
    }

    /**
     * @dataProvider providerTestServiceMethods
     */
    public function testServiceMethods($method, $assertInstance)
    {
        $object = $this->SUT->{$method}();

        $this->assertInstanceOf($assertInstance, $object);
    }

    public function providerTestServiceMethods()
    {
        return array(
            array('Alexa', 'SEOstats\Services\Alexa'),
            array('Google', 'SEOstats\Services\Google'),
            array('Mozscape', 'SEOstats\Services\Mozscape'),
            array('OpenSiteExplorer', 'SEOstats\Services\OpenSiteExplorer'),
            array('SEMRush', 'SEOstats\Services\SEMRush'),
            array('Sistrix', 'SEOstats\Services\Sistrix'),
            array('Social', 'SEOstats\Services\Social'),
        );
    }

    public function testSetAndGetUrl()
    {
        $url = 'http://github.com';

        $result = $this->SUT->getUrl($url);
        $this->assertSame($url, $result);

        $this->SUT->setUrl($url);
        $this->assertSame($url, $this->SUT->getUrl());
    }

    public function testSetUrlInvalid()
    {
        $this->setExpectedException('SEOstats\Common\SEOstatsException' ,'Invalid URL!');

        $this->SUT->setUrl("github.com");
    }

    public function testGetHost()
    {
        $host = $this->SUT->getHost('http://github.com/path/file.txt');
        $this->assertEquals('github.com', $host);
    }

    public function testGetDomain()
    {
        $domain = $this->SUT->getDomain('http://github.com/path/file.txt');
        $this->assertEquals('http://github.com', $domain);
    }

    public function testGetDOMDocument()
    {
        $html = '<html><body>test</body></html>';

        $result = $this->helperMakeAccessable($this->SUT, '_getDOMDocument', array($html));
        $this->assertInstanceOf('DOMDocument', $result);
    }

    public function testGetDOMXPath()
    {
        $doc = new \DOMDocument('<html><body>test</body></html>');

        $result = $this->helperMakeAccessable($this->SUT, '_getDOMXPath', array($doc));
        $this->assertInstanceOf('DOMXPath', $result);
    }

    /**
     *
     * @group live
     */
    public function testGetPage()
    {
        $url = 'http://github.com/test';
        $result = $this->helperMakeAccessable($this->SUT, '_getPage', array($url));

        $this->assertInternalType('string', $result);
        $this->assertEquals($url, $this->SUT->getLastLoadedUrl());
    }

    public function testSetHtml()
    {
        $html = '<html><body></body></html>';
        $this->helperMakeAccessable($this->SUT, '_lastHtml', false);

        $this->assertFalse($this->SUT->getLastLoadedHtml());

        $result = $this->helperMakeAccessable($this->SUT, '_setHtml', array($html));

        $this->assertSame($html, $this->SUT->getLastLoadedHtml());
    }

    public function testNoDataDefaultValue()
    {
        $this->markTestIncomplete();
    }
}
