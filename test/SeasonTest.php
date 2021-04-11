<?php

use com\nlf\calendar\SolarSeason;
use PHPUnit\Framework\TestCase;

/**
 * 季度测试
 * Class SeasonTest
 */
class SeasonTest extends TestCase
{

  public function test1()
  {
    $season = SolarSeason::fromYm(2019, 5);
    $this->assertEquals('2019.2', $season->toString());
    $this->assertEquals('2019年2季度', $season->toFullString());

    $this->assertEquals('2019.3', $season->next(1)->toString());
    $this->assertEquals('2019年3季度', $season->next(1)->toFullString());
  }

}
