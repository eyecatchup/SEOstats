<?php

namespace SEOstatsTest\Services;

use SEOstats\Services\Alexa;

class AlexaTest extends AbstractServiceTestCase
{
    protected $standardVersionFile = "alexa-siteinfo-%s.html";
    protected $standardVersionSubFile = "alexa-siteinfo-%s-%s-%s.html";

    public function setUp()
    {
        parent::setup();

        $this->reflection = array();

        $this->url = 'http://github.com';
        $this->SUT = new \SEOstats\Services\Alexa();
        $this->SUT->setUrl($this->url);
    }

    /**
     *
     * @dataProvider providerTestSiteinfoMethodWithDiffrentVersion
     * @group alexa
     */
    public function testSiteinfoMethodWithDiffrentVersion ($method, $version, $type)
    {
        $this->mockAlexa($method);
        $this->mockGetAlexaPage ($version);
        $SUT = $this->mockedSUT;
        $result = call_user_func(get_class($SUT) . '::' . $method, $this->url);

        $noDataDefault = $this->helperMakeAccessable($SUT, 'noDataDefaultValue', array());

        // for the case that this version can be invalid
        if ($type || $noDataDefault != $result) {
            if (is_array($type)) {
                foreach ($type as $arrayKey=>$arrayValueType) {
                    $this->assertArrayHasKey($arrayKey, $result);
                    $this->assertInternalType($arrayValueType, $result[$arrayKey]);
                }
            } elseif (is_string($type)) {
                $this->assertInternalType($type, $result);
            }
            $this->assertNotEquals($noDataDefault, $result);
        }
        elseif (null === $type) {
            $this->assertEquals($noDataDefault, $result);
        } else {
            $this->markTestSkipped(sprintf('methode %s returns an invalid result check source data version', $method));
        }
    }

    /**
     *
     * @dataProvider providerTestGetTrafficGraph
     * @group alexa
     */
    public function testGetTrafficGraph($url, $paramsArray, $assertResult)
    {
        if ($assertResult instanceof \Exception) {
            $this->setExpectedException(get_class($assertResult), $assertResult->getMessage());
        }
        $result = call_user_func_array(array($this->SUT, 'getTrafficGraph'), $paramsArray);

        if (! $assertResult instanceof \Exception) {
            $this->assertInternalType('string', $result);
            $this->assertEquals($assertResult, $result);
        }
    }

    /**
     * @group alexa
     */
    public function testGetXPath()
    {
        $urlList = array(
            $this->url,
            'http://www.google.de'
        );

        $reflectionMethod = $this->helperMakeAccessable($this->SUT,'_getXPath');
        $reflectionProperty = $this->helperMakeAccessable($this->SUT,'_lastLoadedUrl');

        $result1 = $result2 = null;

        $SUT = $this->SUT;

        foreach ($urlList as $url) {
            // first call
            $result1 = $reflectionMethod->invoke($this->SUT, $url);
            $this->assertInternalType('object', $result1);
            $this->assertInstanceOf('DOMXPath', $result1);
            $this->assertNotSame($result1, $result2);


            $reflectionProperty->setValue($this->SUT, $url);


            // secound call
            $result2 = $reflectionMethod->invoke($this->SUT, $url);
            $this->assertInternalType('object', $result2);
            $this->assertInstanceOf('DOMXPath', $result2);
            $this->assertSame($result1, $result2);
        }
    }

    /**
     * @group alexa
     */
    public function testGetAlexaPage()
    {
        $this->mockAlexa('_getAlexaPage');
        $this->mockGetPage();
        $reflectionMethod = $this->helperMakeAccessable($this->mockedSUT,'_getAlexaPage');

        $result = $reflectionMethod->invoke($this->mockedSUT, $this->url);
        $this->assertInternalType('string', $result);

        $this->assertRegExp('#<body id="siteInfoPage"#', $result);
    }

    /**
     * @dataProvider providerTestRetInt
     * @group alexa
     */
    public function testRetInt($string, $assert)
    {
        $reflectionMethod = $this->helperMakeAccessable($this->SUT,'retInt');

        $result = $reflectionMethod->invoke($this->SUT, $string);
        $this->assertInternalType('integer', $result);
        $this->assertSame($assert, $result);
    }

    public function providerTestRetInt()
    {
        return array(
            array('1234',1234),
            array('12,34',1234),
            array('  1234 ',1234),
            array(' 1,2,3,4 ',1234),
            array('',0),
            array(' , , , , ',0),
            array('      ',0),
            array(',,,,,',0),
        );
    }

