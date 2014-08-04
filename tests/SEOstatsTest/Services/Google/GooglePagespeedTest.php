<?php

namespace SEOstatsTest\Services\Google;

use SEOstats\Services\Sitrix;

class GooglePagespeedTest extends AbstractGoogleTestCase
{
    protected $standardVersionFile = "google-pagespeed-%s.json";
    protected $standardVersionSubFile = "google-pagespeed-%s-%s-%s.html";

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
     * @group google-pagespeed
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
        $pagespeedValue = json_decode(file_get_contents($this->getAssertDirectory('google-pagespeed-2014.json')));

        $result[] = array('getPagespeedAnalysis', '2014', $pagespeedValue);
        $result[] = array('getPagespeedAnalysis', 'failed', (object) array());

        $result[] = array('getPagespeedScore', '2014', 90);
        $result[] = array('getPagespeedScore', 'failed', $failedValue);


        return $result;
    }
}
