<?php

namespace SEOstatsTest\Services;

use SEOstats\Services\SemRush;

class SemRushTest extends AbstractServiceTestCase
{
    protected $standardVersionFile = "semrush-%s.html";
    protected $standardVersionSubFile = "semrush-%s-%s-%s.html";

    public function setUp()
    {
        parent::setup();

        $this->reflection = array();

        $this->url = 'http://github.com';
        $this->SUT = new SemRush();
        $this->SUT->setUrl($this->url);
    }

    /**
     * @group semrush
     */
    public function testGetDBs()
    {
        $result = $this->helperMakeAccessable($this->SUT, 'getDBs', array());

        $this->assertInternalType('array', $result);
        $this->assertGreaterThanOrEqual(1, count($result));
    }

    public function testGetParams()
    {
        $this->markTestIncomplete();
    }

    public function testGetDomainRank()
    {
        $this->markTestIncomplete();
    }

    public function testGetDomainRankHistory()
    {
        $this->markTestIncomplete();
    }

    public function testGetOrganicKeywords()
    {
        $this->markTestIncomplete();
    }

    public function testGetCompetitors()
    {
        $this->markTestIncomplete();
    }

    /**
     * @group semrush
     * @dataProvider providerTestGetDomainGraph
     */
    public function testGetDomainGraph($args, $assertCallback)
    {
        if ($assertCallback) {
            $callback = array($this, $assertCallback);
        } else {
            $this->setExpectedException('\SEOstats\Common\SEOstatsException');
        }

        $result = call_user_func_array(array($this->SUT, 'getDomainGraph'), $args);

        if ($callback) {
            call_user_func($callback, $result, $args);
        }
    }

    public function testGetApiData()
    {
        $this->markTestIncomplete();
    }

    public function testGetBackendUrl()
    {
        $this->markTestIncomplete();
    }

    public function testGetWidgetUrl()
    {
        $this->markTestIncomplete();
    }

    public function testExc()
    {
        $this->markTestIncomplete();
    }


    public function providerTestGetDomainGraph()
    {
        $result = array();

        #$reportType = 1, $url = false, $db = false, $w = 400, $h = 300, $lc = 'e43011', $dc = 'e43011', $lang = 'en', $html = true

        $argsValid = array( 1, 'github.com', 'de', 400, 300, 'e43011', 'e43011', 'de', true );

        $args = $argsValid;
        $result[]= array($args, 'assertValidImageHtmlResult');

        $args[8] = false;
        $result[]= array($args, 'assertValidImageUrlResult');

        $args = $argsValid;
        $args[0] = 0;
        $result[]= array($args, false);

        $args = $argsValid;
        $args[2] = 'whatEver';
        $result[]= array($args, false);

        $imageSizeList = array(
            array(50,300),
            array(800,300),
            array(300,50),
            array(300,800),
        );

        foreach ($imageSizeList as $size) {
            $args = $argsValid;
            $args[3] = $size[0];
            $args[4] = $size[1];
            $result[]= array($args, false);
        }

        $args = $argsValid;
        $args[7] = 'whatEverLang';
        $result[]= array($args, false);


        return $result;
    }

    public function assertValidImageUrlResult($result, $args)
    {
        preg_match('#(?<url>.+)\?(?<query>.+)#', $result, $urlSplit);

        parse_str($urlSplit['query'], $query);

        // count without html
        $this->assertEquals(count($args) - 1, count($query));
    }


    public function assertValidImageHtmlResult($result, $args)
    {
        $regexp = '#<img\s+src="([^"]+)"[^>]+/?>#';
        $matchResult = preg_match ($regexp, $result, $matches);

        $this->assertSame(1, $matchResult);
        $this->assertValidImageUrlResult($matches[1], $args);
    }
}
