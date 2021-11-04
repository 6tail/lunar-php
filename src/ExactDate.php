<?php

namespace com\nlf\calendar;

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

}
