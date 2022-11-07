<?php

use com\nlf\calendar\Solar;
use com\nlf\calendar\Lunar;
use PHPUnit\Framework\TestCase;

/**
 * 儒略日测试
 * Class JulianDayTest
 */
class JulianDayTest extends TestCase
{

  public function test1()
  {
    $solar = Solar::fromYmd(2020, 7, 15);
    $this->assertEquals(2459045.5, $solar->getJulianDay());
  }

  public function test2()
  {
    $solar = Solar::fromJulianDay(2459045.5);
    $this->assertEquals('2020-07-15 00:00:00', $solar->toYmdHms());
  }

}
