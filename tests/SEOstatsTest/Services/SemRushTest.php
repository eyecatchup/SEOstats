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

    /**
     * @group semrush
     */
    public function testGetParams()
    {
        $result = $this->SUT->getParams();

        $this->assertInternalType('array', $result);
        $this->assertEquals(2, count($result));

        foreach ($result as $paramBranch) {
            $this->assertInternalType('array', $paramBranch);
            $this->assertGreaterThanOrEqual(1, count($paramBranch));
        }
    }

    /**
     * @group semrush
     * @dataProvider providerTestSpecificalBackendUrl
     */
    public function testSpecificalBackendUrl($method, $args, $jsonData, $assertResult, $apiOrBackend = false)
    {
        if (!$assertResult) {
            $this->setExpectedException('\SEOstats\Common\SEOstatsException');
        }

        $this->mockSUT($method);

        $this->mockGetPage(function ($url) use ($jsonData, $apiOrBackend) {

            if ($apiOrBackend == 'api' && !preg_match('#.api.#', $url)) {
                return "false";
            } elseif ($apiOrBackend == 'backend' && !preg_match('#.backend.#', $url)) {
                return "false";
            }

            return $jsonData;
        });

        $result = call_user_func_array(array($this->mockedSUT, $method), $args);

        if ($assertResult) {
            $this->assertEquals($assertResult, $result);
        }
    }

    /**
     * @group semrush
     * @dataProvider providerTestSpecificalWidgetUrl
     */
    public function testSpecificalWidgetUrl($method, $args, $jsonData, $assertResult)
    {
        if (!$assertResult) {
            $this->setExpectedException('\SEOstats\Common\SEOstatsException');
        }

        $this->mockSUT($method);
        $this->mockGetPage(function ($url) use ($jsonData) {
            return $jsonData;
        });

        $result = call_user_func_array(array($this->mockedSUT, $method), $args);

        if ($assertResult) {
            $this->assertEquals($assertResult, $result);
        }
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

    /**
     * @group semrush
     */
    public function testGetApiData()
    {
        $that = $this;

        $jsonData = '{"foo":"bar"}';
        $data = array('foo'=>'bar');

        $this->mockSUT('getApiData');
        $this->mockGetPage(function ($url) use ($that, $jsonData) {
            $that->assertEquals('github.com', $url);
            return $jsonData;
        });

        $result = $this->helperMakeAccessable($this->mockedSUT, 'getApiData', array('github.com'));

        $this->assertEquals($data, $result);
    }

    /**
     * @group semrush
     * @dataProvider providerTestGetWidgetAndBackendUrl
     */
    public function testGetWidgetAndBackendUrl($method, $args, $status)
    {
        if (!$status) {
            $this->setExpectedException('\SEOstats\Common\SEOstatsException');
        }


        $result = $this->helperMakeAccessable($this->SUT, $method, $args);

        if ($status) {
            $this->assertInternalType('string', $result);

            preg_match('#(?<url>.+)\?(?<query>.+)#', $result, $urlSplit);

            parse_str($urlSplit['query'], $query);

            $this->assertContains($args[0],$query);
            if ($method == 'getWidgetUrl') {
                $this->assertContains($args[1],$query);
            } else {
                $this->assertRegExp(sprintf('#^(https?://)?%s#',$args[1]), $urlSplit['url']);
            }
            $this->assertContains($args[2],$query);
        }
    }

    public function providerTestGetWidgetAndBackendUrl()
    {
        $result = array();
        // $url, $db, $reportType)

        $argsValid = array('github.com', 'de', 'reportType');

        $args = $argsValid;
        $result[]= array('getWidgetUrl', $args, true);
        $result[]= array('getBackendUrl', $args, true);

        $args = $argsValid;
        $args[0] = 'http://';
        $result[]= array('getWidgetUrl', $args, false);
        $result[]= array('getBackendUrl', $args, false);

        $args = $argsValid;
        $args[1] = 'WhatEverDb';
        $result[]= array('getWidgetUrl', $args, false);
        $result[]= array('getBackendUrl', $args, false);

        return $result;
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
        $args[0] = 'http://';
        $result[]= array($args, false);

        $args = $argsValid;
        $args[1] = 0;
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


    public function providerTestSpecificalWidgetUrl()
    {
        $result = array();

        // $url = false, $db = false
        $argsValid = array('github.com', 'de');
        $jsonOrganicValid = '{"organic":"foobar"}';
        $jsonCompetitorsValid = '{"organic_organic":"foobar"}';
        $jsonNoDataValid = 'false';

        $noData = $this->helperMakeAccessable('SeoStats\SeoStats','noDataDefaultValue', array());

        $args = $argsValid;
        $result[]= array('getOrganicKeywords', $args, $jsonOrganicValid, 'foobar');
        $result[]= array('getCompetitors', $args, $jsonCompetitorsValid, 'foobar');

        $args = $argsValid;
        $result[]= array('getOrganicKeywords', $args, $jsonNoDataValid, $noData);
        $result[]= array('getCompetitors', $args, $jsonNoDataValid, $noData);


        $args = $argsValid;
        $args[0] = 'http://';
        $result[]= array('getOrganicKeywords', $args, false, false);
        $result[]= array('getCompetitors', $args, false, false);

        $args = $argsValid;
        $args[1] = 'WhatEverDb';
        $result[]= array('getOrganicKeywords', $args, false, false);
        $result[]= array('getCompetitors', $args, false, false);

        return $result;
    }


    public function providerTestSpecificalBackendUrl()
    {
        $result = array();

        // $url = false, $db = false
        $argsValid = array('github.com', 'de');
        $jsonDomainRankValid = '{"rank":{"data":["foobar","bar","baz"]}}';
        $jsonDomainRankHistoryValid = '{"rank_history":"foobar"}';
        $jsonNoDataValid = 'false';

        $noData = $this->helperMakeAccessable('SeoStats\SeoStats','noDataDefaultValue', array());

        $args = $argsValid;
        $result[]= array('getDomainRank', $args, $jsonDomainRankValid, 'foobar', 'api');
        $result[]= array('getDomainRank', $args, $jsonDomainRankValid, 'foobar', 'backend');
        $result[]= array('getDomainRankHistory', $args, $jsonDomainRankHistoryValid, 'foobar', 'api');
        $result[]= array('getDomainRankHistory', $args, $jsonDomainRankHistoryValid, 'foobar', 'backend');

        $args = $argsValid;
        $result[]= array('getDomainRank', $args, $jsonNoDataValid, $noData, 'api');
        $result[]= array('getDomainRank', $args, $jsonNoDataValid, $noData, 'backend');
        $result[]= array('getDomainRankHistory', $args, $jsonNoDataValid, $noData, 'api');
        $result[]= array('getDomainRankHistory', $args, $jsonNoDataValid, $noData, 'backend');


        $args = $argsValid;
        $args[0] = 'http://';
        $result[]= array('getDomainRank', $args, false, false);
        $result[]= array('getDomainRankHistory', $args, false, false);

        $args = $argsValid;
        $args[1] = 'WhatEverDb';
        $result[]= array('getDomainRank', $args, false, false);
        $result[]= array('getDomainRankHistory', $args, false, false);

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

    protected function mockSUT($method=null, $vars=array())
    {
        switch($method) {
            default:
                $methods = array('_getPage');
                break;
        }

        $this->mockedSUT = $this->getMock(get_class($this->SUT), $methods);
        $this->mockedSUT->setUrl(array_key_exists('url',$vars) ? $vars['url'] : $this->url);
    }

    protected function mockGetApiData($arg = null)
    {
        if (is_callable($arg)) {
            $this->mockedSUT->staticExpects($this->any())
                            ->method('getApiData')
                            ->will($this->returnCallback($arg));
            return;
        }

        $this->mockedSUT->staticExpects($this->any())
                        ->method('getApiData')
                        ->will($this->returnValue($arg));
    }
}
