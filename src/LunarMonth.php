<?php

namespace com\nlf\calendar;

/**
 * 农历月
 * @package com\nlf\calendar
 */
class LunarMonth
{

  /**
   * 农历年
   * @var int
   */
  private $year;

  /**
   * 农历月：1-12，闰月为负数，如闰2月为-2
   * @var int
   */
  private $month;

  /**
   * 天数，大月30天，小月29天
   * @var int
   */
  private $dayCount;

  /**
   * 初一的儒略日
   * @var double
   */
  private $firstJulianDay;

  function __construct($lunarYear, $lunarMonth, $dayCount, $firstJulianDay)
  {
    $this->year = $lunarYear;
    $this->month = $lunarMonth;
    $this->dayCount = $dayCount;
    $this->firstJulianDay = $firstJulianDay;
  }

  public function toString()
  {
    return $this->year . '.' . $this->month;
  }

  public function toFullString()
  {
    return $this->year . '年' . ($this->isLeap() ? '闰' : '') . abs($this->month) . '月(' . $this->dayCount . '天)';
  }

  public function __toString()
  {
    return $this->toString();
  }

  /**
   * 通过农历年月初始化
   * @param int $lunarYear 农历年
   * @param int $lunarMonth 农历月：1-12，闰月为负数，如闰2月为-2
   * @return LunarMonth
   */
  public static function fromYm($lunarYear, $lunarMonth)
  {
    return LunarYear::fromYear($lunarYear)->getMonth($lunarMonth);
  }

  /**
   * 获取农历年
   * @return int 获取农历年
   */
  public function getYear()
  {
    return $this->year;
  }

  /**
   * 获取农历月
   * @return int 农历月：1-12，闰月为负数，如闰2月为-2
   */
  public function getMonth()
  {
    return $this->month;
  }

  /**
   * 获取天数
   * @return int 天数
   */
  public function getDayCount()
  {
    return $this->dayCount;
  }

  /**
   * 获取初一的儒略日
   * @return double 获取初一的儒略日
   */
  public function getFirstJulianDay()
  {
    return $this->firstJulianDay;
  }

  /**
   * 是否闰月
   * @return bool true/false
   */
  public function isLeap()
  {
    return $this->month < 0;
  }
}
