<?php

namespace SEOstatsTest\Services;

use SEOstats\Services\Mozscape;

class MozscapeTest extends AbstractServiceTestCase
{

    public function setUp()
    {
        $this->reflection = array();

        $this->url = 'http://github.com';
        $this->SUT = new \SEOstats\Services\Mozscape();
        $this->SUT->setUrl($this->url);
    }

    public function providerTestGetMethodeMethods()
    {
        $SUT = '\SEOstats\Services\Mozscape';

        $validValue = array('foo'=>'bar');
        $inValidValue = $this->helperMakeAccessable($SUT, 'noDataDefaultValue', array());

        $result = array();
        $float = array(
            100.12345678901234567890,
            100.1234567890123456
        );

        $result[]= array('getMozRankRaw', '16384', array('umrr'=>$float[0]), $float[1]);
        $result[]= array('getMozRankRaw', '16384', $inValidValue, $inValidValue);

        $result[]= array('getMozRank', '16384', array('umrp'=>'foo'), 'foo');
        $result[]= array('getMozRank', '16384', $inValidValue, $inValidValue);


        $result[]= array('getLinkCount', '2048', array('uid'=>'foo'), 'foo');
        $result[]= array('getLinkCount', '2048', $inValidValue, $inValidValue);

        $result[]= array('getEquityLinkCount', '2048', array('uid'=>'foo'), 'foo');
        $result[]= array('getEquityLinkCount', '2048', $inValidValue, $inValidValue);


        $result[]= array('getDomainAuthority', '68719476736', array('pda'=>'foo'), 'foo');
        $result[]= array('getDomainAuthority', '68719476736', $inValidValue, $inValidValue);


        $result[]= array('getPageAuthority', '34359738368', array('upa'=>'foo'), 'foo');
        $result[]= array('getPageAuthority', '34359738368', $inValidValue, $inValidValue);
#*/

        return $result;
    }

    /**
     * @dataProvider providerTestGetMethodeMethods
     */
    public function testGetMethodeMethods($methode, $metricCode, $callbackReturn, $assertResult)
    {
        $this->mockSUT();
        $this->mockGetCols(array($metricCode, null), $callbackReturn);

        $result = call_user_func(array($this->mockedSUT,$methode), null);
        $this->assertEquals($assertResult, $result);
    }

    /**
     * @dataProvider providerTestGetCols
     */
    public function testGetCols($callbackReturn, $assertResult)
    {
        $this->mockSUT('getCols');
        $that = $this;
        $this->mockGetPage(function($url) use($callbackReturn, $that) {
            $parse = parse_url($url);
            parse_str($parse['query'], $query);

            $assertSignature = $that->helperMakeAccessable(
                $that->SUT,
                '_getUrlSafeSignature',
                array($query['Expires'], basename($parse['path']))
            );

            $that->assertEquals($assertSignature, $query['Signature']);

            return $callbackReturn;
        });

        $result = $this->mockedSUT->getCols(1337);
        $this->assertEquals($assertResult, $result);
    }

    public function testGetUrlSafeSignature()
    {
        $expires = 1405036732;

        $sig = $this->helperMakeAccessable($this->SUT,'_getUrlSafeSignature', array($expires));

        $this->assertEquals('BH4/rZyS0Hv8/3UMU6MnOMGD5Ow=', $sig);
    }

    public function testHmacSha1()
    {
        $data = hash('sha512','foo');
        $key = md5('bar');

        $assert = hash_hmac('sha1', $data, $key, true);
        $value1 = $this->helperMakeAccessable($this->SUT,'_hmacsha1', array($data, $key));
        $value2 = $this->helperMakeAccessable($this->SUT,'_hmacsha1Rebuild', array($data, $key));

        $this->assertEquals($assert, $value1);
        $this->assertEquals($assert, $value2);
        $this->assertEquals($value1, $value2);
    }

    public function providerTestGetCols()
    {
        $SUT = '\SEOstats\Services\Mozscape';

        $validValue = array('foo'=>'bar');
        $inValidValue = $this->helperMakeAccessable($SUT, 'noDataDefaultValue', array());

        return array(
            array('', $inValidValue),
            array('{}', $inValidValue),
            array(false, $inValidValue),
            array('invalid json', null),
            array(json_encode($validValue), $validValue),
        );
    }

    protected function mockSUT($method=null, $vars=array())
    {

        $methods = array();
        switch ($method) {
            case 'getCols':
                $methods = array('_getPage');
                break;
            default:
                $methods = array('getCols');
                break;
        }

        $this->mockedSUT = $this->getMock('\SEOstats\Services\Mozscape', $methods);
        $this->mockedSUT->setUrl(array_key_exists('url',$vars) ? $vars['url'] : $this->url);
    }



    protected function mockGetCols ($assertParams, $returnValue)
    {
        $that = $this;
        $this->mockedSUT->staticExpects($this->any())
                        ->method('getCols')
                        ->will($this->returnCallback(function ($cols, $url = null) use($assertParams, $returnValue, $that) {

                            $that->assertEquals($assertParams[0], $cols);
                            $that->assertEquals($assertParams[1], $url);

                            return $returnValue;
                        }));
    }
}
