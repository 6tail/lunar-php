<?php

namespace com\nlf\calendar;

use com\nlf\calendar\util\HolidayUtil;
use com\nlf\calendar\util\LunarUtil;
use com\nlf\calendar\util\SolarUtil;
use DateTime;
use Exception;

bcscale(12);

/**
 * 阳历日期
 * @package com\nlf\calendar
 */
class Solar
{

  /**
   * 2000年儒略日数(2000-1-1 12:00:00 UTC)
   * @var int
   */
  public static $J2000 = 2451545;

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
   * 时
   * @var int
   */
  private $hour;

  /**
   * 分
   * @var int
   */
  private $minute;

  /**
   * 秒
   * @var int
   */
  private $second;

  /**
   * 日期
   * @var
   */
  private $calendar;

  function __construct($year, $month, $day, $hour, $minute, $second)
  {
    if ($year === 1582 && $month === 10) {
      if ($day >= 15) {
        $day -= 10;
      }
    }
    $this->year = $year;
    $this->month = $month;
    $this->day = $day;
    $this->hour = $hour;
    $this->minute = $minute;
    $this->second = $second;
    $this->calendar = ExactDate::fromYmdHms($year, $month, $day, $hour, $minute, $second);
  }

  public static function fromDate($date)
  {
    $calendar = ExactDate::fromDate($date);
    $year = intval($calendar -> format('Y'));
    $month = intval($calendar -> format('n'));
    $day = intval($calendar -> format('j'));
    $hour = intval($calendar -> format('G'));
    $minute = intval($calendar -> format('i'));
    $second = intval($calendar -> format('s'));
    return new Solar($year, $month, $day, $hour, $minute, $second);
  }

  public static function fromJulianDay($julianDay)
  {
    $d = (int)($julianDay + 0.5);
    $f = $julianDay + 0.5 - $d;

    if ($d >= 2299161) {
      $c = (int)(($d - 1867216.25) / 36524.25);
      $d += 1 + $c - (int)($c / 4);
    }
    $d += 1524;
    $year = (int)(($d - 122.1) / 365.25);
    $d -= (int)(365.25 * $year);
    $month = (int)($d / 30.601);
    $d -= (int)(30.601 * $month);
    $day = $d;
    if ($month > 13) {
      $month -= 13;
      $year -= 4715;
    } else {
      $month -= 1;
      $year -= 4716;
    }
    $f *= 24;
    $hour = (int)$f;

    $f -= $hour;
    $f *= 60;
    $minute = (int)$f;

    $f -= $minute;
    $f *= 60;
    $second = intval(round($f));

    if ($second > 59) {
      $second -= 60;
      $minute++;
    }
    if ($minute > 59) {
      $minute -= 60;
      $hour++;
    }

    return Solar::fromYmdHms($year, $month, $day, $hour, $minute, $second);
  }

  /**
   * 通过八字获取阳历列表（晚子时日柱按当天，起始年为1900）
   * @param string $yearGanZhi 年柱
   * @param string $monthGanZhi 月柱
   * @param string $dayGanZhi 日柱
   * @param string $timeGanZhi 时柱
   * @return Solar[] 符合的阳历列表
   * @throws Exception
   */
  public static function fromBaZi($yearGanZhi, $monthGanZhi, $dayGanZhi, $timeGanZhi)
  {
    return self::fromBaZiBySect($yearGanZhi, $monthGanZhi, $dayGanZhi, $timeGanZhi, 2);
  }

  /**
   * 通过八字获取阳历列表（起始年为1900）
   * @param string $yearGanZhi 年柱
   * @param string $monthGanZhi 月柱
   * @param string $dayGanZhi 日柱
   * @param string $timeGanZhi 时柱
   * @param int sect 流派，2晚子时日柱按当天，1晚子时日柱按明天
   * @return Solar[] 符合的阳历列表
   * @throws Exception
   */
  public static function fromBaZiBySect($yearGanZhi, $monthGanZhi, $dayGanZhi, $timeGanZhi, $sect)
  {
    return self::fromBaZiBySectAndBaseYear($yearGanZhi, $monthGanZhi, $dayGanZhi, $timeGanZhi, $sect, 1900);
  }

