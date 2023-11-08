<?php

use com\nlf\calendar\LunarMonth;
use PHPUnit\Framework\TestCase;

/**
 * 月份测试
 * Class SeasonTest
 */
class LunarMonthTest extends TestCase
{

  public function test1()
  {
    $month = LunarMonth::fromYm(2023, 1);
    $this->assertEquals(1, $month->getIndex());
    $this->assertEquals('甲寅', $month->getGanZhi());
  }

  public function test2()
  {
    $month = LunarMonth::fromYm(2023, -2);
    $this->assertEquals(3, $month->getIndex());
    $this->assertEquals('丙辰', $month->getGanZhi());
  }

  public function test3()
  {
    $month = LunarMonth::fromYm(2023, 3);
    $this->assertEquals(4, $month->getIndex());
    $this->assertEquals('丁巳', $month->getGanZhi());
  }

  public function test4()
  {
    $month = LunarMonth::fromYm(2024, 1);
    $this->assertEquals(1, $month->getIndex());
    $this->assertEquals('丙寅', $month->getGanZhi());
  }

  public function test5()
  {
    $month = LunarMonth::fromYm(2023, 12);
    $this->assertEquals(13, $month->getIndex());
    $this->assertEquals('丙寅', $month->getGanZhi());
  }

  public function test6()
  {
    $month = LunarMonth::fromYm(2022, 1);
    $this->assertEquals(1, $month->getIndex());
    $this->assertEquals('壬寅', $month->getGanZhi());
  }

  public function test7()
  {
    $month = LunarMonth::fromYm(2016, 1);
    $this->assertEquals(1, $month->getIndex());
  }

}
