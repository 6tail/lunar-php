<?php

use com\nlf\calendar\LunarYear;
use com\nlf\calendar\SolarYear;
use PHPUnit\Framework\TestCase;

/**
 * 年份测试
 * Class YearTest
 */
class YearTest extends TestCase
{

  public function test1()
  {
    $year = SolarYear::fromYear(2019);
    $this->assertEquals('2019', $year->toString());
    $this->assertEquals('2019年', $year->toFullString());

    $this->assertEquals('2020', $year->next(1)->toString());
    $this->assertEquals('2020年', $year->next(1)->toFullString());
  }

  public function test2()
  {
    $year = LunarYear::fromYear(2017);
    $this->assertEquals('二龙治水', $year->getZhiShui());
    $this->assertEquals('二人分饼', $year->getFenBing());
  }

  public function test3()
  {
    $year = LunarYear::fromYear(2018);
    $this->assertEquals('二龙治水', $year->getZhiShui());
    $this->assertEquals('八人分饼', $year->getFenBing());
  }

  public function test4()
  {
    $year = LunarYear::fromYear(5);
    $this->assertEquals('三龙治水', $year->getZhiShui());
    $this->assertEquals('一人分饼', $year->getFenBing());
  }

  public function test5()
  {
    $year = LunarYear::fromYear(2021);
    $this->assertEquals('十一牛耕田', $year->getGengTian());
  }

  public function test6()
  {
    $year = LunarYear::fromYear(2018);
    $this->assertEquals('三日得金', $year->getDeJin());
  }

}
