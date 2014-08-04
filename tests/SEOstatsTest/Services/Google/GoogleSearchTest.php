<?php

namespace SEOstatsTest\Services\Google;

use SEOstats\Services\Google;
use SEOstats\Services\Google\Search as GoogleSearch;

class GoogleSearchTest extends AbstractGoogleTestCase
{
    protected $standardVersionFile = "google-search-%s.html";
    protected $standardVersionSubFile = "google-search-%s-%s-%s.html";
    public $called = 0;

    public function setup()
    {
        parent::setup();
        $this->reflection = array();

        $this->url = 'http://github.com';
        $this->SUT = new GoogleSearch();
        $this->SUT->setUrl($this->url);

        $this->called = 1;
    }

    /**
     * @dataProvider providerTestGoogleCurl
     * @group google
     * @group google-search
     * @group live
     */
    public function testGoogleCurl($args, $status)
    {
        $result = $this->helperMakeAccessable($this->SUT, 'gCurl', $args);

        if ($status) {
            $this->assertInternalType('string', $result);
            $this->assertTrue(strlen($result) >= 1);

        } else {
            $this->assertFalse($result);
        }
    }

    /**
     * @dataProvider providerTestGetSerps
     * @todo value controll
     * @group google
     * @group google-search
     */
    public function testGetSerps($args, $version, $assertResultCount)
    {
        $this->mockSUT('getSerps');
        $this->mockGCurl ($version);

        $result = $this->helperMakeAccessable($this->mockedSUT, 'getSerps', $args);

        $this->assertEquals($assertResultCount, count($result));
    }


    public function providerTestGoogleCurl()
    {
        $query = rawurlencode('github.com');

        $result   = array();

        // @todo implement cookie support in tests
        $result[] = array(array(# $path, $ref, $useCookie
                                sprintf('search?q=%s&filter=0', $query),
                                'ncr',
                                false
                          ),
                          true);
        $result[] = array(array(# $path, $ref, $useCookie
                                sprintf('search?q=%s&filter=0', $query),
                                '',
                                false
                          ),
                          true);

        return $result;
    }


    public function providerTestGetSerps()
    {
        // query, $maxResults=100, $domain=false
        $query = 'github.com';

        $args = array( $query, 10, false );
        $result[] = array($args, '2014', 15); // github.com result gives more than 10 results on first page
        $result[] = array($args, '2014-with-one-page', 13);
        $result[] = array($args, 'failed', 0);

        $args = array( $query, 10, 'github.com' );
        $result[] = array($args, '2014', 11);
        $result[] = array($args, 'failed', 0);

        $args = array( $query, 10, 'https://github.com' );
        $result[] = array($args, '2014', 4);
        $result[] = array($args, 'failed', 0);

        $args = array( 'some_query_that_dont_give_a_result', 20, false );
        $result[] = array($args, 'failed', 0);



        // @TODO add support for 4, 15 , 25 maxResult to
        // $args = array( $query, 15, false );
        // $result[] = array($args, '2014', 15);
        // $result[] = array($args, 'failed', 0);

        // @TODO fix domain filter regexp
        // $args = array( $query, 15, 'github.com' );
        // $result[] = array($args, '2014', 15);
        // $result[] = array($args, 'failed', 0);



        $args = array( $query, 20, false );
        $result[] = array($args, '2014', 15);
        $result[] = array($args, 'failed', 0);

        $args = array( $query, 20, 'github.com' );
        $result[] = array($args, '2014', 11);
        $result[] = array($args, 'failed', 0);


        return $result;
    }

    protected function mockGCurl ($version)
    {
        $standardFile = $this->getAssertDirectory() . $this->standardVersionFile;
        $that = $this;

        $this->mockedSUT->staticExpects($this->any())
                        ->method('gCurl')
                        ->will($this->returnCallback(function() use ($standardFile, $version, $that) {
                            $file = sprintf($standardFile, $version . '-page-' . $that->called);

                            if (!file_exists($file)) {
                                $file = sprintf($standardFile, $version);
                            }
                            $that->called++;

                            return file_get_contents($file);
                        }));
    }
}
