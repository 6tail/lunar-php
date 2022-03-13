<?php

use com\nlf\calendar\SolarWeek;
use com\nlf\calendar\util\SolarUtil;
use PHPUnit\Framework\TestCase;

/**
 * 星期测试
 * Class WeekTest
 */
class WeekTest extends TestCase
{

  public function test1()
  {
    //一周的开始从星期一开始计
    $start = 1;
    $week = SolarWeek::fromYmd(2019, 5, 1, $start);
    $this->assertEquals('2019.5.1', $week->toString());
    $this->assertEquals('2019年5月第1周', $week->toFullString());

    //当月共几周
    $this->assertEquals(5, SolarUtil::getWeeksOfMonth($week->getYear(), $week->getMonth(), $start));
    //当周第一天
    $this->assertEquals('2019-04-29', $week->getFirstDay()->toString());
    //当周第一天（本月）
    $this->assertEquals('2019-05-01', $week->getFirstDayInMonth()->toString());
  }


  public function test2()
  {
    //一周的开始从星期一开始计
    $start = 0;
    $week = SolarWeek::fromYmd(2019, 5, 1, $start);
    $this->assertEquals('2019.5.1', $week->toString());
    $this->assertEquals('2019年5月第1周', $week->toFullString());

    //当月共几周
    $this->assertEquals(5, SolarUtil::getWeeksOfMonth($week->getYear(), $week->getMonth(), $start));
    //当周第一天
    $this->assertEquals('2019-04-28', $week->getFirstDay()->toString());
    //当周第一天（本月）
    $this->assertEquals('2019-05-01', $week->getFirstDayInMonth()->toString());
  }

  public function test3()
  {
    $start = 0;
    $week = SolarWeek::fromYmd(2022, 5, 1, $start);
    $this->assertEquals(1, $week->getIndex());
  }

  public function test4()
  {
    $start = 2;
    $week = SolarWeek::fromYmd(2022, 5, 4, $start);
    $this->assertEquals(2, $week->getIndex());
  }

  public function test5()
  {
    $start = 0;
    $week = SolarWeek::fromYmd(2022, 3, 6, $start);
    $this->assertEquals(11, $week->getIndexInYear());
  }

}
