<?php

namespace SEOstatsTest\Services;

use SEOstatsTest\AbstractSEOstatsTestCase;
use SEOstats\Services\Mozscape;
use ReflectionClass;

class MozscapeTest extends AbstractSEOstatsTestCase
{

    public function setUp()
    {
        $this->reflection = array();

        $this->url = 'http://github.com';
        $this->SUT = new \SEOstats\Services\Mozscape();
        $this->SUT->setUrl($this->url);

    }

    public function testFoo()
    {
        
    }
}
