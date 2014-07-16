<?php

namespace SEOstatsTest\Services;

use SEOstats\Services\OpenSiteExplorer;

class OpenSiteExplorerTest extends AbstractServiceTestCase
{
    protected $standardVersionFile = "ose-siteinfo-%s.html";
    protected $standardVersionSubFile = "ose-siteinfo-%s-%s-%s.html";

    public function setUp()
    {
        $this->reflection = array();

        $this->url = 'http://github.com';
    }

    /**
     * @dataProvider providerTestGetPageMetrics
     * @todo value controll
     */
    public function testGetPageMetrics($version, $status)
    {
        $this->mockSUT();
        $this->mockGetPage ($version);

        $result = call_user_func(get_class($this->mockedSUT) . '::getPageMetrics', $this->url);

        if ($status) {
            $assertPropertyArray = array('domainAuthority','pageAuthority','justDiscovered',
                                         'justDiscovered', 'linkingRootDomains', 'totalLinks');

            $assertSubPropertyArray = array('result','unit','descr');

            foreach ($assertPropertyArray as $assertProperty ) {
                $this->assertTrue( isset($result->{$assertProperty}) );

                foreach ($assertSubPropertyArray as $assertSubProperty ) {
                    $this->assertTrue( isset($result->{$assertProperty}->{$assertSubProperty}) );
                }
            }

        } else {
            $this->assertEquals($this->helperMakeAccessable($this->mockedSUT, 'noDataDefaultValue', array()), $result);
        }
    }


    public function providerTestGetPageMetrics()
    {
        return array(
            array('2014', true),
            array('failed', false)
        );
    }

    protected function mockSUT($method=null, $vars=array())
    {
        $methods = array('_getPage');

        $this->mockedSUT = $this->getMock('\SEOstats\Services\OpenSiteExplorer', $methods);
        $this->mockedSUT->setUrl(array_key_exists('url',$vars) ? $vars['url'] : $this->url);
    }
}
