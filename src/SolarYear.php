<?php

namespace com\nlf\calendar;

use DateTime;


/**
 * 阳历年
 * @package com\nlf\calendar
 */
class SolarYear
{
  /**
   * 年
   * @var int
   */
  private $year;

  /**
   * 一年的月数
   * @var int
   */
  public static $MONTH_COUNT = 12;

  function __construct($year)
  {
    $this->year = $year;
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

  /**
   * 通过指定年获取阳历年
   * @param int $year 年
   * @return SolarYear
   */
  public static function fromYear($year)
  {
    return new SolarYear($year);
  }

  /**
   * 通过指定日期获取阳历年
   * @param DateTime $date 日期DateTime
   * @return SolarYear
   */
  public static function fromDate($date)
  {
    $calendar = ExactDate::fromDate($date);
    $year = intval(date_format($calendar, 'Y'));
    return new SolarYear($year);
  }

  public function getYear()
  {
    return $this->year;
  }

  /**
   * 获取本年的月份
   * @return SolarMonth[]
   */
  public function getMonths()
  {
    $l = array();
    $month = SolarMonth::fromYm($this->year, 1);
    $l[] = $month;
    for ($i = 1; $i < SolarYear::$MONTH_COUNT; $i++) {
      $l[] = $month->next($i);
    }
    return $l;
  }

  /**
   * 年推移
   * @param int $years 推移的年数，负数为倒推
   * @return SolarYear|null
   */
  public function next($years)
  {
    if (0 === $years) {
      return new SolarYear($this->year);
    }
    $date = ExactDate::fromYmd($this->year, 1, 1);
    $date->modify($years . ' year');
    return SolarYear::fromDate($date);
  }

}
