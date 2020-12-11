<?php

namespace com\nlf\calendar;

use com\nlf\calendar\util\SolarUtil;
use DateTime;
use Exception;

date_default_timezone_set('PRC');
bcscale(12);

/**
 * 阳历月
 * @package com\nlf\calendar
 */
class SolarMonth
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

  function __construct($year, $month)
  {
    $this->year = $year;
    $this->month = $month;
  }

  public function toString()
  {
    return $this->year . '-' . $this->month;
  }

  public function __toString()
  {
    return $this->toString();
  }

  public function toFullString()
  {
    return $this->year . '年' . $this->month . '月';
  }

  /**
   * 通过指定年月获取阳历月
   * @param int $year 年
   * @param int $month 月，1到12
   * @return SolarMonth
   */
  public static function fromYm($year, $month)
  {
    return new SolarMonth($year, $month);
  }

  /**
   * 通过指定日期获取阳历月
   * @param DateTime $date 日期DateTime
   * @return SolarMonth
   */
  public static function fromDate($date)
  {
    $year = (int)date_format($date, 'Y');
    $month = (int)date_format($date, 'n');
    return new SolarMonth($year, $month);
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
   * 获取本月的阳历日期列表
   * @return array
   */
  public function getDays()
  {
    $l = array();
    $d = Solar::fromYmd($this->year, $this->month, 1);
    $l[] = $d;
    $days = SolarUtil::getDaysOfMonth($this->year, $this->month);
    for ($i = 1; $i < $days; $i++) {
      $l[] = $d->next($i);
    }
    return $l;
  }

  /**
   * 获取往后推几个月的阳历月，如果要往前推，则月数用负数
   * @param int $months 月数
   * @return SolarMonth|null
   */
  public function next($months)
  {
    try {
      $date = new DateTime($this->year . '-' . $this->month . '-1');
    } catch (Exception $e) {
      return null;
    }
    $date->modify($months . ' month');
    return SolarMonth::fromDate($date);
  }

}
