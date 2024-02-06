<?php

namespace com\nlf\calendar;

use DateTime;

bcscale(12);

/**
 * 公历半年
 * @package com\nlf\calendar
 */
class SolarHalfYear
{
  /**
   * 年
   * @var int
   */
  private $year;

  /**
   * 月
   * @var int
   */
  private $month;

  /**
   * 一个半年的月数
   * @var int
   */
  public static $MONTH_COUNT = 6;

  function __construct($year, $month)
  {
    $this->year = intval($year);
    $this->month = intval($month);
  }

  public function toString()
  {
    return $this->year . '.' . $this->getIndex();
  }

  public function __toString()
  {
    return $this->toString();
  }

  public function toFullString()
  {
    return $this->year . '年' . (1 === $this->getIndex() ? '上' : '下') . '半年';
  }

  /**
   * 通过指定年月获取公历半年
   * @param int $year 年
   * @param int $month 月，1到12
   * @return SolarHalfYear
   */
  public static function fromYm($year, $month)
  {
    return new SolarHalfYear($year, $month);
  }

  /**
   * 通过指定DateTime获取公历半年
   * @param DateTime $date DateTime
   * @return SolarHalfYear
   */
  public static function fromDate($date)
  {
    $solar = Solar::fromDate($date);
    return new SolarHalfYear($solar->getYear(), $solar->getMonth());
  }

  public function getYear()
  {
    return $this->year;
  }

  public function getMonth()
  {
    return $this->month;
  }

  /**
   * 获取当月是第几半年，从1开始
   * @return int
   */
  public function getIndex()
  {
    return (int)ceil($this->month / SolarHalfYear::$MONTH_COUNT);
  }

  /**
   * 获取本半年的月份
   * @return SolarMonth[]
   */
  public function getMonths()
  {
    $l = array();
    $index = $this->getIndex() - 1;
    for ($i = 0; $i < SolarHalfYear::$MONTH_COUNT; $i++) {
      $l[] = new SolarMonth($this->year, SolarHalfYear::$MONTH_COUNT * $index + $i + 1);
    }
    return $l;
  }

  /**
   * 半年推移
   * @param int $halfYears 推移的半年数，负数为倒推
   * @return SolarHalfYear
   */
  public function next($halfYears)
  {
    $month = SolarMonth::fromYm($this->year, $this->month)->next(self::$MONTH_COUNT * $halfYears);
    return new SolarHalfYear($month->getYear(), $month->getMonth());
  }

}
