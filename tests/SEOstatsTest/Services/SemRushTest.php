<?php

namespace SEOstatsTest\Services;

use SEOstats\Services\SemRush;

class SemRushTest extends AbstractServiceTestCase
{
    protected $standardVersionFile = "semrush-%s.html";
    protected $standardVersionSubFile = "semrush-%s-%s-%s.html";

    public function setUp()
    {
        $this->reflection = array();

        $this->url = 'http://github.com';
    }

    public function testGetDBs()
    {
        $this->markTestIncomplete();
    }

    public function testGetParams()
    {
        $this->markTestIncomplete();
    }

    public function testGetDomainRank()
    {
        $this->markTestIncomplete();
    }

    public function testGetDomainRankHistory()
    {
        $this->markTestIncomplete();
    }

    public function testGetOrganicKeywords()
    {
        $this->markTestIncomplete();
    }

    public function testGetCompetitors()
    {
        $this->markTestIncomplete();
    }

    public function testGetDomainGraph()
    {
        $this->markTestIncomplete();
    }

    public function testGetApiData()
    {
        $this->markTestIncomplete();
    }

    public function testGetBackendUrl()
    {
        $this->markTestIncomplete();
    }

    public function testGetWidgetUrl()
    {
        $this->markTestIncomplete();
    }

    public function testExc()
    {
        $this->markTestIncomplete();
    }
}
