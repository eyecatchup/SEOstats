<?php

namespace SEOstatsTest\Services;

use SEOstatsTest\AbstractSEOstatsTestCase;
use ReflectionClass;

abstract class AbstractServiceTestCase extends AbstractSEOstatsTestCase
{

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->assertDirectory .= 'Service/';
    }
}
