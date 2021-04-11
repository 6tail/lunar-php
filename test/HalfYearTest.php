<?php

use com\nlf\calendar\SolarHalfYear;
use PHPUnit\Framework\TestCase;

/**
 * 半年测试
 * Class HalfYearTest
 */
class HalfYearTest extends TestCase
{

  public function test1()
  {
    $halfYear = SolarHalfYear::fromYm(2019, 5);
    $this->assertEquals('2019.1', $halfYear->toString());
    $this->assertEquals('2019年上半年', $halfYear->toFullString());

    $this->assertEquals('2019.2', $halfYear->next(1)->toString());
    $this->assertEquals('2019年下半年', $halfYear->next(1)->toFullString());
  }

}
