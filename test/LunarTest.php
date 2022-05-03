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
    $this->assertEquals('二〇一九年三月廿七 己亥(猪)年 戊辰(龙)月 戊戌(狗)日 子(鼠)时 纳音[平地木 大林木 平地木 桑柘木] 星期三 西方白虎 星宿[参水猿](吉) 彭祖百忌[戊不受田田主不祥 戌不吃犬作怪上床] 喜神方位[巽](东南) 阳贵神方位[艮](东北) 阴贵神方位[坤](西南) 福神方位[艮](东北) 财神方位[坎](正北) 冲[(壬辰)龙] 煞[北]', $lunar->toFullString());
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
    $solar = Solar::fromYmdHms(9998, 1, 1, 12, 0, 0);
    $this->assertEquals('九九九七年腊月十一', $solar->getLunar()->toString());
  }

  public function test3()
  {
    $lunar = Lunar::fromYmdHms(1, 11, 18, 12, 0, 0);
    $this->assertEquals('0001-12-22', $lunar->getSolar()->toString());
  }

  public function test4()
  {
    $lunar = Lunar::fromYmdHms(7777, 12, 2, 12, 0, 0);
    $this->assertEquals('7778-01-09', $lunar->getSolar()->toString());
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

  public function test22()
  {
    $lunar = Lunar::fromYmd(2022, 1, 1);
    $this->assertEquals('六白金开阳', $lunar->getYearNineStar()->toString());
  }

  public function test23()
  {
    $lunar = Lunar::fromYmd(2033, 1, 1);
    $this->assertEquals('四绿木天权', $lunar->getYearNineStar()->toString());
  }

  public function test24()
  {
    $lunar = Lunar::fromYmd(2033, -11, 1);
    $this->assertEquals('2033-12-22', $lunar->getSolar()->toString());
  }

  public function test25()
  {
    $solar = Solar::fromYmdHms(2021, 6, 7, 21, 18, 0);
    $this->assertEquals('二〇二一年四月廿七', $solar->getLunar()->toString());
  }

  public function test26()
  {
    $lunar = Lunar::fromYmdHms(2021, 6, 7, 21, 18, 0);
    $this->assertEquals('2021-07-16', $lunar->getSolar()->toString());
  }

  public function test27()
  {
    $solar = Solar::fromYmd(1989, 4, 28);
    $this->assertEquals(23, $solar->getLunar()->getDay());
  }

  public function test28()
  {
    $solar = Solar::fromYmd(1990, 10, 8);
    $this->assertEquals('乙酉', $solar->getLunar()->getMonthInGanZhiExact());
  }

  public function test29()
  {
    $solar = Solar::fromYmd(1990, 10, 9);
    $this->assertEquals('丙戌', $solar->getLunar()->getMonthInGanZhiExact());
  }

  public function test30()
  {
    $solar = Solar::fromYmd(1990, 10, 8);
    $this->assertEquals('丙戌', $solar->getLunar()->getMonthInGanZhi());
  }

  public function test31()
  {
    $solar = Solar::fromYmdHms(1987, 4, 17, 9, 0, 0);
    $this->assertEquals('一九八七年三月二十', $solar->getLunar()->toString());
  }

  public function test32()
  {
    $lunar = Lunar::fromYmd(2034, 1, 1);
    $this->assertEquals('2034-02-19', $lunar->getSolar()->toString());
  }

  public function test33()
  {
    $lunar = Lunar::fromYmd(2033, 12, 1);
    $this->assertEquals('2034-01-20', $lunar->getSolar()->toString());
  }

  public function test34()
  {
    $lunar = Lunar::fromYmd(37, -12, 1);
    $this->assertEquals('闰腊', $lunar->getMonthInChinese());
  }

  public function test35()
  {
    $lunar = Lunar::fromYmd(56, -12, 1);
    $this->assertEquals('闰腊', $lunar->getMonthInChinese());

    $lunar = Lunar::fromYmd(75, -11, 1);
    $this->assertEquals('闰冬', $lunar->getMonthInChinese());

    $lunar = Lunar::fromYmd(94, -11, 1);
    $this->assertEquals('闰冬', $lunar->getMonthInChinese());

    $lunar = Lunar::fromYmd(94, 12, 1);
    $this->assertEquals('腊', $lunar->getMonthInChinese());

    $lunar = Lunar::fromYmd(113, 12, 1);
    $this->assertEquals('腊', $lunar->getMonthInChinese());

    $lunar = Lunar::fromYmd(113, -12, 1);
    $this->assertEquals('闰腊', $lunar->getMonthInChinese());

    $lunar = Lunar::fromYmd(5552, -12, 1);
    $this->assertEquals('闰腊', $lunar->getMonthInChinese());
  }

  public function test36()
  {
    $solar = Solar::fromYmd(5553, 1, 22);
    $this->assertEquals('五五五二年闰腊月初二', $solar->getLunar()->toString());
  }

  public function test37()
  {
    $solar = Solar::fromYmd(7013, 12, 24);
    $this->assertEquals('七〇一三年闰冬月初四', $solar->getLunar()->toString());
  }

  public function test38()
  {
    $lunar = Lunar::fromYmd(7013, -11, 4);
    $this->assertEquals('7013-12-24', $lunar->getSolar()->toString());
  }

  public function test41()
  {
    $solar = Solar::fromYmd(4, 2, 10);
    $this->assertEquals('鼠', $solar->getLunar()->getYearShengXiao());
  }

  public function test42()
  {
    $solar = Solar::fromYmd(4, 2, 9);
    $this->assertEquals('猪', $solar->getLunar()->getYearShengXiao());
  }

  public function test43()
  {
    $solar = Solar::fromYmd(1, 2, 12);
    $this->assertEquals('鸡', $solar->getLunar()->getYearShengXiao());
  }

  public function test44()
  {
    $solar = Solar::fromYmd(1, 1, 1);
    $this->assertEquals('猴', $solar->getLunar()->getYearShengXiao());
  }

  public function test45()
  {
    $solar = Solar::fromYmd(2017, 2, 15);
    $this->assertEquals('子命互禄 辛命进禄', $solar->getLunar()->getDayLu());
  }

  public function test46()
  {
    $solar = Solar::fromYmd(2017, 2, 16);
    $this->assertEquals('寅命互禄', $solar->getLunar()->getDayLu());
  }

  public function test48()
  {
    $solar = Solar::fromYmd(2021, 11, 13);
    $this->assertEquals('碓磨厕 外东南', $solar->getLunar()->getDayPositionTai());
  }

  public function test49()
  {
    $solar = Solar::fromYmd(2021, 11, 12);
    $this->assertEquals('占门碓 外东南', $solar->getLunar()->getDayPositionTai());
  }

  public function test50()
  {
    $solar = Solar::fromYmd(2021, 11, 13);
    $this->assertEquals('西南', $solar->getLunar()->getDayPositionFuDesc());
  }

  public function test51()
  {
    $solar = Solar::fromYmd(2021, 11, 12);
    $this->assertEquals('正北', $solar->getLunar()->getDayPositionFuDesc());
  }

  public function test52()
  {
    $solar = Solar::fromYmd(2011, 11, 12);
    $this->assertEquals('厕灶厨 外西南', $solar->getLunar()->getDayPositionTai());
  }

  public function test53()
  {
    $solar = Solar::fromYmd(2021, 12, 25);
    $this->assertEquals('二〇二一年冬月廿二', $solar->getLunar()->toString());
  }

  public function test54()
  {
    $solar = Solar::fromYmd(1722, 9, 25);
    $fs = array();
    $fs[] = '秋社';
    $this->assertEquals($fs, $solar->getLunar()->getOtherFestivals());
  }

  public function test55()
  {
    $solar = Solar::fromYmd(2022, 3, 16);
    $fs = array();
    $fs[] = '春社';
    $this->assertEquals($fs, $solar->getLunar()->getOtherFestivals());
  }

}
