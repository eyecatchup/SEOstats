<?php

namespace SEOstatsTest\Helper;

use SEOstats\Helper\Json;
use SEOstatsTest\AbstractSEOstatsTestCase;

class JsonTest extends AbstractSEOstatsTestCase
{
    /**
     *
     * @dataProvider providerTestDecode
     */
    public function testDecode($string, $assoc, $assert)
    {
        $result = Json::decode($string, $assoc);

        $this->assertEquals($assert, $result);
    }

    /**
     *
     * @dataProvider providerTestEncode
     */
    public function testEncode($var, $assert)
    {
        $result = Json::encode($var);

        $this->assertEquals($assert, $result);
    }

    public function providerTestDecode()
    {
        $jsonValid = '{"foo":"bar","baz":["foo","bar"]}';
        $arrayValid = array(
            'foo'=>'bar',
            'baz'=>array('foo','bar')
        );

        return array(
            array($jsonValid, true, $arrayValid),
            array($jsonValid, false, (object) $arrayValid),
        );
    }

    public function providerTestEncode()
    {
        $jsonValid = '{"foo":"bar","baz":["foo","bar"]}';
        $arrayValid = array(
            'foo'=>'bar',
            'baz'=>array('foo','bar')
        );

        return array(
            array($arrayValid, $jsonValid),
            array(utf8_decode("json-with-uml-äöü"),
                  $this->helperJsonGivesFalseOrNull() ? 'null' : false
            ),
        );
    }

    public function helperJsonGivesFalseOrNull ()
    {
        return defined('HHVM_VERSION') || version_compare(PHP_VERSION, '5.5', '<');
    }
}
