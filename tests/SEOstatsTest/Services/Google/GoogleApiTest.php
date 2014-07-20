<?php

namespace SEOstatsTest\Services\Google;

use SEOstats\Services\Sitrix;

class GoogleApiTest extends AbstractGoogleTestCase
{
    protected $standardVersionFile = "google-api-%s.json";
    protected $standardVersionSubFile = "google-api-%s-%s-%s.html";

    public function setUp()
    {
        parent::setUp();
        $this->reflection = array();

        $this->url = 'http://github.com';
    }

    /**
     * @dataProvider providerTestSimpleMethodeTest
     * @todo value controll
     * @group google
     * @group google-api
     */
    public function testSimpleMethodeTest($method, $version, $assertValue)
    {
        $this->mockSUT();
        $this->mockGetPage ($version);

        $result = call_user_func(get_class($this->mockedSUT) . '::' . $method, $this->url);

        $this->assertEquals($assertValue, $result);
    }


    public function providerTestSimpleMethodeTest()
    {
        $failedValue = $this->helperMakeAccessable('SEOstats\Services\Google', 'noDataDefaultValue', array());

        $version = array(
            array('2014', 7200000),
            array('failed', $failedValue)
        );

        $methods = array(
            'getSiteindexTotal',
            'getBacklinksTotal',
            'getSearchResultsTotal'
        );

        $result= array();
        foreach ( $methods as $m) {
            foreach ($version as $v) {

            }
            $result[] = array_merge(array($m), $v);
        }

        return $result;
    }
}