    public function providerTestGetTrafficGraph()
    {
        // $type = 1, $url = false, $w = 660, $h = 330, $period = 1, $html = true
        $result = array();
        $result[]= array(
          'http://github.com',
          array(1, false, 660, 330, 1, true),
          sprintf(
            '<img src="%s" width="%s" height="%s" alt="Alexa Statistics Graph for %s"/>',
            sprintf(\SEOstats\Config\Services::ALEXA_GRAPH_URL, 't', 660, 330, 1, 'github.com'),
            660, 330, 'github.com'
          )
        );

        $paramsArray = array();
        $paramsArray[] = array(
            'width'=>660,
            'height'=>330,
            'periode'=>1,
            'typeIndex'=>1,
            'typeChar'=>'t',
            'url'=>'http://github.com',
            'domain'=>'github.com',
        );

        $paramsArray[] = array_merge($paramsArray[0], array('url'=>'http://github.com','domain'=>'github.com'));
        $paramsArray[] = array_merge($paramsArray[0], array('width'=>880,'height'=>440));
        $paramsArray[] = array_merge($paramsArray[0], array('periode'=>2));


        $typeArray = array(0=>'',1=>'t',2=>'p',3=>'u',4=>'s',5=>'b',6=>'q',1337=>'');

        foreach ($typeArray as $typeIndex=>$typeChar) {
            $paramsArray[] = array_merge($paramsArray[0], array('typeIndex'=>$typeIndex,'typeChar'=>$typeChar));
        }

        foreach ($paramsArray as $params) {

            $assertResult = $params['typeChar'] !== ''
              ? sprintf(\SEOstats\Config\Services::ALEXA_GRAPH_URL, $params['typeChar'], $params['width'], $params['height'], $params['periode'], $params['domain'])
              : new \Exception("Undefined variable: gtype");

            $result[]= array(
              $params['url'],
              array($params['typeIndex'], $params['url'], $params['width'], $params['height'], $params['periode'], false),
              $assertResult
            );
        }

        return $result;
    }

    public function providerTestSiteinfoMethodWithDiffrentVersion()
    {
        // @TODO to get the new alexa rank daily/weekly/monthly we need a svg analyse for the site comparisons<
        $result = array();
        $methodList = array(
            'getPageLoadTime'=>'string',
            'getBacklinkCount'=>'integer',
            'getCountryRank'=>array('rank'=>'integer','country'=>'string'),
            'getGlobalRank'=>'integer',
            'getQuarterRank'=>'integer',

            'getMonthlyRank'=>array('integer', null),
            'getMonthRank'=>'integer',

            'getWeeklyRank'=>array('integer', null),
            'getWeekRank'=>'integer',

            'getDailyRank'=>array('integer', null),
        );

        $versionList = array(
            array('2013',true),
            array('2014',false) # new version currently not supported
        );

        foreach ($versionList as $version) {
            foreach ($methodList as $methodName=>$methodeAssertResultType) {

                $versionArray = $this->getStandardVersions($version[0], $methodName);
                $iVersion = 0;
                foreach($versionArray as $versionSub) {
                    $assertResult = $methodeAssertResultType;

                    if (is_array($methodeAssertResultType) && array_key_exists(0, $methodeAssertResultType)) {
                        if ($version[0] == $versionSub) {
                            $assertResult = $methodeAssertResultType[0];
                        } else {
                            $versionIndex = explode('-',$versionSub);
                            $assertResult = $methodeAssertResultType[ $versionIndex[2] - 1];
                        }
                    }

                    $result[]= array(
                        $methodName,
                        $versionSub,
                        $version[1] ? $assertResult : $version[1]
                    );
                }
            }
        }

        return $result;
    }

    protected function mockAlexa($method, $vars=array())
    {

        $methods = array();
        switch ($method) {
            case '_getXPath':
                $methods = array('_getAlexaPage','_getPage');
                break;
            case '_getAlexaPage':
                $methods = array('_getPage');
                break;
            default:
                $methods = array('_getAlexaPage','_getPage');
                break;
        }

        $this->mockedSUT = $this->getMock('\SEOstats\Services\Alexa', $methods);
        $this->mockedSUT->setUrl(array_key_exists('url',$vars) ? $vars['url'] : $this->url);
    }

    protected function mockGetAlexaPage ($version, $calledTest = null)
    {
        $standardFile = sprintf($this->getAssertDirectory() . $this->standardVersionFile, $version);
        $this->mockedSUT->staticExpects($this->any())
                        ->method('_getAlexaPage')
                        ->will($this->returnValue(file_get_contents($standardFile)));
    }
}
