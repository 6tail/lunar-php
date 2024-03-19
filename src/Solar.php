<?php

namespace com\nlf\calendar;

use com\nlf\calendar\util\HolidayUtil;
use com\nlf\calendar\util\LunarUtil;
use com\nlf\calendar\util\SolarUtil;
use DateTime;
use DateTimeZone;
use RuntimeException;

bcscale(12);

/**
 * 公历日期
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

  function __construct($year, $month, $day, $hour, $minute, $second)
  {
    $year = intval($year);
    $month = intval($month);
    $day = intval($day);
    $hour = intval($hour);
    $minute = intval($minute);
    $second = intval($second);

    if (1582 == $year && 10 == $month) {
      if ($day > 4 && $day < 15) {
        throw new RuntimeException(sprintf('wrong solar year %d month %d day %d', $year, $month, $day));
      }
    }
    if ($month < 1 || $month > 12) {
      throw new RuntimeException(sprintf('wrong month %d', $month));
    }
    if ($day < 1 || $day > 31) {
      throw new RuntimeException(sprintf('wrong day %d', $day));
    }
    if ($hour < 0 || $hour > 23) {
      throw new RuntimeException(sprintf('wrong hour %d', $hour));
    }
    if ($minute < 0 || $minute > 59) {
      throw new RuntimeException(sprintf('wrong minute %d', $minute));
    }
    if ($second < 0 || $second > 59) {
      throw new RuntimeException(sprintf('wrong second %d', $second));
    }
    $this->year = $year;
    $this->month = $month;
    $this->day = $day;
    $this->hour = $hour;
    $this->minute = $minute;
    $this->second = $second;
  }

  public static function fromDate($date)
  {
    $calendar = DateTime::createFromFormat('Y-n-j G:i:s', $date->format('Y-n-j G:i:s'), $date->getTimezone());
    $calendar->setTimezone(new DateTimezone('Asia/Shanghai'));
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
    if ($hour > 23) {
      $hour -= 24;
      $day++;
    }

    return self::fromYmdHms($year, $month, $day, $hour, $minute, $second);
  }

  /**
   * 通过八字获取公历列表（晚子时日柱按当天，起始年为1900）
   * @param string $yearGanZhi 年柱
   * @param string $monthGanZhi 月柱
   * @param string $dayGanZhi 日柱
   * @param string $timeGanZhi 时柱
   * @return Solar[] 符合的公历列表
   */
  public static function fromBaZi($yearGanZhi, $monthGanZhi, $dayGanZhi, $timeGanZhi)
  {
    return self::fromBaZiBySect($yearGanZhi, $monthGanZhi, $dayGanZhi, $timeGanZhi, 2);
  }

  /**
   * 通过八字获取公历列表（起始年为1900）
   * @param string $yearGanZhi 年柱
   * @param string $monthGanZhi 月柱
   * @param string $dayGanZhi 日柱
   * @param string $timeGanZhi 时柱
   * @param int $sect 流派，2晚子时日柱按当天，1晚子时日柱按明天
   * @return Solar[] 符合的公历列表
   */
  public static function fromBaZiBySect($yearGanZhi, $monthGanZhi, $dayGanZhi, $timeGanZhi, $sect)
  {
    return self::fromBaZiBySectAndBaseYear($yearGanZhi, $monthGanZhi, $dayGanZhi, $timeGanZhi, $sect, 1900);
  }

  /**
   * 通过八字获取公历列表
   * @param string $yearGanZhi 年柱
   * @param string $monthGanZhi 月柱
   * @param string $dayGanZhi 日柱
   * @param string $timeGanZhi 时柱
   * @param int $sect 流派，2晚子时日柱按当天，1晚子时日柱按明天
   * @param int $baseYear 起始年
   * @return Solar[]
   */
  public static function fromBaZiBySectAndBaseYear($yearGanZhi, $monthGanZhi, $dayGanZhi, $timeGanZhi, $sect, $baseYear)
  {
    $sect = (1 == $sect) ? 1 : 2;
    $l = array();
    // 月地支距寅月的偏移值
    $m = LunarUtil::index(substr($monthGanZhi, strlen($monthGanZhi) / 2), LunarUtil::$ZHI, -1) - 2;
    if ($m < 0) {
      $m += 12;
    }
    // 月天干要一致
    if (((LunarUtil::index(substr($yearGanZhi, 0, strlen($yearGanZhi) / 2), LunarUtil::$GAN, -1) + 1) * 2 + $m) % 10 != LunarUtil::index(substr($monthGanZhi, 0, strlen($monthGanZhi) / 2), LunarUtil::$GAN, -1)) {
      return $l;
    }
    // 1年的立春是辛酉，序号57
    $y = LunarUtil::getJiaZiIndex($yearGanZhi) - 57;
    if ($y < 0) {
      $y += 60;
    }
    $y++;
    // 节令偏移值
    $m *= 2;
    // 时辰地支转时刻，子时按零点算
    $h = LunarUtil::index(substr($timeGanZhi, strlen($timeGanZhi) / 2), LunarUtil::$ZHI, -1) * 2;
    $hours = array($h);
    if (0 == $h && 2 == $sect) {
      $hours = array(0, 23);
    }
    $startYear = $baseYear - 1;

    // 结束年
    $date = new DateTime();
    $calendar = DateTime::createFromFormat('Y-n-j G:i:s', $date->format('Y-n-j G:i:s'), $date->getTimezone());
    $calendar->setTimezone(new DateTimezone('Asia/Shanghai'));
    $endYear = intval($calendar -> format('Y'));

    while ($y <= $endYear) {
      if ($y >= $startYear) {
        // 立春为寅月的开始
        $jieQiTable = Lunar::fromYmd($y, 1, 1)->getJieQiTable();
        // 节令推移，年干支和月干支就都匹配上了
        $solarTime = $jieQiTable[Lunar::$JIE_QI_IN_USE[4 + $m]];
        if ($solarTime->getYear() >= $baseYear) {
          // 日干支和节令干支的偏移值
          $d = LunarUtil::getJiaZiIndex($dayGanZhi) - LunarUtil::getJiaZiIndex($solarTime->getLunar()->getDayInGanZhiExact2());
          if ($d < 0) {
            $d += 60;
          }
          if ($d > 0) {
            // 从节令推移天数
            $solarTime = $solarTime->next($d);
          }
          foreach ($hours as $hour)
          {
            $mi = 0;
            $s = 0;
            if ($d == 0 && $hour == $solarTime->getHour()) {
              // 如果正好是节令当天，且小时和节令的小时数相等的极端情况，把分钟和秒钟带上
              $mi = $solarTime->getMinute();
              $s = $solarTime->getSecond();
            }
            // 验证一下
            $solar = Solar::fromYmdHms($solarTime->getYear(), $solarTime->getMonth(), $solarTime->getDay(), $hour, $mi, $s);
            $lunar = $solar->getLunar();
            $dgz = (2 == $sect) ? $lunar->getDayInGanZhiExact2() : $lunar->getDayInGanZhiExact();
            if (strcmp($lunar->getYearInGanZhiExact(), $yearGanZhi) == 0 && strcmp($lunar->getMonthInGanZhiExact(), $monthGanZhi) == 0 && strcmp($dgz, $dayGanZhi) == 0 && strcmp($lunar->getTimeInGanZhi(), $timeGanZhi) == 0) {
              $l[] = $solar;
            }
          }
        }
      }
      $y += 60;
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
    return sprintf('%04d-%02d-%02d', $this->year, $this->month, $this->day);
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
    return Lunar::fromSolar($this);
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
   * 公历日期相减，获得相差天数
   * @param $solar Solar 公历
   * @return int 天数
   */
  public function subtract($solar)
  {
    return SolarUtil::getDaysBetween($solar->getYear(), $solar->getMonth(), $solar->getDay(), $this->getYear(), $this->getMonth(), $this->getDay());
  }

  /**
   * 公历日期相减，获得相差分钟数
   * @param $solar Solar 公历
   * @return int 分钟数
   */
  public function subtractMinute($solar)
  {
    $days = $this->subtract($solar);
    $cm = $this->getHour() * 60 + $this->getMinute();
    $sm = $solar->getHour() * 60 + $solar->getMinute();
    $m = $cm - $sm;
    if ($m < 0) {
      $m += 1440;
      $days--;
    }
    $m += $days * 1440;
    return $m;
  }

  /**
   * 是否在指定日期之后
   * @param $solar Solar 公历
   * @return bool
   */
  public function isAfter($solar)
  {
    if ($this->year > $solar->getYear()) {
      return true;
    }
    if ($this->year < $solar->getYear()) {
      return false;
    }
    if ($this->month > $solar->getMonth()) {
      return true;
    }
    if ($this->month < $solar->getMonth()) {
      return false;
    }
    if ($this->day > $solar->getDay()) {
      return true;
    }
    if ($this->day < $solar->getDay()) {
      return false;
    }
    if ($this->hour > $solar->getHour()) {
      return true;
    }
    if ($this->hour < $solar->getHour()) {
      return false;
    }
    if ($this->minute > $solar->getMinute()) {
      return true;
    }
    if ($this->minute < $solar->getMinute()) {
      return false;
    }
    return $this->second > $solar->second;
  }

  /**
   * 是否在指定日期之前
   * @param $solar Solar 公历
   * @return bool
   */
  public function isBefore($solar)
  {
    if ($this->year > $solar->getYear()) {
      return false;
    }
    if ($this->year < $solar->getYear()) {
      return true;
    }
    if ($this->month > $solar->getMonth()) {
      return false;
    }
    if ($this->month < $solar->getMonth()) {
      return true;
    }
    if ($this->day > $solar->getDay()) {
      return false;
    }
    if ($this->day < $solar->getDay()) {
      return true;
    }
    if ($this->hour > $solar->getHour()) {
      return false;
    }
    if ($this->hour < $solar->getHour()) {
      return true;
    }
    if ($this->minute > $solar->getMinute()) {
      return false;
    }
    if ($this->minute < $solar->getMinute()) {
      return true;
    }
    return $this->second < $solar->second;
  }

  /**
   * 年推移
   * @param $years int 年数
   * @return Solar 公历
   */
  public function nextYear($years) {
    $y = $this->year + $years;
    $m = $this->month;
    $d = $this->day;
    if (1582 == $y && 10 == $m) {
      if ($d > 4 && $d < 15) {
        $d += 10;
      }
    } else if (2 == $m) {
      if ($d > 28) {
        if (!SolarUtil::isLeapYear($y)) {
          $d = 28;
        }
      }
    }
    return self::fromYmdHms($y, $m, $d, $this->hour, $this->minute, $this->second);
  }

  /**
   * 月推移
   * @param $months int 月数
   * @return Solar 公历
   */
  public function nextMonth($months) {
    $month = SolarMonth::fromYm($this->year, $this->month)->next($months);
    $y = $month->getYear();
    $m = $month->getMonth();
    $d = $this->day;
    if (1582 == $y && 10 == $m) {
      if ($d > 4 && $d < 15) {
        $d += 10;
      }
    } else {
      $days = SolarUtil::getDaysOfMonth($y, $m);
      if ($d > $days) {
        $d = $days;
      }
    }
    return self::fromYmdHms($y, $m, $d, $this->hour, $this->minute, $this->second);
  }

  /**
   * 天推移
   * @param $days int 天数
   * @return Solar 公历
   */
  public function next($days)
  {
    $y = $this->year;
    $m = $this->month;
    $d = $this->day;
    if (1582 == $y && 10 == $m) {
      if ($d > 4) {
        $d -= 10;
		}
    }
    if ($days > 0) {
      $d += $days;
      $daysInMonth = SolarUtil::getDaysOfMonth($y, $m);
      while ($d > $daysInMonth) {
        $d -= $daysInMonth;
        $m++;
        if ($m > 12) {
          $m = 1;
          $y++;
        }
        $daysInMonth = SolarUtil::getDaysOfMonth($y, $m);
      }
    } else if ($days < 0) {
      while ($d + $days <= 0) {
        $m--;
        if ($m < 1) {
          $m = 12;
          $y--;
        }
        $d += SolarUtil::getDaysOfMonth($y, $m);
      }
      $d += $days;
    }
    if (1582 == $y && 10 == $m) {
      if ($d > 4) {
        $d += 10;
      }
    }
    return self::fromYmdHms($y, $m, $d, $this->hour, $this->minute, $this->second);
  }

  /**
   * 小时推移
   * @param $hours int 小时数
   * @return Solar 公历
   */
  public function nextHour($hours) {
    $h = $this->hour + $hours;
    $n = $h < 0 ? -1 : 1;
    $hour = (int)abs($h);
    $days = (int)($hour / 24) * $n;
    $hour = ($hour % 24) * $n;
    if ($hour < 0) {
      $hour += 24;
      $days--;
    }
    $solar = $this->next($days);
    return self::fromYmdHms($solar->getYear(), $solar->getMonth(), $solar->getDay(), $hour, $solar->getMinute(), $solar->getSecond());
  }

  /**
   * 获取星期
   * @return int 星期，0星期天，1星期一，2星期二，3星期三，4星期四，5星期五，6星期六
   */
  public function getWeek()
  {
    return ((int)($this->getJulianDay() + 0.5) + 7000001) % 7;
  }

  /**
   * 获取往后推几个工作日的公历日期，如果要往前推，则天数用负数
   * @param int $days 天数
   * @return Solar
   */
  public function nextWorkday($days)
  {
    $solar = self::fromYmdHms($this->year, $this->month, $this->day, $this->hour, $this->minute, $this->second);
    if ($days != 0) {
      $rest = abs($days);
      $add = $days < 0 ? -1 : 1;
      while ($rest > 0) {
        $solar = $solar->next($add);
        $work = true;
        $holiday = HolidayUtil::getHolidayByYmd($solar->getYear(), $solar->getMonth(), $solar->getDay());
        if (null == $holiday) {
          $week = $solar->getWeek();
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
    }
    return $solar;
  }

  /**
   * 获取薪资比例(感谢 https://gitee.com/smr1987)
   * @return int 薪资比例：1/2/3
   */
  public function getSalaryRate()
  {
    // 元旦节
    if ($this->month == 1 && $this->day == 1) {
      return 3;
    }
    // 劳动节
    if ($this->month == 5 && $this->day == 1) {
      return 3;
    }
    // 国庆
    if ($this->month == 10 && $this->day >= 1 && $this->day <= 3) {
      return 3;
    }
    $lunar = $this->getLunar();
    // 春节
    if ($lunar->getMonth() == 1 && $lunar->getDay() >= 1 && $lunar->getDay() <= 3) {
      return 3;
    }
    // 端午
    if ($lunar->getMonth() == 5 && $lunar->getDay() == 5) {
      return 3;
    }
    // 中秋
    if ($lunar->getMonth() == 8 && $lunar->getDay() == 15) {
      return 3;
    }
    // 清明
    if (strcmp('清明', $lunar->getJieQi()) === 0) {
      return 3;
    }
    $holiday = HolidayUtil::getHolidayByYmd($this->year, $this->month, $this->day);
    if (null != $holiday) {
      // 法定假日非上班
      if (!$holiday->isWork()) {
        return 2;
      }
    } else {
      // 周末
      $week = $this->getWeek();
      if ($week == 6 || $week == 0) {
        return 2;
      }
    }
    // 工作日
    return 1;
  }

}
