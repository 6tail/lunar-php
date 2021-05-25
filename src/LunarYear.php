<?php

namespace com\nlf\calendar;

use com\nlf\calendar\util\ShouXingUtil;

date_default_timezone_set('PRC');
bcscale(12);

/**
 * 农历年
 * @package com\nlf\calendar
 */
class LunarYear
{
  /**
   * 年
   * @var int
   */
  private $year;

  /**
   * 农历月们
   * @var LunarMonth[]
   */
  private $months = array();

  /**
   * 节气儒略日们
   * @var double[]
   */
  private $jieQiJulianDays = array();

  function __construct($lunarYear)
  {
    $this->year = $lunarYear;
    $this->compute();
  }

  /**
   * 通过农历年初始化
   * @param int $lunarYear 农年
   * @return LunarYear
   */
  public static function fromYear($lunarYear)
  {
    return new LunarYear($lunarYear);
  }

  public function toString()
  {
    return $this->year . '';
  }

  public function __toString()
  {
    return $this->toString();
  }

  public function toFullString()
  {
    return $this->year . '年';
  }

  public function getYear()
  {
    return $this->year;
  }

  /**
   * @return double[]
   */
  public function getJieQiJulianDays()
  {
    return $this->jieQiJulianDays;
  }

  /**
   * 获取本年的月份
   * @return LunarMonth[]
   */
  public function getMonths()
  {
    return $this->months;
  }

  /**
   * 获取农历月
   * @param int $lunarMonth 月，1-12，闰月为负数，如闰2月为-2
   * @return LunarMonth|null
   */
  public function getMonth($lunarMonth)
  {
    foreach ($this->months as $m) {
      if ($m->getYear() == $this->year && $m->getMonth() == $lunarMonth) {
        return $m;
      }
    }
    return null;
  }

  /**
   * 获取闰月
   * @return int 闰月数字，1代表闰1月，0代表无闰月
   */
  public function getLeapMonth()
  {
    foreach ($this->months as $m) {
      if ($m->getYear() == $this->year && $m->isLeap()) {
        return abs($m->getMonth());
      }
    }
    return 0;
  }

  private function compute()
  {
    // 节气(中午12点，长度25)
    $jq = array();
    // 合朔，即每月初一(中午12点，长度16)
    $hs = array();
    // 每月天数(长度15)
    $dayCounts = array();

    $year = $this->year - 2000;
    // 从上年的大雪到下年的立春
    for ($i = 0, $j = count(Lunar::$JIE_QI_IN_USE); $i < $j; $i++) {
      // 精确的节气
      $t = 36525 * ShouXingUtil::saLonT(($year + (17 + $i) * 15.0 / 360) * 2 * M_PI);
      $t += ShouXingUtil::$ONE_THIRD - ShouXingUtil::dtT($t);
      $this->jieQiJulianDays[] = $t + Solar::$J2000;
      // 按中午12点算的节气
      if ($i > 0 && $i < 26) {
        $jq[] = round($t);
      }
    }

    //冬至前的初一
    $w = ShouXingUtil::calcShuo($jq[0]);
    if ($w > $jq[0]) {
      $w -= 29.5306;
    }
    // 递推每月初一
    for ($i = 0; $i < 16; $i++) {
      $hs[] = ShouXingUtil::calcShuo($w + 29.5306 * $i);
    }
    // 每月天数
    for ($i = 0; $i < 15; $i++) {
      $dayCounts[] = (int)($hs[$i + 1] - $hs[$i]);
    }
    $leap = -1;
    if ($hs[13] <= $jq[24]) {
      $i = 1;
      while ($hs[$i + 1] > $jq[2 * $i] && $i < 13) {
        $i++;
      }
      $leap = $i;
    }

    $y = $this->year - 1;
    $m = 11;
    for ($i = 0; $i < count($dayCounts); $i++) {
      $isLeap = false;
      if ($i == $leap) {
        $isLeap = true;
        $m--;
      }
      $this->months[] = new LunarMonth($y, $isLeap ? -$m : $m, $dayCounts[$i], $hs[$i] + Solar::$J2000);
      $m++;
      if ($m == 13) {
        $m = 1;
        $y++;
      }
    }
  }

}
