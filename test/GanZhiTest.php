<?php

use com\nlf\calendar\Solar;
use PHPUnit\Framework\TestCase;

/**
 * 干支测试
 * Class GanZhiTest
 */
class GanZhiTest extends TestCase
{

  public function test1()
  {
    $solar = Solar::fromYmdHms(2020, 1, 1, 13, 22, 0);
    $lunar = $solar->getLunar();
    $this->assertEquals("己亥", $lunar->getYearInGanZhi());
    $this->assertEquals("己亥", $lunar->getYearInGanZhiByLiChun());
    $this->assertEquals("己亥", $lunar->getYearInGanZhiExact());
  }

  public function test2()
  {
    $solar = Solar::fromYmd(2012, 12, 27);
    $lunar = $solar->getLunar();
    $this->assertEquals("壬辰", $lunar->getYearInGanZhi());
    $this->assertEquals("壬子", $lunar->getMonthInGanZhi());
    $this->assertEquals("壬戌", $lunar->getDayInGanZhi());
  }

  public function test3()
  {
    $solar = Solar::fromYmd(2012, 12, 20);
    $lunar = $solar->getLunar();
    $this->assertEquals("壬辰", $lunar->getYearInGanZhi());
    $this->assertEquals("壬子", $lunar->getMonthInGanZhi());
    $this->assertEquals("乙卯", $lunar->getDayInGanZhi());
  }

  public function test4()
  {
    $solar = Solar::fromYmd(2012, 11, 20);
    $lunar = $solar->getLunar();
    $this->assertEquals("壬辰", $lunar->getYearInGanZhi());
    $this->assertEquals("辛亥", $lunar->getMonthInGanZhi());
    $this->assertEquals("乙酉", $lunar->getDayInGanZhi());
  }

  public function test15()
  {
    $solar = Solar::fromYmd(1988, 2, 15);
    $lunar = $solar->getLunar();
    $this->assertEquals("丁卯", $lunar->getYearInGanZhi());
  }

  public function test16()
  {
    $solar = Solar::fromYmdHms(1988, 2, 15, 23, 30,0);
    $lunar = $solar->getLunar();
    $this->assertEquals("丁卯", $lunar->getYearInGanZhi());
    $this->assertEquals("戊辰", $lunar->getYearInGanZhiByLiChun());
    $this->assertEquals("戊辰", $lunar->getYearInGanZhiExact());
  }

  public function test17()
  {
    $solar = Solar::fromYmdHms(2019, 2, 8, 13, 22, 0);
    $lunar = $solar->getLunar();
    $this->assertEquals("己亥", $lunar->getYearInGanZhi());
    $this->assertEquals("己亥", $lunar->getYearInGanZhiByLiChun());
    $this->assertEquals("己亥", $lunar->getYearInGanZhiExact());

    $this->assertEquals("丙寅", $lunar->getMonthInGanZhi());
    $this->assertEquals("丙寅", $lunar->getMonthInGanZhiExact());
  }

  public function test18()
  {
    $solar = Solar::fromYmdHms(2020,2,4,13,22,0);
    $lunar = $solar->getLunar();
    $this->assertEquals("庚子", $lunar->getYearInGanZhi());
    $this->assertEquals("庚子", $lunar->getYearInGanZhiByLiChun());
    $this->assertEquals("己亥", $lunar->getYearInGanZhiExact());

    $this->assertEquals("戊寅", $lunar->getMonthInGanZhi());
    $this->assertEquals("丁丑", $lunar->getMonthInGanZhiExact());
  }

}
