<?php

namespace SEOstatsTest\Services;

use SEOstats\Services\Sitrix;

class SistrixTest extends AbstractServiceTestCase
{
    protected $standardVersionFile = "sistrix-%s.html";
    protected $standardVersionSubFile = "sistrix-%s-%s-%s.html";

    public function setUp()
    {
        $this->reflection = array();

        $this->url = 'http://github.com';
    }

    /**
     * @dataProvider providerTestGetVisibilityIndex
     * @todo value controll
     */
    public function testGetVisibilityIndex($version, $status)
    {
        $this->mockSUT();
        $this->mockGetPage ($version);

        $result = call_user_func(get_class($this->mockedSUT) . '::getVisibilityIndex', $this->url);

        if ($status) {
            $assertValue ='h3 foo1';

        } else {
            $assertValue = $this->helperMakeAccessable($this->mockedSUT, 'noDataDefaultValue', array());
        }

        $this->assertEquals($assertValue, $result);
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

    protected function mockSUT($method=null, $vars=array())
    {
        $methods = array('_getPage');

        $this->mockedSUT = $this->getMock('\SEOstats\Services\Sistrix', $methods);
        $this->mockedSUT->setUrl(array_key_exists('url',$vars) ? $vars['url'] : $this->url);
    }
}
