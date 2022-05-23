<?php

use com\nlf\calendar\util\HolidayUtil;
use PHPUnit\Framework\TestCase;

/**
 * 节假日测试
 * Class HolidayTest
 */
class HolidayTest extends TestCase
{

  public function test()
  {
    $holiday = HolidayUtil::getHoliday('2020-01-01');
    $this->assertEquals('2020-01-01 元旦节 2020-01-01', $holiday->toString());
  }

  public function test1()
  {
    $holiday = HolidayUtil::getHolidayByYmd(2016,10,4);
    $this->assertEquals('2016-10-01', $holiday->getTarget());
  }

  public function test2()
  {
    // 将2020-01-01修改为春节
    HolidayUtil::fix(null,'202001011120200101');
    $holiday = HolidayUtil::getHoliday('2020-01-01');
    $this->assertEquals('2020-01-01 春节 2020-01-01', $holiday->toString());
  }

  public function testRemove()
  {
    $holiday = HolidayUtil::getHolidayByYmd(2010,1,1);
    $this->assertEquals('元旦节', $holiday->getName());

    HolidayUtil::fix(null, '20100101~000000000000000000000000000');
    $holiday = HolidayUtil::getHolidayByYmd(2010,1,1);
    $this->assertNull($holiday);
  }

}
