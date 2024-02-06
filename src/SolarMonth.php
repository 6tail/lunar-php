<?php

namespace com\nlf\calendar;

use com\nlf\calendar\util\SolarUtil;
use DateTime;


/**
 * 公历月
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
    $this->year = intval($year);
    $this->month = intval($month);
  }

  public function toString()
  {
    return $this->year . '-' . $this->month;
  }

  public function __toString()
  {
    return $this->toString();
  }

  /**
   * @return string
   */
  public function toFullString()
  {
    return $this->year . '年' . $this->month . '月';
  }

  /**
   * 通过指定年月获取公历月
   * @param int $year 年
   * @param int $month 月，1到12
   * @return SolarMonth
   */
  public static function fromYm($year, $month)
  {
    return new SolarMonth($year, $month);
  }

  /**
   * 通过DateTime获取公历月
   * @param DateTime $date DateTime
   * @return SolarMonth
   */
  public static function fromDate($date)
  {
    $solar = Solar::fromDate($date);
    return new SolarMonth($solar->getYear(), $solar->getMonth());
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
   * 获取本月的公历日期列表
   * @return Solar[]
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
   * 获取本月的公历周列表
   * @param int $start 星期几作为一周的开始，1234560分别代表星期一至星期天
   * @return SolarWeek[] 周列表
   */
  public function getWeeks($start)
  {
    $l = array();
    $week = SolarWeek::fromYmd($this->year, $this->month, 1, $start);
    while (true) {
      $l[] = $week;
      $week = $week->next(1, false);
      $firstDay = $week->getFirstDay();
      if ($firstDay->getYear() > $this->year || $firstDay->getMonth() > $this->month) {
        break;
      }
    }
    return $l;
  }

  /**
   * 获取往后推几个月的公历月，如果要往前推，则月数用负数
   * @param int $months 月数
   * @return SolarMonth
   */
  public function next($months)
  {
    $n = $months < 0 ? -1 : 1;
    $m = abs($months);
    $y = $this->year + (int)($m / 12) * $n;
    $m = $this->month + $m % 12 * $n;
    if ($m > 12) {
      $m -= 12;
      $y++;
    } else if ($m < 1) {
      $m += 12;
      $y--;
    }
    return new SolarMonth($y, $m);
  }

}
