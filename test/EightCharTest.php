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
    $this->assertEquals('丁丑', $eightChar->getShenGong());
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

}