  /**
   * 通过八字获取阳历列表
   * @param string $yearGanZhi 年柱
   * @param string $monthGanZhi 月柱
   * @param string $dayGanZhi 日柱
   * @param string $timeGanZhi 时柱
   * @param int sect 流派，2晚子时日柱按当天，1晚子时日柱按明天
   * @param int $baseYear 起始年
   * @return Solar[]
   * @throws Exception
   */
  public static function fromBaZiBySectAndBaseYear($yearGanZhi, $monthGanZhi, $dayGanZhi, $timeGanZhi, $sect, $baseYear)
  {
    $sect = (1 == $sect) ? 1 : 2;
    $l = array();
    $today = Solar::fromDate(new DateTime());
    $lunar = $today->getLunar();
    $offsetYear = LunarUtil::getJiaZiIndex($lunar->getYearInGanZhiExact()) - LunarUtil::getJiaZiIndex($yearGanZhi);
    if ($offsetYear < 0) {
      $offsetYear = $offsetYear + 60;
    }
    $startYear = $lunar->getYear() - $offsetYear;
    $hour = 0;
    $timeZhi = substr($timeGanZhi, strlen($timeGanZhi) / 2);
    for ($i = 0, $j = count(LunarUtil::$ZHI); $i < $j; $i++) {
      if (strcmp(LunarUtil::$ZHI[$i], $timeZhi) === 0) {
        $hour = ($i - 1) * 2;
      }
    }
    while ($startYear >= $baseYear) {
      $year = $startYear - 1;
      $counter = 0;
      $month = 12;
      $found = false;
      while ($counter < 15) {
        if ($year >= $baseYear) {
          $day = 1;
          $solar = Solar::fromYmdHms($year, $month, $day, $hour, 0, 0);
          $lunar = $solar->getLunar();
          if (strcmp($lunar->getYearInGanZhiExact(), $yearGanZhi) == 0 && strcmp($lunar->getMonthInGanZhiExact(), $monthGanZhi) == 0) {
            $found = true;
            break;
          }
        }
        $month++;
        if ($month > 12) {
          $month = 1;
          $year++;
        }
        $counter++;
      }
      if ($found) {
        $counter = 0;
        $month--;
        if ($month < 1) {
          $month = 12;
          $year--;
        }
        $day = 1;
        $solar = Solar::fromYmdHms($year, $month, $day, $hour, 0, 0);
        while ($counter < 61) {
          $lunar = $solar->getLunar();
          $dgz = (2 == $sect) ? $lunar->getDayInGanZhiExact2() : $lunar->getDayInGanZhiExact();
          if (strcmp($lunar->getYearInGanZhiExact(), $yearGanZhi) == 0 && strcmp($lunar->getMonthInGanZhiExact(), $monthGanZhi) == 0 && strcmp($dgz, $dayGanZhi) == 0 && strcmp($lunar->getTimeInGanZhi(), $timeGanZhi) == 0) {
            $l[] = $solar;
            break;
          }
          $solar = $solar->next(1);
          $counter++;
        }
      }
      $startYear -= 60;
    }
    return $l;
  }

  public static function fromYmd($year, $month, $day)
  {
    return new Solar($year, $month, $day, 0, 0, 0);
  }

  public static function fromYmdHms($year, $month, $day, $hour, $minute, $second)
  {
    return new Solar($year, $month, $day, $hour, $minute, $second);
  }

  /**
   * @return string
   */
  public function toYmd()
  {
    $d = $this->day;
    if ($this->year === 1582 && $this->month === 10) {
      if ($d >= 5) {
        $d += 10;
      }
    }
    return sprintf('%04d-%02d-%02d', $this->year, $this->month, $d);
  }

  public function toYmdHms()
  {
    return $this->toYmd() . ' ' . sprintf('%02d:%02d:%02d', $this->hour, $this->minute, $this->second);
  }

