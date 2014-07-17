<?php

namespace SEOstatsTest\Services;

use SEOstats\Services\Social;

class SocialTest extends AbstractServiceTestCase
{
    protected $standardVersionFile = "social-%s.html";
    protected $standardVersionSubFile = "social-%s-%s-%s.html";

    public function setUp()
    {
        $this->reflection = array();

        $this->url = 'http://github.com';
    }

    public function testGetGoogleShares()
    {
        $this->markTestIncomplete();
    }

    public function testGetGooglePlusShares()
    {
        $this->markTestIncomplete();
    }

    public function testGetFacebookShares()
    {
        $this->markTestIncomplete();
    }

    public function testGetTwitterShares()
    {
        $this->markTestIncomplete();
    }

    public function testGetDeliciousShares()
    {
        $this->markTestIncomplete();
    }

    public function testGetDeliciousTopTags()
    {
        $this->markTestIncomplete();
    }

    public function testGetDiggShares()
    {
        $this->markTestIncomplete();
    }

    public function testGetLinkedInShares()
    {
        $this->markTestIncomplete();
    }

    public function testGetPinterestShares()
    {
        $this->markTestIncomplete();
    }

    public function testGetStumbleUponShares()
    {
        $this->markTestIncomplete();
    }

    public function testGetVKontakteShares()
    {
        $this->markTestIncomplete();
    }

    public function testGetXingShares()
    {
        $this->markTestIncomplete();
    }
}
