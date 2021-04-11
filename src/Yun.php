<?php

namespace com\nlf\calendar;

use com\nlf\calendar\util\LunarUtil;
use DateTime;

date_default_timezone_set('PRC');
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
   */
  public function __construct($eightChar, $gender)
  {
    $this->lunar = $eightChar->getLunar();
    $this->gender = $gender;
    // 阳
    $yang = 0 == $this->lunar->getYearGanIndexExact() % 2;
    // 男
    $man = 1 == $gender;
    $this->forward = ($yang && $man) || (!$yang && !$man);
    $this->computeStart();
  }

  private function computeStart()
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

    $endTimeZhiIndex = ($end->getHour() == 23) ? 11 : LunarUtil::getTimeZhiIndex(substr($end->toYmdHms(), 11, 5));
    $startTimeZhiIndex = ($start->getHour() == 23) ? 11 : LunarUtil::getTimeZhiIndex(substr($start->toYmdHms(), 11, 5));
    // 时辰差
    $hourDiff = $endTimeZhiIndex - $startTimeZhiIndex;

    $endCalendar = DateTime::createFromFormat('Y-n-j G:i:s',sprintf('%d-%d-%d 0:00:00', $end->getYear(), $end->getMonth(), $end->getDay()));
    $startCalendar = DateTime::createFromFormat('Y-n-j G:i:s',sprintf('%d-%d-%d 0:00:00', $start->getYear(), $start->getMonth(), $start->getDay()));

    // 天数差
    $dayDiff = $endCalendar->diff($startCalendar)->days;
    if ($hourDiff < 0) {
      $hourDiff += 12;
      $dayDiff--;
    }
    $monthDiff = (int)($hourDiff * 10 / 30);
    $month = $dayDiff * 4 + $monthDiff;
    $day = $hourDiff * 10 - $monthDiff * 30;
    $year = (int)($month / 12);
    $month = $month - $year * 12;
    $this->startYear = $year;
    $this->startMonth = $month;
    $this->startDay = $day;
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
    $date = DateTime::createFromFormat('Y-n-j G:i:s',sprintf('%d-%d-%d 0:00:00', $birth->getYear(), $birth->getMonth(), $birth->getDay()));
    $date->modify($this->startYear . ' year');
    $date->modify($this->startMonth . ' month');
    $date->modify($this->startDay . ' day');
    return Solar::fromDate($date);
  }

  /**
   * 获取大运
   * @return DaYun[]
   */
  public function getDaYun()
  {
    $n = 10;
    $l = array();
    for ($i = 0; $i < $n; $i++) {
      $l[] = new DaYun($this, $i);
    }
    return $l;
  }

}
