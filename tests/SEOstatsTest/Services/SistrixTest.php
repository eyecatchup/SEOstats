<?php

namespace SEOstatsTest\Services;

class SistrixTest extends AbstractServiceTestCase
{
    protected $standardVersionFile = "sistrix-%s.html";
    protected $standardVersionSubFile = "sistrix-%s-%s-%s.html";
    protected $standardVersionSubFileJson = "sistrix-%s-%s-%s.json";

    public function setUp()
    {
        $this->reflection = array();
        $this->url = 'http://github.com';
    }

    /**
     * @dataProvider providerTestGetVisibilityIndex
     * @todo value control
     * @group sistrix
     */
    public function testGetVisibilityIndex($version, $status)
    {
        $this->mockSUT();
        $this->mockGetPage($version);

        $result = call_user_func(get_class($this->mockedSUT) . '::getVisibilityIndex', $this->url);

        if ($status) {
            $assertValue = 'h3 foo1';

        } else {
            $assertValue = $this->helperMakeAccessable($this->mockedSUT, 'noDataDefaultValue', array());
        }

        $this->assertEquals($assertValue, $result);
    }

    /**
     * @dataProvider providerTestGetVisibilityIndexByApi
     * @group sistrix
     */
    public function testGetVisibilityIndexByApi($version, $result, $status)
    {
        $this->mockSUT('api', array('url' => 'http://www.spiegel.de'));
        $this->mockGetApi($version, 'viOverApi', $result);
        $this->mockHasApiKey(true);
        $this->mockCheckApiCredits(true);

        $resp = call_user_func(get_class($this->mockedSUT) . '::getVisibilityIndexByApi', $this->url);

        if ($status) {
            $assertValue = '355.9236';
        } else {
            $assertValue = $this->helperMakeAccessable($this->mockedSUT, 'noDataDefaultValue', array());
        }

        $this->assertEquals($assertValue, $resp);
    }

    /**
     * @dataProvider providerTestGetApiCredits
     * @group sistrix
     */
    public function testGetApiCredits($version, $result, $status)
    {
        $this->mockSUT('apicredits');
        $this->mockGetApi($version, 'apiCredits', $result);
        $this->mockHasApiKey(true);

        $resp = call_user_func(get_class($this->mockedSUT) . '::getApiCredits');

        if ($status) {
            $assertValue = '9998';
        } else {
            $assertValue = $this->helperMakeAccessable($this->mockedSUT, 'noDataDefaultValue', array());
        }

        $this->assertEquals($assertValue, $resp);
    }

    /**
     * @dataProvider providerTestCheckApiCredits
     * @group sistrix
     */
    public function testCheckApiCredits($version, $result, $status)
    {
        $this->mockSUT('apicredits');
        $this->mockGetApi($version, 'apiCredits', $result);
        $this->mockHasApiKey(true);

        $resp = call_user_func(get_class($this->mockedSUT) . '::checkApiCredits');

        if ($status) {
            $assertValue = true;
        } else {
            $assertValue = false;
        }

        $this->assertEquals($assertValue, $resp);
    }

    /**
     * @group sistrix
     */
    public function testHasApiKey()
    {
        $this->markTestIncomplete();
    }

    /**
     * @group sistrix
     */
    public function testGuardApiCredits()
    {
        $this->markTestIncomplete();
    }

    /**
     * @group sistrix
     */
    public function testCheckDatabase()
    {
        $this->markTestIncomplete();
    }

    /**
     * @group sistrix
     */
    public function testGetDomainFromUrl()
    {
        $this->markTestIncomplete();
    }

    /**
     * @group sistrix
     */
    public function testGetValidDatabase()
    {
        $this->markTestIncomplete();
    }

    /**
     * @group sistrix
     */
    public function testGuardDatabaseIsValid()
    {
        $this->markTestIncomplete();
    }

    /**
     * @group sistrix
     */
    public function testGuardDomainIsValid()
    {
        $this->markTestIncomplete();
    }

    public function providerTestGetPageMetrics()
    {
        return array(
            array('2014', true),
            array('failed', false)
        );
    }

    public function providerTestGetVisibilityIndex()
    {
        return array(
            array('2013', true),
            array('failed', false)
        );
    }

    public function providerTestGetVisibilityIndexByApi()
    {
        return array(
            array('2014', 'valid', true),
            array('2014', 'failed', false),
            array('2014', 'empty', false)
        );
    }

    public function providerTestGetApiCredits()
    {
        return array(
            array('2014', 'valid', true),
            array('2014', 'invalid', false),
            array('2014', 'empty-response', false),
            array('2014', 'failed', false),
            array('2014', 'empty', false)
        );
    }

    public function providerTestCheckApiCredits()
    {
        return array(
            array('2014', 'valid', true),
            array('2014', 'invalid', false),
            array('2014', 'empty-response', false),
            array('2014', 'failed', false),
            array('2014', 'empty', false)
        );
    }

    protected function mockGetApi($version, $method, $result)
    {
        $standardFile = sprintf($this->getAssertDirectory() . $this->standardVersionSubFileJson,
                                $version,
                                $method,
                                $result
                                );
        $this->mockedSUT->staticExpects($this->any())
             ->method('_getPage')
             ->will($this->returnValue(file_get_contents($standardFile)));
    }

    protected function mockHasApiKey($result)
    {
        $this->mockedSUT->staticExpects($this->any())
             ->method('hasApiKey')
             ->will($this->returnValue($result));
    }

    protected function mockCheckApiCredits($result)
    {
        $this->mockedSUT->staticExpects($this->any())
             ->method('checkApiCredits')
             ->will($this->returnValue($result));
    }

    protected function hasApiKey()
    {
        return true;
    }

    protected function mockSUT($method = null, $vars = array())
    {
        switch ($method) {
            case 'api':
                $methods = array('hasApiKey', '_getPage', 'checkApiCredits');
                break;
            case 'apicredits':
                $methods = array('hasApiKey', '_getPage');
                break;
            default:
                $methods = array('_getPage');
                break;
        }

        $this->mockedSUT = $this->getMock('\SEOstats\Services\Sistrix', $methods);
        $this->mockedSUT->setUrl(array_key_exists('url', $vars) ? $vars['url'] : $this->url);
    }
}
