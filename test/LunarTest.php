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

}
