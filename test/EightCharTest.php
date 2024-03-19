<?php

use com\nlf\calendar\Solar;
use com\nlf\calendar\Lunar;
use PHPUnit\Framework\TestCase;

/**
 * 八字测试
 * Class EightCharTest
 */
class EightCharTest extends TestCase
{

  public function test1()
  {
    $solar = Solar::fromYmdHms(2005, 12, 23, 8, 37, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('乙酉', $eightChar->getYear());
    $this->assertEquals('戊子', $eightChar->getMonth());
    $this->assertEquals('辛巳', $eightChar->getDay());
    $this->assertEquals('壬辰', $eightChar->getTime());
  }

  public function test2()
  {
    $solar = Solar::fromYmdHms(1988, 2, 15, 23, 30, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('戊辰', $eightChar->getYear());
    $this->assertEquals('甲寅', $eightChar->getMonth());
    $this->assertEquals('庚子', $eightChar->getDay());
    $this->assertEquals('戊子', $eightChar->getTime());

    $eightChar->setSect(1);
    $this->assertEquals('戊辰', $eightChar->getYear());
    $this->assertEquals('甲寅', $eightChar->getMonth());
    $this->assertEquals('辛丑', $eightChar->getDay());
    $this->assertEquals('戊子', $eightChar->getTime());
  }

  public function test3()
  {
    $solar = Solar::fromYmdHms(1988, 2, 2, 22, 30, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('丁卯', $eightChar->getYear());
    $this->assertEquals('癸丑', $eightChar->getMonth());
    $this->assertEquals('丁亥', $eightChar->getDay());
    $this->assertEquals('辛亥', $eightChar->getTime());
  }

  public function testHideGan()
  {
    $solar = Solar::fromYmdHms(2005, 12, 23, 8, 37, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('辛', implode($eightChar->getYearHideGan()));
    $this->assertEquals('癸', implode($eightChar->getMonthHideGan()));
    $this->assertEquals('丙庚戊', implode($eightChar->getDayHideGan()));
    $this->assertEquals('戊乙癸', implode($eightChar->getTimeHideGan()));
  }

  public function testShiShenZhi()
  {
    $solar = Solar::fromYmdHms(2005, 12, 23, 8, 37, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('比肩', implode($eightChar->getYearShiShenZhi()));
    $this->assertEquals('食神', implode($eightChar->getMonthShiShenZhi()));
    $this->assertEquals('正官劫财正印', implode($eightChar->getDayShiShenZhi()));
    $this->assertEquals('正印偏财食神', implode($eightChar->getTimeShiShenZhi()));
  }

  public function testDiShi()
  {
    $solar = Solar::fromYmdHms(2005, 12, 23, 8, 37, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('临官', $eightChar->getYearDiShi());
    $this->assertEquals('长生', $eightChar->getMonthDiShi());
    $this->assertEquals('死', $eightChar->getDayDiShi());
    $this->assertEquals('墓', $eightChar->getTimeDiShi());

    $solar = Solar::fromYmdHms(2020, 11, 18, 17, 37, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('病', $eightChar->getYearDiShi());
    $this->assertEquals('死', $eightChar->getMonthDiShi());
    $this->assertEquals('衰', $eightChar->getDayDiShi());
    $this->assertEquals('绝', $eightChar->getTimeDiShi());

    $solar = Solar::fromYmdHms(2020, 11, 19, 17, 37, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('胎', $eightChar->getYearDiShi());
    $this->assertEquals('绝', $eightChar->getMonthDiShi());
    $this->assertEquals('长生', $eightChar->getDayDiShi());
    $this->assertEquals('死', $eightChar->getTimeDiShi());

    $solar = Solar::fromYmdHms(2020, 11, 20, 17, 37, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('绝', $eightChar->getYearDiShi());
    $this->assertEquals('胎', $eightChar->getMonthDiShi());
    $this->assertEquals('病', $eightChar->getDayDiShi());
    $this->assertEquals('长生', $eightChar->getTimeDiShi());

    $solar = Solar::fromYmdHms(2020, 11, 21, 17, 37, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('胎', $eightChar->getYearDiShi());
    $this->assertEquals('绝', $eightChar->getMonthDiShi());
    $this->assertEquals('冠带', $eightChar->getDayDiShi());
    $this->assertEquals('死', $eightChar->getTimeDiShi());

    $solar = Solar::fromYmdHms(2020, 11, 22, 17, 37, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('绝', $eightChar->getYearDiShi());
    $this->assertEquals('胎', $eightChar->getMonthDiShi());
    $this->assertEquals('帝旺', $eightChar->getDayDiShi());
    $this->assertEquals('长生', $eightChar->getTimeDiShi());

    $solar = Solar::fromYmdHms(2020, 11, 23, 17, 37, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('死', $eightChar->getYearDiShi());
    $this->assertEquals('病', $eightChar->getMonthDiShi());
    $this->assertEquals('沐浴', $eightChar->getDayDiShi());
    $this->assertEquals('帝旺', $eightChar->getTimeDiShi());

    $solar = Solar::fromYmdHms(2020, 11, 24, 17, 37, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('长生', $eightChar->getYearDiShi());
    $this->assertEquals('沐浴', $eightChar->getMonthDiShi());
    $this->assertEquals('衰', $eightChar->getDayDiShi());
    $this->assertEquals('临官', $eightChar->getTimeDiShi());

    $solar = Solar::fromYmdHms(2020, 11, 25, 17, 37, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('帝旺', $eightChar->getYearDiShi());
    $this->assertEquals('临官', $eightChar->getMonthDiShi());
    $this->assertEquals('长生', $eightChar->getDayDiShi());
    $this->assertEquals('沐浴', $eightChar->getTimeDiShi());

    $solar = Solar::fromYmdHms(2020, 11, 26, 17, 37, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('临官', $eightChar->getYearDiShi());
    $this->assertEquals('帝旺', $eightChar->getMonthDiShi());
    $this->assertEquals('病', $eightChar->getDayDiShi());
    $this->assertEquals('病', $eightChar->getTimeDiShi());

    $solar = Solar::fromYmdHms(2020, 11, 27, 17, 37, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('沐浴', $eightChar->getYearDiShi());
    $this->assertEquals('长生', $eightChar->getMonthDiShi());
    $this->assertEquals('养', $eightChar->getDayDiShi());
    $this->assertEquals('胎', $eightChar->getTimeDiShi());
  }

  public function testNaYin()
  {
    $solar = Solar::fromYmdHms(2005, 12, 23, 8, 37, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('泉中水', $eightChar->getYearNaYin());
    $this->assertEquals('霹雳火', $eightChar->getMonthNaYin());
    $this->assertEquals('白蜡金', $eightChar->getDayNaYin());
    $this->assertEquals('长流水', $eightChar->getTimeNaYin());
  }

  public function testTaiYuan()
  {
    $solar = Solar::fromYmdHms(2005, 12, 23, 8, 37, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('己卯', $eightChar->getTaiYuan());

    $solar = Solar::fromYmdHms(1995, 12, 18, 10, 28, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('己卯', $eightChar->getTaiYuan());
  }

  public function testMingGong()
  {
    $solar = Solar::fromYmdHms(2005, 12, 23, 8, 37, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('己丑', $eightChar->getMingGong());

    $solar = Solar::fromYmdHms(1998, 6, 11, 4, 28, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('辛酉', $eightChar->getMingGong());

    $solar = Solar::fromYmdHms(1995, 12, 18, 10, 28, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('戊子', $eightChar->getMingGong());
  }

  public function testShenGong()
  {
    $solar = Solar::fromYmdHms(1995, 12, 18, 10, 28, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('壬午', $eightChar->getShenGong());
  }

  public function testShenGong1()
  {
    $solar = Solar::fromYmdHms(1994, 12, 6, 2, 0, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('乙丑', $eightChar->getShenGong());
  }

  public function testShenGong2()
  {
    $solar = Solar::fromYmdHms(1990, 12, 11, 6, 0, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('庚辰', $eightChar->getShenGong());
  }

  public function testShenGong3()
  {
    $solar = Solar::fromYmdHms(1993, 5, 23, 4, 0, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('庚申', $eightChar->getShenGong());
  }

  public function test4()
  {
    $lunar = Lunar::fromYmd(1985, 12, 27);
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('1995-11-05', $eightChar->getYun(1)->getStartSolar()->toYmd());
  }

  public function test5()
  {
    $lunar = Lunar::fromYmd(1985, 1, 27);
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('1989-03-28', $eightChar->getYun(1)->getStartSolar()->toYmd());
  }

  public function test6()
  {
    $lunar = Lunar::fromYmd(1986, 12, 27);
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('1990-04-15', $eightChar->getYun(1)->getStartSolar()->toYmd());
  }

  public function test7()
  {
    $solar = Solar::fromYmdHms(2053, 2, 18, 13, 0, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('癸酉 甲寅 乙酉 癸未', $eightChar->toString());
  }

  public function test8()
  {
    $solar = Solar::fromYmdHms(8071, 12, 30, 13, 0, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('辛卯 庚子 己丑 辛未', $eightChar->toString());
  }

  public function test11()
  {
    $lunar = Lunar::fromYmdHms(1987, 12, 28, 23, 30, 0);
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('戊辰', $eightChar->getYear());
    $this->assertEquals('甲寅', $eightChar->getMonth());
    $this->assertEquals('庚子', $eightChar->getDay());
    $this->assertEquals('戊子', $eightChar->getTime());
  }

  public function test12()
  {
    $solarList = Solar::fromBaZi('丙辰', '丁酉', '丙子', '甲午');
    $actual = array();
    foreach ($solarList as $solar) {
      $actual[] = $solar->toYmdHms();
    }

    $expected = array('1916-10-06 12:00:00', '1976-09-21 12:00:00');
    $this->assertEquals($expected, $actual);
  }

  public function test13()
  {
    $solarList = Solar::fromBaZi('壬寅', '庚戌', '己未', '乙亥');
    $actual = array();
    foreach ($solarList as $solar) {
      $actual[] = $solar->toYmdHms();
    }

    $expected = array('2022-11-02 22:00:00');
    $this->assertEquals($expected, $actual);
  }

  public function test14()
  {
    $solarList = Solar::fromBaZi('己卯', '辛未', '甲戌', '壬申');
    $actual = array();
    foreach ($solarList as $solar) {
      $actual[] = $solar->toYmdHms();
    }

    $expected = array('1939-08-05 16:00:00', '1999-07-21 16:00:00');
    $this->assertEquals($expected, $actual);
  }

  public function test15()
  {
    $solarList = Solar::fromBaZi('庚子', '戊子', '己卯', '庚午');
    $actual = array();
    foreach ($solarList as $solar) {
      $actual[] = $solar->toYmdHms();
    }

    $expected = array('1901-01-01 12:00:00', '1960-12-17 12:00:00');
    $this->assertEquals($expected, $actual);
  }

  public function test16()
  {
    $solarList = Solar::fromBaZi('庚子', '癸未', '乙丑', '丁亥');
    $actual = array();
    foreach ($solarList as $solar) {
      $actual[] = $solar->toYmdHms();
    }

    $expected = array('1960-08-05 22:00:00', '2020-07-21 22:00:00');
    $this->assertEquals($expected, $actual);
  }

  public function test17()
  {
    $lunar = Solar::fromYmdHms('1999', '06', '07', '09', '11', '00')->getLunar()->getEightChar();
    $actual = $lunar->toString();

    $expected = '己卯 庚午 庚寅 辛巳';
    $this->assertEquals($expected, $actual);
  }

  public function test18()
  {
    $solarList = Solar::fromBaZiBySectAndBaseYear('癸卯','甲寅','甲寅','甲子', 2, 1843);
    $actual = array();
    foreach ($solarList as $solar) {
      $actual[] = $solar->toYmdHms();
    }

    $expected = array('1843-02-09 00:00:00', '2023-02-25 00:00:00');
    $this->assertEquals($expected, $actual);
  }

  public function test19()
  {
    $solarList = Solar::fromBaZi('己亥','丁丑','壬寅','戊申');
    $actual = array();
    foreach ($solarList as $solar) {
      $actual[] = $solar->toYmdHms();
    }

    $expected = array('1900-01-29 16:00:00', '1960-01-15 16:00:00');
    $this->assertEquals($expected, $actual);
  }

  public function test20()
  {
    $solarList = Solar::fromBaZi('己亥','丙子','癸酉','庚申');
    $actual = array();
    foreach ($solarList as $solar) {
      $actual[] = $solar->toYmdHms();
    }

    $expected = array('1959-12-17 16:00:00');
    $this->assertEquals($expected, $actual);
  }

  public function test21()
  {
    $solarList = Solar::fromBaZiBySectAndBaseYear('乙亥','乙酉','乙酉','乙酉', 1, 1000);
    $actual = array();
    foreach ($solarList as $solar) {
      $actual[] = $solar->toYmdHms();
    }

    $expected = array('1095-09-23 18:00:00', '1155-09-08 18:00:00', '1335-09-23 18:00:00', '1395-09-08 18:00:00', '1575-09-23 18:00:00', '1635-09-18 18:00:00', '1815-10-05 18:00:00', '1875-09-20 18:00:00');
    $this->assertEquals($expected, $actual);
  }

  public function test22()
  {
    $solarList = Solar::fromBaZi('癸卯','乙卯','丙辰','丁酉');
    $actual = array();
    foreach ($solarList as $solar) {
      $actual[] = $solar->toYmdHms();
    }

    $expected = array('1903-03-29 18:00:00', '1963-03-14 18:00:00');
    $this->assertEquals($expected, $actual);
  }

  public function test23()
  {
    $solarList = Solar::fromBaZiBySectAndBaseYear('甲辰','丙寅','壬戌','壬子', 2, 1900);
    $actual = array();
    foreach ($solarList as $solar) {
      $actual[] = $solar->toYmdHms();
    }

    $expected = array('2024-02-28 23:00:00');
    $this->assertEquals($expected, $actual);
  }

  public function test24()
  {
    $solarList = Solar::fromBaZiBySectAndBaseYear('甲辰','丙寅','癸亥','壬子', 1, 1900);
    $actual = array();
    foreach ($solarList as $solar) {
      $actual[] = $solar->toYmdHms();
    }

    $expected = array('2024-02-29 00:00:00');
    $this->assertEquals($expected, $actual);
  }

  public function test25()
  {
    $solarList = Solar::fromBaZiBySectAndBaseYear('丁卯','丁未','甲申','乙丑', 1, 1900);
    $actual = array();
    foreach ($solarList as $solar) {
      $actual[] = $solar->toYmdHms();
    }

    $expected = array('1987-08-03 02:00:00');
    $this->assertEquals($expected, $actual);
  }

  public function test26()
  {
    $solar = Solar::fromYmdHms(2000, 1, 23, 22, 0, 0);
    $lunar = $solar->getLunar();
    $eightChar = $lunar->getEightChar();
    $this->assertEquals('己卯', $eightChar->getYear());
    $this->assertEquals('丁丑', $eightChar->getMonth());
    $this->assertEquals('庚辰', $eightChar->getDay());
    $this->assertEquals('丁亥', $eightChar->getTime());
  }

}
