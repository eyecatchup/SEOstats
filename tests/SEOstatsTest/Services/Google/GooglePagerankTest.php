<?php

namespace SEOstatsTest\Services\Google;

use SEOstats\Services\Sitrix;

class GooglePagerankTest extends AbstractGoogleTestCase
{
    /**
     * @group google
     * @group google-pr
     * @group live
     */
    public function testGetPageRank()
    {
        $result = $this->SUT->getPageRank();

        $this->assertInternalType('string', $result);
        $this->assertGreaterThanOrEqual(0, $result);
    }
    /**
     * @group google
     * @group google-pr
     * @group live
     */
    public function testGetPageRankNoData()
    {
        $SUT = $this->SUT;
        $SUT->setUrl('http://example.com/no-data');
        $result = $SUT->getPageRank();

        $this->assertInternalType('string', $result);
        $this->assertEquals($this->helperMakeAccessable($SUT,'noDataDefaultValue',array()), $result);
    }

}
