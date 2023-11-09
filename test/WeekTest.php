<?php

use com\nlf\calendar\Solar;
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

  public function test6()
  {
    $solar = Solar::fromYmd(1582, 10, 1);
    $this->assertEquals(1, $solar->getWeek());
  }

  public function test7()
  {
    $solar = Solar::fromYmd(1582, 10, 15);
    $this->assertEquals(5, $solar->getWeek());
  }

  public function test8()
  {
    $solar = Solar::fromYmd(2023, 1, 31);
    $this->assertEquals(2, $solar->getWeek());
  }

  public function test9()
  {
    $this->assertEquals(0, Solar::fromYmd(1129, 11, 17)->getWeek());
  }

  public function test10()
  {
    $this->assertEquals(5, Solar::fromYmd(1129, 11, 1)->getWeek());
  }

  public function test11()
  {
    $this->assertEquals(4, Solar::fromYmd(8, 11, 1)->getWeek());
  }

  public function test12()
  {
    $this->assertEquals(0, Solar::fromYmd(1582, 9, 30)->getWeek());
  }

  public function test13()
  {
    $this->assertEquals(1, Solar::fromYmd(1582, 1, 1)->getWeek());
  }

  public function test14()
  {
    $this->assertEquals(6, Solar::fromYmd(1500, 2, 29)->getWeek());
  }

  public function test15()
  {
    $this->assertEquals(3, Solar::fromYmd(9865, 7, 26)->getWeek());
  }

  public function test16()
  {
    $this->assertEquals(6, Solar::fromYmd(1961, 9, 30)->getWeek());
    $this->assertEquals(6, Solar::fromYmdHms(1961, 9, 30, 23, 59, 59)->getWeek());
  }

  public function test17()
  {
    $this->assertEquals(3, Solar::fromYmdHms(2021, 9, 15, 20, 0, 0)->getWeek());
    $this->assertEquals(3, Solar::fromYmdHms(2021, 9, 15, 23, 59, 59)->getWeek());
  }

}
