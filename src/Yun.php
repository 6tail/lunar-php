<?php

namespace com\nlf\calendar;

use com\nlf\calendar\util\LunarUtil;

bcscale(12);

/**
 * 运
 * @package com\nlf\calendar
 */
class Yun
{

  /**
   * 性别，1男，0女
   * @var int
   */
  private $gender;

  /**
   * 起运年数
   * @var int
   */
  private $startYear;

  /**
   * 起运月数
   * @var int
   */
  private $startMonth;

  /**
   * 起运天数
   * @var int
   */
  private $startDay;

  /**
   * 起运小时数
   * @var int
   */
  private $startHour;

  /**
   * 是否顺推
   * @var bool
   */
  private $forward;

  /**
   * 阴历日期
   * @var Lunar
   */
  private $lunar;

  /**
   * 初始化
   * @param $eightChar EightChar 八字
   * @param $gender int 性别，1男，0女
   * @param int $sect 流派，1按天数和时辰数计算，3天1年，1天4个月，1时辰10天；2按分钟数计算
   */
  public function __construct($eightChar, $gender, $sect)
  {
    $this->lunar = $eightChar->getLunar();
    $this->gender = $gender;
    // 阳
    $yang = 0 == $this->lunar->getYearGanIndexExact() % 2;
    // 男
    $man = 1 == $gender;
    $this->forward = ($yang && $man) || (!$yang && !$man);
    $this->computeStart($sect);
  }

  private function computeStart($sect)
  {
    // 上节
    $prev = $this->lunar->getPrevJie();
    // 下节
    $next = $this->lunar->getNextJie();
    // 出生日期
    $current = $this->lunar->getSolar();
    // 阳男阴女顺推，阴男阳女逆推
    $start = $this->forward ? $current : $prev->getSolar();
    $end = $this->forward ? $next->getSolar() : $current;

    $hour = 0;

    if (2 == $sect) {
      $minutes = (int)(($end->getCalendar()->getTimestamp() - $start->getCalendar()->getTimestamp()) / 60);
      $year = (int)($minutes / 4320);
      $minutes -= $year * 4320;
      $month = (int)($minutes / 360);
      $minutes -= $month * 360;
      $day = (int)($minutes / 12);
      $minutes -= $day * 12;
      $hour = $minutes * 2;
    } else {
      $endTimeZhiIndex = ($end->getHour() == 23) ? 11 : LunarUtil::getTimeZhiIndex(substr($end->toYmdHms(), 11, 5));
      $startTimeZhiIndex = ($start->getHour() == 23) ? 11 : LunarUtil::getTimeZhiIndex(substr($start->toYmdHms(), 11, 5));
      // 时辰差
      $hourDiff = $endTimeZhiIndex - $startTimeZhiIndex;

      // 天数差
      $dayDiff = ExactDate::getDaysBetween($start->getYear(), $start->getMonth(), $start->getDay(), $end->getYear(), $end->getMonth(), $end->getDay());
      if ($hourDiff < 0) {
        $hourDiff += 12;
        $dayDiff--;
      }
      $monthDiff = (int)($hourDiff * 10 / 30);
      $month = $dayDiff * 4 + $monthDiff;
      $day = $hourDiff * 10 - $monthDiff * 30;
      $year = (int)($month / 12);
      $month = $month - $year * 12;
    }

    $this->startYear = $year;
    $this->startMonth = $month;
    $this->startDay = $day;
    $this->startHour = $hour;
  }

  /**
   * 获取性别
   * @return int
   */
  public function getGender()
  {
    return $this->gender;
  }

  /**
   * 获取起运年数
   * @return int
   */
  public function getStartYear()
  {
    return $this->startYear;
  }

  /**
   * 获取起运月数
   * @return int
   */
  public function getStartMonth()
  {
    return $this->startMonth;
  }

  /**
   * 获取起运天数
   * @return int
   */
  public function getStartDay()
  {
    return $this->startDay;
  }

  /**
   * 获取起运小时数
   * @return int
   */
  public function getStartHour()
  {
    return $this->startHour;
  }

  /**
   * 是否顺推
   * @return bool
   */
  public function isForward()
  {
    return $this->forward;
  }

  /**
   * 获取阴历日期
   * @return Lunar
   */
  public function getLunar()
  {
    return $this->lunar;
  }

  /**
   * 获取起运的阳历日期
   * @return Solar|null
   */
  public function getStartSolar()
  {
    $birth = $this->lunar->getSolar();
    $date = ExactDate::fromYmdHms($birth->getYear(), $birth->getMonth(), $birth->getDay(), $birth->getHour(), $birth->getMinute(), $birth->getSecond());
    $date->modify($this->startYear . ' year');
    $date->modify($this->startMonth . ' month');
    $date->modify($this->startDay . ' day');
    $date->modify($this->startHour . ' hour');
    return Solar::fromDate($date);
  }

  /**
   * 获取10轮大运
   * @return DaYun[]
   */
  public function getDaYun()
  {
    return $this->getDaYunBy(10);
  }

  /**
   * 获取大运
   * @param $n int 轮数
   * @return DaYun[]
   */
  public function getDaYunBy($n)
  {
    $l = array();
    for ($i = 0; $i < $n; $i++) {
      $l[] = new DaYun($this, $i);
    }
    return $l;
  }

}
