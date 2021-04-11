<?php

use com\nlf\calendar\SolarMonth;
use PHPUnit\Framework\TestCase;

/**
 * 月份测试
 * Class SeasonTest
 */
class MonthTest extends TestCase
{

  public function test1()
  {
    $month = SolarMonth::fromYm(2019, 5);
    $this->assertEquals('2019-5', $month->toString());
    $this->assertEquals('2019年5月', $month->toFullString());

    $this->assertEquals('2019-6', $month->next(1)->toString());
    $this->assertEquals('2019年6月', $month->next(1)->toFullString());
  }

}
