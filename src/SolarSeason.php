<?php

namespace com\nlf\calendar;

use DateTime;

bcscale(12);

/**
 * 阳历季度
 * @package com\nlf\calendar
 */
class SolarSeason
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
   * 一个季度的月数
   * @var int
   */
  public static $MONTH_COUNT = 3;

  function __construct($year, $month)
  {
    $this->year = $year;
    $this->month = $month;
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
    return $this->year . '年' . $this->getIndex() . '季度';
  }

  /**
   * 通过指定年月获取阳历季度
   * @param int $year 年
   * @param int $month 月，1到12
   * @return SolarSeason
   */
  public static function fromYm($year, $month)
  {
    return new SolarSeason($year, $month);
  }

  /**
   * 通过指定日期获取阳历季度
   * @param DateTime $date 日期DateTime
   * @return SolarSeason
   */
  public static function fromDate($date)
  {
    $calendar = ExactDate::fromDate($date);
    $year = intval(date_format($calendar, 'Y'));
    $month = intval(date_format($calendar, 'n'));
    return new SolarSeason($year, $month);
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
   * 获取当月是第几季度，从1开始
   * @return int
   */
  public function getIndex()
  {
    return (int)ceil($this->month / SolarSeason::$MONTH_COUNT);
  }

  /**
   * 获取本季度的月份
   * @return SolarMonth[]
   */
  public function getMonths()
  {
    $l = array();
    $index = $this->getIndex() - 1;
    for ($i = 0; $i < SolarSeason::$MONTH_COUNT; $i++) {
      $l[] = new SolarMonth($this->year, SolarSeason::$MONTH_COUNT * $index + $i + 1);
    }
    return $l;
  }

  /**
   * 季度推移
   * @param int $seasons 推移的季度数，负数为倒推
   * @return SolarSeason|null
   */
  public function next($seasons)
  {
    if (0 === $seasons) {
      return new SolarSeason($this->year, $this->month);
    }
    $date = ExactDate::fromYmd($this->year, $this->month, 1);
    $date->modify((SolarSeason::$MONTH_COUNT * $seasons) . ' month');
    return SolarSeason::fromDate($date);
  }

}
