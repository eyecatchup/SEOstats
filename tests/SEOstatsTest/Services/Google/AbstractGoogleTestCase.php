<?php

namespace SEOstatsTest\Services\Google;

use SEOstatsTest\Services\AbstractServiceTestCase;
use ReflectionClass;

abstract class AbstractGoogleTestCase extends AbstractServiceTestCase
{

    protected function mockSUT($method=null, $vars=array())
    {
        switch($method) {
            case "getSerps":
                $methods = array('gCurl');
                break;
            default:
                $methods = array('_getPage');
                break;
        }

        $this->mockedSUT = $this->getMock( get_class($this->SUT), $methods);
        $this->mockedSUT->setUrl(array_key_exists('url',$vars) ? $vars['url'] : $this->url);
    }

    public function setUp()
    {
        parent::setUp();
        $this->reflection = array();

        $this->url = 'http://github.com';
        $this->SUT = new \SEOstats\Services\Google();
        $this->SUT->setUrl($this->url);
    }
}
