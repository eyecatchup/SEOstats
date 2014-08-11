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
        $this->mockGetPage ($version);

        $result = call_user_func(get_class($this->mockedSUT) . '::getVisibilityIndex', $this->url);

        if ($status) {
            $assertValue ='h3 foo1';

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
      $this->mockSUT('api',array('url'=>'http://www.spiegel.de'));
      $this->mockGetApi($version, $result);

      $result = call_user_func(get_class($this->mockedSUT) . '::getVisibilityIndexByApi', $this->url);

      if ($status) {
        $assertValue ='355.9236';
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

    public function providerTestGetVisibilityIndexByApi()
    {
      return array(
        array('2014', 'vi', true),
        array('2014','failed', false)
      );
    }

    protected function mockGetApi($version, $result) {
      $standardFile = sprintf($this->getAssertDirectory() . $this->standardVersionSubFileJson, $version, 'api', $result);
      $this->mockedSUT->staticExpects($this->any())
        ->method('_getApi')
        ->will($this->returnValue(file_get_contents($standardFile)));
    }

    protected function hasApiKey() {
      return true;
    }

    protected function mockSUT($method=null, $vars=array())
    {
        switch($method) {
          case 'api':
            $methods = array('hasApiKey');
            break;
          default:
            $methods = array('_getPage');
            break;
        }

        $this->mockedSUT = $this->getMock('\SEOstats\Services\Sistrix', $methods);
        $this->mockedSUT->setUrl(array_key_exists('url',$vars) ? $vars['url'] : $this->url);
    }
}
