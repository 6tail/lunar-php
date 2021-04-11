<?php

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

}
