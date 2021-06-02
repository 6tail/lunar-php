<?php

use com\nlf\calendar\Solar;
use PHPUnit\Framework\TestCase;
use com\nlf\calendar\Lunar;

class LunarTest extends TestCase
{
  public function test()
  {
    $lunar = Lunar::fromYmdHms(2019, 3, 27, 0, 0, 0);
    $this->assertEquals('二〇一九年三月廿七', $lunar->toString());
    $this->assertEquals('二〇一九年三月廿七 己亥(猪)年 戊辰(龙)月 戊戌(狗)日 子(鼠)时 纳音[平地木 大林木 平地木 桑柘木] 星期三 (七殿泰山王诞) 西方白虎 星宿[参水猿](吉) 彭祖百忌[戊不受田田主不祥 戌不吃犬作怪上床] 喜神方位[巽](东南) 阳贵神方位[艮](东北) 阴贵神方位[坤](西南) 福神方位[坎](正北) 财神方位[坎](正北) 冲[(壬辰)龙] 煞[北]', $lunar->toFullString());
    $this->assertEquals('2019-05-01', $lunar->getSolar()->toString());
    $this->assertEquals('2019-05-01 00:00:00 星期三 (劳动节) 金牛座', $lunar->getSolar()->toFullString());
  }

  public function test1()
  {
    $solar = Solar::fromYmdHms(1, 1, 1, 12, 0, 0);
    $this->assertEquals('〇年冬月十八', $solar->getLunar()->toString());
  }

  public function test2()
  {
    $solar = Solar::fromYmdHms(9999, 12, 31, 12, 0, 0);
    $this->assertEquals('九九九九年腊月初二', $solar->getLunar()->toString());
  }

  public function test3()
  {
    $lunar = Lunar::fromYmdHms(0, 11, 18, 12, 0, 0);
    $this->assertEquals('0001-01-01', $lunar->getSolar()->toString());
  }

  public function test4()
  {
    $lunar = Lunar::fromYmdHms(9999, 12, 2, 12, 0, 0);
    $this->assertEquals('9999-12-31', $lunar->getSolar()->toString());
  }

  public function test5()
  {
    $lunar = Lunar::fromYmdHms(1905, 1, 1, 12, 0, 0);
    $this->assertEquals('1905-02-04', $lunar->getSolar()->toString());
  }

  public function test6()
  {
    $lunar = Lunar::fromYmdHms(2038, 12, 29, 12, 0, 0);
    $this->assertEquals('2039-01-23', $lunar->getSolar()->toString());
  }


  public function test7()
  {
    $lunar = Lunar::fromYmdHms(2020, -4, 2, 13, 0, 0);
    $this->assertEquals('二〇二〇年闰四月初二', $lunar->toString());
    $this->assertEquals('2020-05-24', $lunar->getSolar()->toString());
  }


  public function test8()
  {
    $lunar = Lunar::fromYmdHms(2020, 12, 10, 13, 0, 0);
    $this->assertEquals('二〇二〇年腊月初十', $lunar->toString());
    $this->assertEquals('2021-01-22', $lunar->getSolar()->toString());
  }


  public function test9()
  {
    $lunar = Lunar::fromYmdHms(1500, 1, 1, 12, 0, 0);
    $this->assertEquals('1500-01-31', $lunar->getSolar()->toString());
  }


  public function test10()
  {
    $lunar = Lunar::fromYmdHms(1500, 12, 29, 12, 0, 0);
    $this->assertEquals('1501-01-18', $lunar->getSolar()->toString());
  }

  public function test11()
  {
    $solar = new Solar(1500, 1, 1, 12, 0, 0);
    $this->assertEquals('一四九九年腊月初一', $solar->getLunar()->toString());
  }


  public function test12()
  {
    $solar = new Solar(1500, 12, 31, 12, 0, 0);
    $this->assertEquals('一五〇〇年腊月十一', $solar->getLunar()->toString());
  }


  public function test13()
  {
    $solar = new Solar(1582, 10, 4, 12, 0, 0);
    $this->assertEquals('一五八二年九月十八', $solar->getLunar()->toString());
  }


  public function test14()
  {
    $solar = new Solar(1582, 10, 15, 12, 0, 0);
    $this->assertEquals('一五八二年九月十九', $solar->getLunar()->toString());
  }


  public function test15()
  {
    $lunar = Lunar::fromYmdHms(1582, 9, 18, 12, 0, 0);
    $this->assertEquals('1582-10-04', $lunar->getSolar()->toString());
  }


  public function test16()
  {
    $lunar = Lunar::fromYmdHms(1582, 9, 19, 12, 0, 0);
    $this->assertEquals('1582-10-15', $lunar->getSolar()->toString());
  }


  public function test17()
  {
    $lunar = Lunar::fromYmdHms(2019, 12, 12, 11, 22, 0);
    $this->assertEquals('2020-01-06', $lunar->getSolar()->toString());
  }

  public function test18()
  {
    $lunar = Lunar::fromYmd(2021, 12, 29);
    $fs = $lunar->getFestivals();
    $this->assertEquals('除夕', $fs[0]);
  }

  public function test19()
  {
    $lunar = Lunar::fromYmd(2020, 12, 30);
    $fs = $lunar->getFestivals();
    $this->assertEquals('除夕', $fs[0]);
  }

  public function test20()
  {
    $lunar = Lunar::fromYmd(2020, 12, 29);
    $fs = $lunar->getFestivals();
    $this->assertEquals(0, count($fs));
  }

  public function test21()
  {
    $solar = Solar::fromYmd(2022, 1, 31);
    $lunar = $solar->getLunar();
    $fs = $lunar->getFestivals();
    $this->assertEquals('除夕', $fs[0]);
  }

}