  public function toFullString()
  {
    $s = $this->toYmdHms();
    if ($this->isLeapYear()) {
      $s .= ' 闰年';
    }
    $s .= ' 星期' . $this->getWeekInChinese();
    foreach ($this->getFestivals() as $f) {
      $s .= ' (' . $f . ')';
    }
    $s .= ' ' . $this->getXingZuo() . '座';
    return $s;
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

  public function getHour()
  {
    return $this->hour;
  }

  public function getMinute()
  {
    return $this->minute;
  }

  public function getSecond()
  {
    return $this->second;
  }

  public function getJulianDay()
  {
    $y = $this->year;
    $m = $this->month;
    $d = $this->day + (($this->second / 60 + $this->minute) / 60 + $this->hour) / 24;
    $n = 0;
    $g = false;
    if ($y * 372 + $m * 31 + (int)$d >= 588829) {
      $g = true;
    }
    if ($m <= 2) {
      $m += 12;
      $y--;
    }
    if ($g) {
      $n = (int)($y / 100);
      $n = 2 - $n + (int)($n / 4);
    }
    return (int)(365.25 * ($y + 4716)) + (int)(30.6001 * ($m + 1)) + $d + $n - 1524.5;
  }

  public function getLunar()
  {
    return Lunar::fromDate($this->calendar);
  }

  public function getCalendar()
  {
    return $this->calendar;
  }

  public function toString()
  {
    return $this->toYmd();
  }

  public function __toString()
  {
    return $this->toString();
  }

  public function isLeapYear()
  {
    return SolarUtil::isLeapYear($this->year);
  }

  public function getWeek()
  {
    return intval($this->calendar->format('w'));
  }

  public function getWeekInChinese()
  {
    return SolarUtil::$WEEK[$this->getWeek()];
  }

  public function getXingZuo()
  {
    $index = 11;
    $y = $this->month * 100 + $this->day;
    if ($y >= 321 && $y <= 419) {
      $index = 0;
    } else if ($y >= 420 && $y <= 520) {
      $index = 1;
    } else if ($y >= 521 && $y <= 621) {
      $index = 2;
    } else if ($y >= 622 && $y <= 722) {
      $index = 3;
    } else if ($y >= 723 && $y <= 822) {
      $index = 4;
    } else if ($y >= 823 && $y <= 922) {
      $index = 5;
    } else if ($y >= 923 && $y <= 1023) {
      $index = 6;
    } else if ($y >= 1024 && $y <= 1122) {
      $index = 7;
    } else if ($y >= 1123 && $y <= 1221) {
      $index = 8;
    } else if ($y >= 1222 || $y <= 119) {
      $index = 9;
    } else if ($y <= 218) {
      $index = 10;
    }
    return SolarUtil::$XING_ZUO[$index];
  }

  public function getFestivals()
  {
    $l = array();
    $key = $this->month . '-' . $this->day;
    if (!empty(SolarUtil::$FESTIVAL[$key])) {
      $l[] = SolarUtil::$FESTIVAL[$key];
    }
    $weeks = intval(ceil($this->day / 7.0));
    $week = $this->getWeek();
    $key = $this->month . '-' . $weeks . '-' . $week;
    if (!empty(SolarUtil::$WEEK_FESTIVAL[$key])) {
      $l[] = SolarUtil::$WEEK_FESTIVAL[$key];
    }
    if ($this->day + 7 > SolarUtil::getDaysOfMonth($this->year, $this->month)) {
      $key = $this->month . '-0-' . $week;
      if (!empty(SolarUtil::$WEEK_FESTIVAL[$key])) {
        $l[] = SolarUtil::$WEEK_FESTIVAL[$key];
      }
    }
    return $l;
  }

  public function getOtherFestivals()
  {
    $l = array();
    $key = $this->month . '-' . $this->day;
    if (!empty(SolarUtil::$OTHER_FESTIVAL[$key])) {
      foreach (SolarUtil::$OTHER_FESTIVAL[$key] as $f) {
        $l[] = $f;
      }
    }
    return $l;
  }

  /**
   * 获取往后推几天的阳历日期，如果要往前推，则天数用负数
   * @param int $days 天数
   * @return Solar|null
   */
  public function next($days)
  {
    if ($days == 0) {
      return Solar::fromYmdHms($this->year, $this->month, $this->day, $this->hour, $this->minute, $this->second);
    }
    $calendar = ExactDate::fromYmdHms($this->year, $this->month, $this->day, $this->hour, $this->minute, $this->second);
    $calendar->modify(($days > 0 ? '+' : '') . $days . ' day');
    return Solar::fromDate($calendar);
  }

  /**
   * 获取往后推几个工作日的阳历日期，如果要往前推，则天数用负数
   * @param int $days 天数
   * @return Solar|null
   */
  public function nextWorkday($days)
  {
    if ($days == 0) {
      return Solar::fromYmdHms($this->year, $this->month, $this->day, $this->hour, $this->minute, $this->second);
    }
    $calendar = ExactDate::fromYmdHms($this->year, $this->month, $this->day, $this->hour, $this->minute, $this->second);
    $rest = abs($days);
    $add = $days < 1 ? -1 : 1;
    while ($rest > 0) {
      $calendar->modify(($add > 0 ? '+' : '') . $add . ' day');
      $work = true;
      $year = intval($calendar -> format('Y'));
      $month = intval($calendar -> format('n'));
      $day = intval($calendar -> format('j'));
      $holiday = HolidayUtil::getHolidayByYmd($year, $month, $day);
      if (null == $holiday) {
        $week = intval($calendar->format('w'));
        if (0 === $week || 6 === $week) {
          $work = false;
        }
      } else {
        $work = $holiday->isWork();
      }
      if ($work) {
        $rest -= 1;
      }
    }
    return Solar::fromDate($calendar);
  }

}
