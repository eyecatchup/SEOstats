<?php

namespace SEOstatsTest;

use ReflectionClass;

abstract class AbstractSEOstatsTestCase extends \PHPUnit_Framework_TestCase
{

    protected $standardVersionFile;
    protected $standardVersionSubFile;

    protected $assertDirectory;

    protected $SUT;

    protected $mockedSUT;


    /**
     *
     * @var string
     */
    protected $url;


    /**
     *
     * @var string
     */
    protected $reflection = array();

    public function setup()
    {
        parent::setup();

        $this->assertDirectory = __DIR__ . '/_assert/';
    }


    protected function getStandardVersions ($version, $methode)
    {
        $filePattern = $this->standardVersionSubFile;

        $methodeFile = $this->assertDirectory . sprintf($filePattern, $version, $methode, 1);


        $result= array($version);
        if (! file_exists($methodeFile)) {
            return array($version);
        }

        $fileList = new \DirectoryIterator($this->assertDirectory);
        $regexp = sprintf('#' . $filePattern . '$#', $version, $methode, '\d+');
        $filtertList = new \RegexIterator($fileList, $regexp);

        $regexp = sprintf('#' . $this->standardVersionFile . '#','([^.]+)');
        foreach ($filtertList as $file) {
            preg_match($regexp, $file, $matches);
            $result[] = $matches[1];
        }
        return $result;
    }

    protected function helperMakeAccessable ($object, $propertyOrMethod, $value = null)
    {
        $objectClass = get_class($object);
        if (!isset($this->reflection[$objectClass])) {
            $this->reflection[$objectClass] = new ReflectionClass($object);
        }
        $reflection = $this->reflection[$objectClass];
        $isMethod = $reflection->hasMethod($propertyOrMethod);

        if ($isMethod) {
            $reflectionSub = $reflection->getMethod($propertyOrMethod);
        } else {
            $reflectionSub = $reflection->getProperty($propertyOrMethod);
        }

        $reflectionSub->setAccessible(true);

        if (!is_null($value)) {
            if ($isMethod) {
                return $reflectionSub->invokeArgs($object, $value);
            } else {
                $reflectionSub->setValue($object, $value);
            }
        }

        return $reflectionSub;
    }

    protected function mockGetPage()
    {
        $standardFile = sprintf('%s/_assert/' . $this->standardVersionFile, __DIR__, 2013);

        $this->mockedSUT->staticExpects($this->any())
                        ->method('_getPage')
                        ->will($this->returnValue(file_get_contents($standardFile)));
    }
}
