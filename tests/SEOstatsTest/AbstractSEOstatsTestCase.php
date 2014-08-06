<?php

namespace SEOstatsTest;

use ReflectionClass;

abstract class AbstractSEOstatsTestCase extends \PHPUnit_Framework_TestCase
{

    protected $standardVersionFile;
    protected $standardVersionSubFile;

    protected $assertDirectory;

    public $mockedSUT;


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
    }

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->assertDirectory = __DIR__ . '/_assert/';
    }

    public function getAssertDirectory ($file = null)
    {
        return $this->assertDirectory . $file?:'';
    }


    public function getStandardVersions ($version, $methode)
    {
        $filePattern = $this->standardVersionSubFile;
        $methodeFile = sprintf($this->getAssertDirectory($filePattern), $version, $methode, 1);

        $result= array($version);
        if (! file_exists($methodeFile)) {
            return array($version);
        }

        $fileList = new \DirectoryIterator($this->getAssertDirectory());
        $regexp = sprintf('#' . $filePattern . '$#', $version, $methode, '\d+');

        $filtertList = new \RegexIterator($fileList, $regexp);

        $regexp = sprintf('#' . $this->standardVersionFile . '#','([^.]+)');
        foreach ($filtertList as $file) {
            preg_match($regexp, $file, $matches);
            $result[] = $matches[1];
        }
        return $result;
    }

    public function helperMakeAccessable ($object, $propertyOrMethod, $value = null)
    {
        if ( is_string($object) ) {
            $objectClass = $object;
            $object = null;
        } else {
            $objectClass = get_class($object);
        }

        if (!isset($this->reflection[$objectClass])) {
            $this->reflection[$objectClass] = new ReflectionClass($objectClass);
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

    public function mockGetPage($arg = null)
    {
        if (is_callable($arg)) {
            $this->mockedSUT->staticExpects($this->any())
                            ->method('_getPage')
                            ->will($this->returnCallback($arg));

            return;
        }

        if (! is_string($arg)) {
            $arg = 2013;
        }

        $standardFile = sprintf($this->getAssertDirectory() . $this->standardVersionFile, $arg);

        $this->mockedSUT->staticExpects($this->any())
                        ->method('_getPage')
                        ->will($this->returnValue(file_get_contents($standardFile)));
    }
}
