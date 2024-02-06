<?php

namespace com\nlf\calendar;

use com\nlf\calendar\util\SolarUtil;
use DateTime;

bcscale(12);

/**
 * 公历周
 * @package com\nlf\calendar
 */
class SolarWeek
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
   * 日
   * @var int
   */
  private $day;

  /**
   * 星期几作为一周的开始，1234560分别代表星期一至星期天
   * @var int
   */
  private $start;

  function __construct($year, $month, $day, $start)
  {
    $this->year = intval($year);
    $this->month = intval($month);
    $this->day = intval($day);
    $this->start = intval($start);
  }

  public function toString()
  {
    return $this->year . '.' . $this->month . '.' . $this->getIndex();
  }

  public function __toString()
  {
    return $this->toString();
  }

  public function toFullString()
  {
    return $this->year . '年' . $this->month . '月第' . $this->getIndex() . '周';
  }

  /**
   * 通过指定年月日获取公历周
   * @param int $year 年
   * @param int $month 月，1到12
   * @param int $day 日，1到31
   * @param int $start 星期几作为一周的开始，1234560分别代表星期一至星期天
   * @return SolarWeek
   */
  public static function fromYmd($year, $month, $day, $start)
  {
    return new SolarWeek($year, $month, $day, $start);
  }

  /**
   * 通过指定DateTime获取公历周
   * @param DateTime $date DateTime
   * @param int $start 星期几作为一周的开始，1234560分别代表星期一至星期天
   * @return SolarWeek
   */
  public static function fromDate($date, $start)
  {
    $solar = Solar::fromDate($date);
    return new SolarWeek($solar->getYear(), $solar->getMonth(), $solar->getDay(), $start);
  }

  public function getYear()
  {
    return $this->year;
  }

  public function getMonth()
  {
    return $this->month;
  }

  public function getDay()
  {
    return $this->day;
  }

  public function getStart()
  {
    return $this->start;
  }

  /**
   * 获取当前日期是在当月第几周
   * @return int
   */
  public function getIndex()
  {
    $offset = Solar::fromYmd($this->year, $this->month, 1)->getWeek() - $this->start;
    if ($offset < 0) {
      $offset += 7;
    }
    return (int)ceil(($this->day + $offset) / 7);
  }

  /**
   * 获取当前日期是在当年第几周
   * @return int
   */
  public function getIndexInYear()
  {
    $offset = Solar::fromYmd($this->year, 1, 1)->getWeek() - $this->start;
    if ($offset < 0) {
      $offset += 7;
    }
    return (int)ceil((SolarUtil::getDaysInYear($this->year, $this->month, $this->day) + $offset) / 7);
  }

  /**
   * 周推移
   * @param int $weeks 推移的周数，负数为倒推
   * @param bool $separateMonth 是否按月单独计算
   * @return SolarWeek
   */
  public function next($weeks, $separateMonth)
  {
    if (0 === $weeks) {
      return SolarWeek::fromYmd($this->year, $this->month, $this->day, $this->start);
    }
    $solar = Solar::fromYmd($this->year, $this->month, $this->day);
    if ($separateMonth) {
      $n = $weeks;
      $week = SolarWeek::fromYmd($solar->getYear(), $solar->getMonth(), $solar->getDay(), $this->start);
      $month = $this->month;
      $plus = $n > 0;
      while (0 !== $n) {
        $solar = $solar->next($plus ? 7 : -7);
        $week = SolarWeek::fromYmd($solar->getYear(), $solar->getMonth(), $solar->getDay(), $this->start);
        $weekMonth = $week->getMonth();
        if ($month !== $weekMonth) {
          $index = $week->getIndex();
          if ($plus) {
            if (1 === $index) {
              $firstDay = $week->getFirstDay();
              $week = SolarWeek::fromYmd($firstDay->getYear(), $firstDay->getMonth(), $firstDay->getDay(), $this->start);
              $weekMonth = $week->getMonth();
            } else {
              $solar = Solar::fromYmd($week->getYear(), $week->getMonth(), 1);
              $week = SolarWeek::fromYmd($solar->getYear(), $solar->getMonth(), $solar->getDay(), $this->start);
            }
          } else {
            if (SolarUtil::getWeeksOfMonth($week->getYear(), $week->getMonth(), $week->getStart()) === $index) {
              $lastDay = $week->getFirstDay()->next(6);
              $week = SolarWeek::fromYmd($lastDay->getYear(), $lastDay->getMonth(), $lastDay->getDay(), $this->start);
              $weekMonth = $week->getMonth();
            } else {
              $solar = Solar::fromYmd($week->year, $week->month, SolarUtil::getDaysOfMonth($week->getYear(), $week->getMonth()));
              $week = SolarWeek::fromYmd($solar->getYear(), $solar->getMonth(), $solar->getDay(), $this->start);
            }
          }
          $month = $weekMonth;
        }
        $n -= $plus ? 1 : -1;
      }
      return $week;
    } else {
      $solar = $solar->next($weeks * 7);
      return SolarWeek::fromYmd($solar->getYear(), $solar->getMonth(), $solar->getDay(), $this->start);
    }
  }

  /**
   * 获取本周第一天的公历日期（可能跨月）
   * @return Solar
   */
  public function getFirstDay()
  {
    $solar = Solar::fromYmd($this->year, $this->month, $this->day);
    $prev = $solar->getWeek() - $this->start;
    if ($prev < 0) {
      $prev += 7;
    }
    return $solar->next(-$prev);
  }

  /**
   * 获取本周第一天的公历日期（仅限当月）
   * @return Solar
   */
  public function getFirstDayInMonth()
  {
    $days = $this->getDays();
    foreach ($days as $day) {
      if ($this->month === $day->getMonth()) {
        return $day;
      }
    }
    return null;
  }

  /**
   * 获取本周的公历日期列表（可能跨月）
   * @return Solar[]
   */
  public function getDays()
  {
    $firstDay = $this->getFirstDay();
    $l = array();
    if (null == $firstDay) {
      return $l;
    }
    $l[] = $firstDay;
    for ($i = 1; $i < 7; $i++) {
      $l[] = $firstDay->next($i);
    }
    return $l;
  }

  /**
   * 获取本周的公历日期列表（仅限当月）
   * @return Solar[]
   */
  public function getDaysInMonth()
  {
    $days = $this->getDays();
    $l = array();
    foreach ($days as $day) {
      if ($this->month !== $day->getMonth()) {
        continue;
      }
      $l[] = $day;
    }
    return $l;
  }

}
