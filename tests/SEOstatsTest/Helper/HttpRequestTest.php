<?php

namespace SEOstatsTest\Helper;

use SEOstatsTest\AbstractSEOstatsTestCase;
use SEOstats\Helper\HttpRequest;

class HttpRequestTest extends AbstractSEOstatsTestCase
{

    /**
     *
     * @group live
     */
    public function testGetHttpCode()
    {
        $statusCode = HttpRequest::getHttpCode('github.com');

        $this->assertSame(200, $statusCode);
    }

    /**
     *
     * @dataProvider providerTestGetFile
     */
    public function testGetFile($url, $filePath, $assertStatusCode)
    {

        unlink($filePath);
        $this->assertFalse(file_exists($filePath));
        $SUT = new HttpRequest();
        $statusCode = $SUT->getFile($url, $filePath);

        $this->assertTrue(file_exists($filePath));
        $this->assertSame($assertStatusCode, $statusCode);
    }

    /**
     *
     * @dataProvider providerTestSendRequest
     * @group live
     */
    public function testSendRequest($url, $postData, $postJson, $assertResponse)
    {
        $statusCode = HttpRequest::sendRequest($url, $postData, $postJson);

        if ($assertResponse) {
            $this->assertInternalType('string', $statusCode);
        } else {
            $this->assertFalse($statusCode);
        }
    }

    public function providerTestSendRequest()
    {
        $url = 'http://www.example.com';
        $postData = array('fieldA'=>'foo','fieldB'=>'bar');

        return array(
            array($url, $postData, false, true),
            array($url, $postData, true, true),
                array($url, false, false, true),
            array('www.' . hash('sha512','this-domain-is-not-realy-exsist') . '.com/README.md',
                  $postData, false, false),
            array('www.' . hash('sha512','this-domain-is-not-realy-exsist') . '.com/README.md',
                  $postData, true, false)
        );
    }

    public function providerTestGetFile()
    {
        $file_buffer = $this->getAssertDirectory() . 'buffer.txt';
        $url = 'https://github.com/eyecatchup/SEOstats/blob/master';
        return array(
            array($url . '/README.md', $file_buffer, true),
            array('www.' . hash('sha512','this-domain-is-not-realy-exsist') . '.com/README.md', $file_buffer, true)
        );
    }
}
