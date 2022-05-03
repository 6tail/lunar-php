<?php

use com\nlf\calendar\Solar;
use com\nlf\calendar\util\SolarUtil;
use PHPUnit\Framework\TestCase;

class SolarTest extends TestCase
{
  public function test()
  {
    $solar = Solar::fromYmd(2019, 5, 1);
    $this->assertEquals('2019-05-01', $solar->toString());
    $this->assertEquals('2019-05-01 00:00:00 星期三 (劳动节) 金牛座', $solar->toFullString());
    $this->assertEquals('二〇一九年三月廿七', $solar->getLunar()->toString());
    $this->assertEquals('二〇一九年三月廿七 己亥(猪)年 戊辰(龙)月 戊戌(狗)日 子(鼠)时 纳音[平地木 大林木 平地木 桑柘木] 星期三 西方白虎 星宿[参水猿](吉) 彭祖百忌[戊不受田田主不祥 戌不吃犬作怪上床] 喜神方位[巽](东南) 阳贵神方位[艮](东北) 阴贵神方位[坤](西南) 福神方位[艮](东北) 财神方位[坎](正北) 冲[(壬辰)龙] 煞[北]', $solar->getLunar()->toFullString());
  }

  public function test1()
  {
    $solar = Solar::fromYmd(2020, 1, 23);
    $this->assertEquals('2020-01-24', $solar->next(1)->toString());
    $this->assertEquals('2020-02-03', $solar->nextWorkday(1)->toString());
  }

  public function test2()
  {
    $solar = Solar::fromYmd(2020, 2, 3);
    $this->assertEquals('2020-01-31', $solar->next(-3)->toString());
    $this->assertEquals('2020-01-21', $solar->nextWorkday(-3)->toString());
  }

  public function test3()
  {
    $solar = Solar::fromYmd(2020, 2, 9);
    $this->assertEquals('2020-02-15', $solar->next(6)->toString());
    $this->assertEquals('2020-02-17', $solar->nextWorkday(6)->toString());
  }

  public function test4()
  {
    $solar = Solar::fromYmd(2020, 1, 17);
    $this->assertEquals('2020-01-18', $solar->next(1)->toString());
    $this->assertEquals('2020-01-19', $solar->nextWorkday(1)->toString());
  }

  public function test10()
  {
    $this->assertEquals(false, SolarUtil::isLeapYear(1500));
  }

  public function test11()
  {
    $solar = Solar::fromYmd(2022, 3, 31);
    $this->assertEquals('四', $solar->getWeekInChinese());
  }

  public function test12()
  {
    $solar = Solar::fromYmd(2021, 3, 29);
    $fs = array();
    $fs[] = '全国中小学生安全教育日';
    $this->assertEquals($fs, $solar->getFestivals());
  }
  
}
