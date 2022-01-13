<?php

namespace com\nlf\calendar;

use com\nlf\calendar\util\SolarUtil;
use DateTime;
use DateTimeZone;
use RuntimeException;

/**
 * 精确日期
 * @package com\nlf\calendar
 */
class ExactDate
{

  public static $TIME_ZONE = 'Asia/Shanghai';

  public static function fromYmdHms($year, $month, $day, $hour, $minute, $second)
  {
    $calendar = DateTime::createFromFormat('Y-n-j', sprintf('%d-%d-%d', $year, $month, $day), new DateTimeZone(ExactDate::$TIME_ZONE));
    if (!$calendar) {
      throw new RuntimeException(sprintf('not support DateTime: %d-%02d-%02d %02d:%02d:%02d', $year, $month, $day, $hour, $minute, $second));
    }
    $calendar->setTime($hour, $minute, $second, 0);
    return $calendar;
  }

  public static function fromYmd($year, $month, $day)
  {
    return ExactDate::fromYmdHms($year, $month, $day, 0, 0, 0);
  }

  public static function fromDate(DateTime $date)
  {
    $calendar = DateTime::createFromFormat('Y-n-j G:i:s', $date->format('Y-n-j G:i:s'), $date->getTimezone());
    $calendar->setTimezone(new DateTimezone(ExactDate::$TIME_ZONE));
    $hour = intval($calendar -> format('G'));
    $minute = intval($calendar -> format('i'));
    $second = intval($calendar -> format('s'));
    $calendar->setTime($hour, $minute, $second, 0);
    return $calendar;
  }

  /**
   * 获取两个日期之间相差的天数（如果日期a比日期b小，天数为正，如果日期a比日期b大，天数为负）
   * @param $ay int 年a
   * @param $am int 月a
   * @param $ad int 日a
   * @param $by int 年b
   * @param $bm int 月b
   * @param $bd int 日b
   * @return int
   */
  public static function getDaysBetween($ay, $am, $ad, $by, $bm, $bd)
  {
    if ($ay == $by) {
      $n = SolarUtil::getDaysInYear($by, $bm, $bd) - SolarUtil::getDaysInYear($ay, $am, $ad);
    } else if ($ay > $by) {
      $days = SolarUtil::getDaysOfYear($by) - SolarUtil::getDaysInYear($by, $bm, $bd);
      for ($i = $by + 1; $i < $ay; $i++) {
        $days += SolarUtil::getDaysOfYear($i);
      }
      $days += SolarUtil::getDaysInYear($ay, $am, $ad);
      $n = -$days;
    } else {
      $days = SolarUtil::getDaysOfYear($ay) - SolarUtil::getDaysInYear($ay, $am, $ad);
      for ($i = $ay + 1; $i < $by; $i++) {
        $days += SolarUtil::getDaysOfYear($i);
      }
      $days += SolarUtil::getDaysInYear($by, $bm, $bd);
      $n = $days;
    }
    return $n;
  }

  /**
   * 获取两个日期之间相差的天数（如果日期a比日期b小，天数为正，如果日期a比日期b大，天数为负）
   * @param DateTime $date0 日期a
   * @param DateTime $date1 日期b
   * @return int 天数
   */
  public static function getDaysBetweenDate(DateTime $date0, DateTime $date1)
  {
    return ExactDate::getDaysBetween(intval($date0 -> format('Y')), intval($date0 -> format('n')), intval($date0 -> format('j')), intval($date1 -> format('Y')), intval($date1 -> format('n')), intval($date1 -> format('j')));
  }
}
