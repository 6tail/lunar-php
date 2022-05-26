<?php

namespace com\nlf\calendar;

use com\nlf\calendar\util\LunarUtil;
use com\nlf\calendar\util\SolarUtil;
use DateTime;

bcscale(12);

/**
 * 农历日期
 * @package com\nlf\calendar
 */
class Lunar
{
  /**
   * 节气表，国标以冬至为首个节气
   * @var string[]
   */
  public static $JIE_QI = array('冬至', '小寒', '大寒', '立春', '雨水', '惊蛰', '春分', '清明', '谷雨', '立夏', '小满', '芒种', '夏至', '小暑', '大暑', '立秋', '处暑', '白露', '秋分', '寒露', '霜降', '立冬', '小雪', '大雪');

  /**
   * 实际的节气表
   * @var string[]
   */
  public static $JIE_QI_IN_USE = array('DA_XUE', '冬至', '小寒', '大寒', '立春', '雨水', '惊蛰', '春分', '清明', '谷雨', '立夏', '小满', '芒种', '夏至', '小暑', '大暑', '立秋', '处暑', '白露', '秋分', '寒露', '霜降', '立冬', '小雪', '大雪', 'DONG_ZHI', 'XIAO_HAN', 'DA_HAN', 'LI_CHUN', 'YU_SHUI', 'JING_ZHE');

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
   * 对应阳历
   * @var Solar
   */
  private $solar;

  /**
   * 时对应的天干下标，0-9
   * @var int
   */
  private $timeGanIndex;

  /**
   * 时对应的地支下标，0-11
   * @var int
   */
  private $timeZhiIndex;

  /**
   * 日对应的天干下标，0-9
   * @var int
   */
  private $dayGanIndex;

  /**
   * 日对应的天干下标（八字流派1，晚子时日柱算明天），0-9
   * @var int
   */
  private $dayGanIndexExact;

  /**
   * 日对应的天干下标（八字流派2，晚子时日柱算当天），0-9
   * @var int
   */
  private $dayGanIndexExact2;

  /**
   * 日对应的地支下标，0-11
   * @var int
   */
  private $dayZhiIndex;

  /**
   * 日对应的地支下标（八字流派1，晚子时日柱算明天），0-9
   * @var int
   */
  private $dayZhiIndexExact;

  /**
   * 日对应的地支下标（八字流派1，晚子时日柱算当天），0-9
   * @var int
   */
  private $dayZhiIndexExact2;

  /**
   * 月对应的天干下标（以节交接当天起算），0-9
   * @var int
   */
  private $monthGanIndex;

  /**
   * 月对应的地支下标（以节交接当天起算），0-11
   * @var int
   */
  private $monthZhiIndex;

  /**
   * 月对应的天干下标（最精确的，供八字用，以节交接时刻起算），0-9
   * @var int
   */
  private $monthGanIndexExact;

  /**
   * 月对应的地支下标（最精确的，供八字用，以节交接时刻起算），0-11
   * @var int
   */
  private $monthZhiIndexExact;

  /**
   * 年对应的天干下标（国标，以正月初一为起点），0-9
   * @var int
   */
  private $yearGanIndex;

  /**
   * 年对应的地支下标（国标，以正月初一为起点），0-11
   * @var int
   */
  private $yearZhiIndex;

  /**
   * 年对应的天干下标（月干计算用，以立春为起点），0-9
   * @var int
   */
  private $yearGanIndexByLiChun;

  /**
   * 年对应的地支下标（月支计算用，以立春为起点），0-11
   * @var int
   */
  private $yearZhiIndexByLiChun;

  /**
   * 年对应的天干下标（最精确的，供八字用，以立春交接时刻为起点），0-9
   * @var int
   */
  private $yearGanIndexExact;

  /**
   * 年对应的地支下标（最精确的，供八字用，以立春交接时刻为起点），0-11
   * @var int
   */
  private $yearZhiIndexExact;

  /**
   * 周下标，0-6
   * @var int
   */
  private $weekIndex;

  /**
   * 24节气表（对应阳历的准确时刻）
   * @var Solar[]
   */
  private $jieQi = array();

  /**
   * 八字
   * @var EightChar
   */
  private $eightChar = null;

  private function __construct($lunarYear, $lunarMonth, $lunarDay, $hour, $minute, $second, $solar, $y)
  {
    $this->year = $lunarYear;
    $this->month = $lunarMonth;
    $this->day = $lunarDay;
    $this->hour = $hour;
    $this->minute = $minute;
    $this->second = $second;
    $this->solar = $solar;
    $this->compute($y);
  }

  /**
   * 通过指定农历年月日获取农历
   * @param int $lunarYear 年（农历）
   * @param int $lunarMonth 月（农历），1到12，闰月为负，即闰2月=-2
   * @param int $lunarDay 日（农历），1到30
   * @return Lunar
   */
  public static function fromYmd($lunarYear, $lunarMonth, $lunarDay)
  {
    return Lunar::fromYmdHms($lunarYear, $lunarMonth, $lunarDay, 0, 0, 0);
  }

  /**
   * 通过指定农历年月日时分秒获取农历
   * @param int $lunarYear 年（农历）
   * @param int $lunarMonth 月（农历），1到12，闰月为负，即闰2月=-2
   * @param int $lunarDay 日（农历），1到30
   * @param int $hour 小时（阳历）
   * @param int $minute 分钟（阳历）
   * @param int $second 秒钟（阳历）
   * @return Lunar
   */
  public static function fromYmdHms($lunarYear, $lunarMonth, $lunarDay, $hour, $minute, $second)
  {
    $y = LunarYear::fromYear($lunarYear);
    $m = $y->getMonth($lunarMonth);
    $noon = Solar::fromJulianDay($m->getFirstJulianDay() + $lunarDay - 1);
    $solar = Solar::fromYmdHms($noon->getYear(), $noon->getMonth(), $noon->getDay(), $hour, $minute, $second);
    return new Lunar($lunarYear, $lunarMonth, $lunarDay, $hour, $minute, $second, $solar, $y);
  }

  /**
   * 通过阳历日期初始化
   * @param DateTime $date 阳历日期
   * @return Lunar
   */
  public static function fromDate($date)
  {
    $c = ExactDate::fromDate($date);
    $currentYear = intval($c->format('Y'));
    $currentMonth = intval($c->format('n'));
    $currentDay = intval($c->format('j'));
    $hour = intval($c->format('G'));
    $minute = intval($c->format('i'));
    $second = intval($c->format('s'));
    $ly = LunarYear::fromYear($currentYear);
    $lunarYear = 0;
    $lunarMonth = 0;
    $lunarDay = 0;
    foreach ($ly->getMonths() as $m) {
      // 初一
      $firstDay = Solar::fromJulianDay($m->getFirstJulianDay());
      $days = ExactDate::getDaysBetween($firstDay->getYear(), $firstDay->getMonth(), $firstDay->getDay(), $currentYear, $currentMonth, $currentDay);
      if ($days < $m->getDayCount()) {
        $lunarYear = $m->getYear();
        $lunarMonth = $m->getMonth();
        $lunarDay = $days + 1;
        break;
      }
    }
    return new Lunar($lunarYear, $lunarMonth, $lunarDay, $hour, $minute, $second, Solar::fromDate($date), $ly);
  }

  /**
   * 计算节气表
   * @param LunarYear $y
   */
  private function computeJieQi($y)
  {
    $jds = $y->getJieQiJulianDays();
    for ($i = 0, $j = count(Lunar::$JIE_QI_IN_USE); $i < $j; $i++) {
      $this->jieQi[Lunar::$JIE_QI_IN_USE[$i]] = Solar::fromJulianDay($jds[$i]);
    }
  }

  /**
   * 计算干支纪年
   */
  private function computeYear()
  {
    //以正月初一开始
    $offset = $this->year - 4;
    $yearGanIndex = $offset % 10;
    $yearZhiIndex = $offset % 12;

    if ($yearGanIndex < 0) {
      $yearGanIndex += 10;
    }

    if ($yearZhiIndex < 0) {
      $yearZhiIndex += 12;
    }

    //以立春作为新一年的开始的干支纪年
    $g = $yearGanIndex;
    $z = $yearZhiIndex;

    //精确的干支纪年，以立春交接时刻为准
    $gExact = $yearGanIndex;
    $zExact = $yearZhiIndex;

    $solarYear = $this->solar->getYear();
    $solarYmd = $this->solar->toYmd();
    $solarYmdHms = $this->solar->toYmdHms();

    //获取立春的阳历时刻
    $liChun = $this->jieQi['立春'];
    if ($liChun->getYear() != $solarYear) {
      $liChun = $this->jieQi['LI_CHUN'];
    }
    $liChunYmd = $liChun->toYmd();
    $liChunYmdHms = $liChun->toYmdHms();

    //阳历和阴历年份相同代表正月初一及以后
    if ($this->year === $solarYear) {
      //立春日期判断
      if (strcmp($solarYmd, $liChunYmd) < 0) {
        $g--;
        $z--;
      }
      //立春交接时刻判断
      if (strcmp($solarYmdHms, $liChunYmdHms) < 0) {
        $gExact--;
        $zExact--;
      }
    } else if ($this->year < $solarYear) {
      if (strcmp($solarYmd, $liChunYmd) >= 0) {
        $g++;
        $z++;
      }
      if (strcmp($solarYmdHms, $liChunYmdHms) >= 0) {
        $gExact++;
        $zExact++;
      }
    }

    $this->yearGanIndex = $yearGanIndex;
    $this->yearZhiIndex = $yearZhiIndex;

    $this->yearGanIndexByLiChun = ($g < 0 ? $g + 10 : $g) % 10;
    $this->yearZhiIndexByLiChun = ($z < 0 ? $z + 12 : $z) % 12;

    $this->yearGanIndexExact = ($gExact < 0 ? $gExact + 10 : $gExact) % 10;
    $this->yearZhiIndexExact = ($zExact < 0 ? $zExact + 12 : $zExact) % 12;
  }

  /**
   * 干支纪月计算
   */
  private function computeMonth()
  {
    $start = null;
    $ymd = $this->solar->toYmd();
    $time = $this->solar->toYmdHms();
    $size = count(Lunar::$JIE_QI_IN_USE);

    //序号：大雪以前-3，大雪到小寒之间-2，小寒到立春之间-1，立春之后0
    $index = -3;
    for ($i = 0; $i < $size; $i += 2) {
      $end = $this->jieQi[Lunar::$JIE_QI_IN_USE[$i]];
      $symd = (null == $start) ? $ymd : $start->toYmd();
      if (strcmp($ymd, $symd) >= 0 && strcmp($ymd, $end->toYmd()) < 0) {
        break;
      }
      $start = $end;
      $index++;
    }
    //干偏移值（以立春当天起算）
    $offset = ((($this->yearGanIndexByLiChun + ($index < 0 ? 1 : 0)) % 5 + 1) * 2) % 10;
    $this->monthGanIndex = (($index < 0 ? $index + 10 : $index) + $offset) % 10;
    $this->monthZhiIndex = (($index < 0 ? $index + 12 : $index) + LunarUtil::$BASE_MONTH_ZHI_INDEX) % 12;

    $start = null;
    $index = -3;
    for ($i = 0; $i < $size; $i += 2) {
      $end = $this->jieQi[Lunar::$JIE_QI_IN_USE[$i]];
      $stime = null == $start ? $time : $start->toYmdHms();
      if (strcmp($time, $stime) >= 0 && strcmp($time, $end->toYmdHms()) < 0) {
        break;
      }
      $start = $end;
      $index++;
    }
    //干偏移值（以立春交接时刻起算）
    $offset = ((($this->yearGanIndexExact + ($index < 0 ? 1 : 0)) % 5 + 1) * 2) % 10;
    $this->monthGanIndexExact = (($index < 0 ? $index + 10 : $index) + $offset) % 10;
    $this->monthZhiIndexExact = (($index < 0 ? $index + 12 : $index) + LunarUtil::$BASE_MONTH_ZHI_INDEX) % 12;
  }

  /**
   * 干支纪日计算
   */
  private function computeDay()
  {
    $noon = Solar::fromYmdHms($this->solar->getYear(), $this->solar->getMonth(), $this->solar->getDay(), 12, 0, 0);
    $offset = (int)$noon->getJulianDay() - 11;
    $dayGanIndex = $offset % 10;
    $dayZhiIndex = $offset % 12;

    $this->dayGanIndex = $dayGanIndex;
    $this->dayZhiIndex = $dayZhiIndex;

    $dayGanExact = $dayGanIndex;
    $dayZhiExact = $dayZhiIndex;

    $this->dayGanIndexExact2 = $dayGanExact;
    $this->dayZhiIndexExact2 = $dayZhiExact;

    $hm = ($this->hour < 10 ? '0' : '') . $this->hour . ':' . ($this->minute < 10 ? '0' : '') . $this->minute;
    if (strcmp($hm, '23:00') >= 0 && strcmp($hm, '23:59') <= 0) {
      $dayGanExact++;
      if ($dayGanExact >= 10) {
        $dayGanExact -= 10;
      }
      $dayZhiExact++;
      if ($dayZhiExact >= 12) {
        $dayZhiExact -= 12;
      }
    }

    $this->dayGanIndexExact = $dayGanExact;
    $this->dayZhiIndexExact = $dayZhiExact;
  }

  /**
   * 干支纪时计算
   */
  private function computeTime()
  {
    $this->timeZhiIndex = LunarUtil::getTimeZhiIndex(($this->hour < 10 ? '0' : '') . $this->hour . ':' . ($this->minute < 10 ? '0' : '') . $this->minute);
    $this->timeGanIndex = ($this->dayGanIndexExact % 5 * 2 + $this->timeZhiIndex) % 10;
  }

  /**
   * 星期计算
   */
  private function computeWeek()
  {
    $this->weekIndex = $this->solar->getWeek();
  }

  private function compute($y)
  {
    $this->computeJieQi($y);
    $this->computeYear();
    $this->computeMonth();
    $this->computeDay();
    $this->computeTime();
    $this->computeWeek();
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

  public function getSolar()
  {
    return $this->solar;
  }

  /**
   * 获取年份的天干（以正月初一作为新年的开始）
   * @return string 天干，如辛
   */
  public function getYearGan()
  {
    return LunarUtil::$GAN[$this->yearGanIndex + 1];
  }

  /**
   * 获取年份的天干（以立春当天作为新年的开始）
   *
   * @return string 天干，如辛
   */
  public function getYearGanByLiChun()
  {
    return LunarUtil::$GAN[$this->yearGanIndexByLiChun + 1];
  }

  /**
   * 获取最精确的年份天干（以立春交接的时刻作为新年的开始）
   *
   * @return string 天干，如辛
   */
  public function getYearGanExact()
  {
    return LunarUtil::$GAN[$this->yearGanIndexExact + 1];
  }

  /**
   * 获取年份的地支（以正月初一作为新年的开始）
   *
   * @return string 地支，如亥
   */
  public function getYearZhi()
  {
    return LunarUtil::$ZHI[$this->yearZhiIndex + 1];
  }

  /**
   * 获取年份的地支（以立春当天作为新年的开始）
   *
   * @return string 地支，如亥
   */
  public function getYearZhiByLiChun()
  {
    return LunarUtil::$ZHI[$this->yearZhiIndexByLiChun + 1];
  }

  /**
   * 获取最精确的年份地支（以立春交接的时刻作为新年的开始）
   *
   * @return string 地支，如亥
   */
  public function getYearZhiExact()
  {
    return LunarUtil::$ZHI[$this->yearZhiIndexExact + 1];
  }

  /**
   * 获取干支纪年（年柱）（以正月初一作为新年的开始）
   * @return string 年份的干支（年柱），如辛亥
   */
  public function getYearInGanZhi()
  {
    return $this->getYearGan() . $this->getYearZhi();
  }

  /**
   * 获取干支纪年（年柱）（以立春当天作为新年的开始）
   * @return string 年份的干支（年柱），如辛亥
   */
  public function getYearInGanZhiByLiChun()
  {
    return $this->getYearGanByLiChun() . $this->getYearZhiByLiChun();
  }

  /**
   * 获取干支纪年（年柱）（以立春交接的时刻作为新年的开始）
   * @return string 年份的干支（年柱），如辛亥
   */
  public function getYearInGanZhiExact()
  {
    return $this->getYearGanExact() . $this->getYearZhiExact();
  }

  /**
   * 获取干支纪月（月柱）（以节交接当天起算）
   * <p>月天干口诀：甲己丙寅首，乙庚戊寅头。丙辛从庚寅，丁壬壬寅求，戊癸甲寅居，周而复始流。</p>
   * <p>月地支：正月起寅</p>
   *
   * @return string 干支纪月（月柱），如己卯
   */
  public function getMonthInGanZhi()
  {
    return $this->getMonthGan() . $this->getMonthZhi();
  }

  /**
   * 获取精确的干支纪月（月柱）（以节交接时刻起算）
   * <p>月天干口诀：甲己丙寅首，乙庚戊寅头。丙辛从庚寅，丁壬壬寅求，戊癸甲寅居，周而复始流。</p>
   * <p>月地支：正月起寅</p>
   *
   * @return string 干支纪月（月柱），如己卯
   */
  public function getMonthInGanZhiExact()
  {
    return $this->getMonthGanExact() . $this->getMonthZhiExact();
  }

  /**
   * 获取月天干（以节交接当天起算）
   * @return string 月天干，如己
   */
  public function getMonthGan()
  {
    return LunarUtil::$GAN[$this->monthGanIndex + 1];
  }

  /**
   * 获取精确的月天干（以节交接时刻起算）
   * @return string 月天干，如己
   */
  public function getMonthGanExact()
  {
    return LunarUtil::$GAN[$this->monthGanIndexExact + 1];
  }

  /**
   * 获取月地支（以节交接当天起算）
   * @return string 月地支，如卯
   */
  public function getMonthZhi()
  {
    return LunarUtil::$ZHI[$this->monthZhiIndex + 1];
  }

  /**
   * 获取精确的月地支（以节交接时刻起算）
   * @return string 月地支，如卯
   */
  public function getMonthZhiExact()
  {
    return LunarUtil::$ZHI[$this->monthZhiIndexExact + 1];
  }

  /**
   * 获取干支纪日（日柱）
   *
   * @return string 干支纪日（日柱），如己卯
   */
  public function getDayInGanZhi()
  {
    return $this->getDayGan() . $this->getDayZhi();
  }

  /**
   * 获取干支纪日（日柱，八字流派1，晚子时日柱算明天）
   * @return string 干支纪日（日柱），如己卯
   */
  public function getDayInGanZhiExact()
  {
    return $this->getDayGanExact() . $this->getDayZhiExact();
  }

  /**
   * 获取干支纪日（日柱，八字流派2，晚子时日柱算当天）
   * @return string 干支纪日（日柱），如己卯
   */
  public function getDayInGanZhiExact2()
  {
    return $this->getDayGanExact2() . $this->getDayZhiExact2();
  }

  /**
   * 获取日天干
   *
   * @return string 日天干，如甲
   */
  public function getDayGan()
  {
    return LunarUtil::$GAN[$this->dayGanIndex + 1];
  }

  /**
   * 获取日天干（八字流派1，晚子时日柱算明天）
   * @return string 日天干，如甲
   */
  public function getDayGanExact()
  {
    return LunarUtil::$GAN[$this->dayGanIndexExact + 1];
  }

  /**
   * 获取日天干（八字流派1，晚子时日柱算当天）
   * @return string 日天干，如甲
   */
  public function getDayGanExact2()
  {
    return LunarUtil::$GAN[$this->dayGanIndexExact2 + 1];
  }

  /**
   * 获取日地支
   *
   * @return string 日地支，如卯
   */
  public function getDayZhi()
  {
    return LunarUtil::$ZHI[$this->dayZhiIndex + 1];
  }

  /**
   * 获取日地支（八字流派1，晚子时日柱算明天）
   * @return string 日地支，如卯
   */
  public function getDayZhiExact()
  {
    return LunarUtil::$ZHI[$this->dayZhiIndexExact + 1];
  }

  /**
   * 获取日地支（八字流派1，晚子时日柱算当天）
   * @return string 日地支，如卯
   */
  public function getDayZhiExact2()
  {
    return LunarUtil::$ZHI[$this->dayZhiIndexExact2 + 1];
  }

  /**
   * 获取年生肖（以正月初一起算）
   *
   * @return string 年生肖，如虎
   */
  public function getYearShengXiao()
  {
    return LunarUtil::$SHENG_XIAO[$this->yearZhiIndex + 1];
  }

  /**
   * 获取年生肖（以立春当天起算）
   *
   * @return string 年生肖，如虎
   */
  public function getYearShengXiaoByLiChun()
  {
    return LunarUtil::$SHENG_XIAO[$this->yearZhiIndexByLiChun + 1];
  }

  /**
   * 获取精确的年生肖（以立春交接时刻起算）
   *
   * @return string 年生肖，如虎
   */
  public function getYearShengXiaoExact()
  {
    return LunarUtil::$SHENG_XIAO[$this->yearZhiIndexExact + 1];
  }

  /**
   * 获取月生肖
   *
   * @return string 月生肖，如虎
   */
  public function getMonthShengXiao()
  {
    return LunarUtil::$SHENG_XIAO[$this->monthZhiIndex + 1];
  }

  /**
   * 获取日生肖
   *
   * @return string 日生肖，如虎
   */
  public function getDayShengXiao()
  {
    return LunarUtil::$SHENG_XIAO[$this->dayZhiIndex + 1];
  }

  /**
   * 获取时辰生肖
   *
   * @return string 时辰生肖，如虎
   */
  public function getTimeShengXiao()
  {
    return LunarUtil::$SHENG_XIAO[$this->timeZhiIndex + 1];
  }

  /**
   * 获取中文的年
   *
   * @return string 中文年，如二零零一
   */
  public function getYearInChinese()
  {
    $y = $this->year . '';
    $s = '';
    for ($i = 0, $j = strlen($y); $i < $j; $i++) {
      $s .= LunarUtil::$NUMBER[ord(substr($y, $i, 1)) - 48];
    }
    return $s;
  }

  /**
   * 获取中文的月
   *
   * @return string 中文月，如正
   */
  public function getMonthInChinese()
  {
    return ($this->month < 0 ? '闰' : '') . LunarUtil::$MONTH[abs($this->month)];
  }

  /**
   * 获取中文日
   *
   * @return string 中文日，如初一
   */
  public function getDayInChinese()
  {
    return LunarUtil::$DAY[$this->day];
  }

  /**
   * 获取时辰（地支）
   * @return string 时辰（地支）
   */
  public function getTimeZhi()
  {
    return LunarUtil::$ZHI[$this->timeZhiIndex + 1];
  }

  /**
   * 获取时辰（天干）
   * @return string 时辰（天干）
   */
  public function getTimeGan()
  {
    return LunarUtil::$GAN[$this->timeGanIndex + 1];
  }

  /**
   * 获取时辰干支（时柱）
   * @return string 时辰干支（时柱）
   */
  public function getTimeInGanZhi()
  {
    return $this->getTimeGan() . $this->getTimeZhi();
  }

  /**
   * 获取季节
   * @return string 农历季节
   */
  public function getSeason()
  {
    return LunarUtil::$SEASON[abs($this->month)];
  }

  private function convertJieQi($name)
  {
    $jq = $name;
    if (strcmp('DONG_ZHI', $jq) === 0) {
      $jq = '冬至';
    } else if (strcmp('DA_HAN', $jq) === 0) {
      $jq = '大寒';
    } else if (strcmp('XIAO_HAN', $jq) === 0) {
      $jq = '小寒';
    } else if (strcmp('LI_CHUN', $jq) === 0) {
      $jq = '立春';
    } else if (strcmp('DA_XUE', $jq) === 0) {
      $jq = '大雪';
    } else if (strcmp('YU_SHUI', $jq) === 0) {
      $jq = '雨水';
    } else if (strcmp('JING_ZHE', $jq) === 0) {
      $jq = '惊蛰';
    }
    return $jq;
  }

  /**
   * 获取节
   *
   * @return string 节
   */
  public function getJie()
  {
    for ($i = 0, $j = count(Lunar::$JIE_QI_IN_USE); $i < $j; $i += 2) {
      $key = Lunar::$JIE_QI_IN_USE[$i];
      $d = $this->jieQi[$key];
      if ($d->getYear() === $this->solar->getYear() && $d->getMonth() === $this->solar->getMonth() && $d->getDay() === $this->solar->getDay()) {
        return $this->convertJieQi($key);
      }
    }
    return '';
  }

  /**
   * 获取气
   *
   * @return string 气
   */
  public function getQi()
  {
    for ($i = 1, $j = count(Lunar::$JIE_QI_IN_USE); $i < $j; $i += 2) {
      $key = Lunar::$JIE_QI_IN_USE[$i];
      $d = $this->jieQi[$key];
      if ($d->getYear() === $this->solar->getYear() && $d->getMonth() === $this->solar->getMonth() && $d->getDay() === $this->solar->getDay()) {
        return $this->convertJieQi($key);
      }
    }
    return '';
  }

  /**
   * 获取星期，0代表周日，1代表周一
   *
   * @return int 0123456
   */
  public function getWeek()
  {
    return $this->weekIndex;
  }

  /**
   * 获取星期的中文
   *
   * @return string 日一二三四五六
   */
  public function getWeekInChinese()
  {
    return SolarUtil::$WEEK[$this->getWeek()];
  }

  /**
   * 获取宿
   *
   * @return string 宿
   */
  public function getXiu()
  {
    return LunarUtil::$XIU[$this->getDayZhi() . $this->getWeek()];
  }

  /**
   * 获取宿吉凶
   *
   * @return string 吉/凶
   */
  public function getXiuLuck()
  {
    return LunarUtil::$XIU_LUCK[$this->getXiu()];
  }

  /**
   * 获取宿歌诀
   *
   * @return string 宿歌诀
   */
  public function getXiuSong()
  {
    return LunarUtil::$XIU_SONG[$this->getXiu()];
  }

  /**
   * 获取政
   *
   * @return string 政
   */
  public function getZheng()
  {
    return LunarUtil::$ZHENG[$this->getXiu()];
  }

  /**
   * 获取动物
   * @return string 动物
   */
  public function getAnimal()
  {
    return LunarUtil::$ANIMAL[$this->getXiu()];
  }

  /**
   * 获取宫
   * @return string 宫
   */
  public function getGong()
  {
    return LunarUtil::$GONG[$this->getXiu()];
  }

  /**
   * 获取兽
   * @return string 兽
   */
  public function getShou()
  {
    return LunarUtil::$SHOU[$this->getGong()];
  }

  /**
   * 获取节日，有可能一天会有多个节日
   *
   * @return string[] 节日列表，如春节
   */
  public function getFestivals()
  {
    $l = array();
    $key = $this->month . '-' . $this->day;
    if (!empty(LunarUtil::$FESTIVAL[$key])) {
      $l[] = LunarUtil::$FESTIVAL[$key];
    }
    if (abs($this->month) === 12 && $this->day >= 29 && $this->year != $this->next(1)->getYear()) {
      $l[] = '除夕';
    }
    return $l;
  }

  /**
   * 获取非正式的节日，有可能一天会有多个节日
   *
   * @return string[] 非正式的节日列表，如中元节
   */
  public function getOtherFestivals()
  {
    $l = array();
    $key = $this->month . '-' . $this->day;
    if (!empty(LunarUtil::$OTHER_FESTIVAL[$key])) {
      foreach (LunarUtil::$OTHER_FESTIVAL[$key] as $f) {
        $l[] = $f;
      }
    }
    $jq = $this->jieQi['清明'];
    $solarYmd = $this->solar->toYmd();
    if (strcmp($solarYmd, $jq->next(-1)->toYmd()) === 0) {
      $l[] = '寒食节';
    }
    $jq = $this->jieQi['立春'];
    $offset = 4 - $jq->getLunar()->getDayGanIndex();
    if ($offset < 0) {
      $offset += 10;
    }
    if (strcmp($solarYmd, $jq->next($offset + 40)->toYmd()) === 0) {
      $l[] = '春社';
    }
    $jq = $this->jieQi['立秋'];
    $offset = 4 - $jq->getLunar()->getDayGanIndex();
    if ($offset < 0) {
      $offset += 10;
    }
    if (strcmp($solarYmd, $jq->next($offset + 40)->toYmd()) === 0) {
      $l[] = '秋社';
    }
    return $l;
  }

  /**
   * 获取彭祖百忌天干
   * @return string 彭祖百忌天干
   */
  public function getPengZuGan()
  {
    return LunarUtil::$PENG_ZU_GAN[$this->dayGanIndex + 1];
  }

  /**
   * 获取彭祖百忌地支
   * @return string 彭祖百忌地支
   */
  public function getPengZuZhi()
  {
    return LunarUtil::$PENG_ZU_ZHI[$this->dayZhiIndex + 1];
  }

  /**
   * 获取日喜神方位
   * @return string 喜神方位，如艮
   */
  public function getPositionXi()
  {
    return $this->getDayPositionXi();
  }

  /**
   * 获取日喜神方位描述
   * @return string 喜神方位描述，如东北
   */
  public function getPositionXiDesc()
  {
    return $this->getDayPositionXiDesc();
  }

  /**
   * 获取日喜神方位
   * @return string 喜神方位，如艮
   */
  public function getDayPositionXi()
  {
    return LunarUtil::$POSITION_XI[$this->dayGanIndex + 1];
  }

  /**
   * 获取日喜神方位描述
   * @return string 喜神方位描述，如东北
   */
  public function getDayPositionXiDesc()
  {
    return LunarUtil::$POSITION_DESC[$this->getDayPositionXi()];
  }

  /**
   * 获取时辰喜神方位
   * @return string 喜神方位，如艮
   */
  public function getTimePositionXi()
  {
    return LunarUtil::$POSITION_XI[$this->timeGanIndex + 1];
  }

  /**
   * 获取时辰喜神方位描述
   * @return string 喜神方位描述，如东北
   */
  public function getTimePositionXiDesc()
  {
    return LunarUtil::$POSITION_DESC[$this->getTimePositionXi()];
  }

  /**
   * 获取日阳贵神方位
   * @return string 阳贵神方位，如艮
   */
  public function getPositionYangGui()
  {
    return $this->getDayPositionYangGui();
  }

  /**
   * 获取日阳贵神方位描述
   * @return string 阳贵神方位描述，如东北
   */
  public function getPositionYangGuiDesc()
  {
    return $this->getDayPositionYangGuiDesc();
  }

  /**
   * 获取日阳贵神方位
   * @return string 阳贵神方位，如艮
   */
  public function getDayPositionYangGui()
  {
    return LunarUtil::$POSITION_YANG_GUI[$this->dayGanIndex + 1];
  }

  /**
   * 获取日阳贵神方位描述
   * @return string 阳贵神方位描述，如东北
   */
  public function getDayPositionYangGuiDesc()
  {
    return LunarUtil::$POSITION_DESC[$this->getDayPositionYangGui()];
  }

  /**
   * 获取时辰阳贵神方位
   * @return string 阳贵神方位，如艮
   */
  public function getTimePositionYangGui()
  {
    return LunarUtil::$POSITION_YANG_GUI[$this->timeGanIndex + 1];
  }

  /**
   * 获取时辰阳贵神方位描述
   * @return string 阳贵神方位描述，如东北
   */
  public function getTimePositionYangGuiDesc()
  {
    return LunarUtil::$POSITION_DESC[$this->getTimePositionYangGui()];
  }

  /**
   * 获取日阴贵神方位
   * @return string 阴贵神方位，如艮
   */
  public function getPositionYinGui()
  {
    return $this->getDayPositionYinGui();
  }

  /**
   * 获取日阴贵神方位描述
   * @return string 阴贵神方位描述，如东北
   */
  public function getPositionYinGuiDesc()
  {
    return $this->getDayPositionYinGuiDesc();
  }

  /**
   * 获取日阴贵神方位
   * @return string 阴贵神方位，如艮
   */
  public function getDayPositionYinGui()
  {
    return LunarUtil::$POSITION_YIN_GUI[$this->dayGanIndex + 1];
  }

  /**
   * 获取日阴贵神方位描述
   * @return string 阴贵神方位描述，如东北
   */
  public function getDayPositionYinGuiDesc()
  {
    return LunarUtil::$POSITION_DESC[$this->getDayPositionYinGui()];
  }

  /**
   * 获取时辰阴贵神方位
   * @return string 阴贵神方位，如艮
   */
  public function getTimePositionYinGui()
  {
    return LunarUtil::$POSITION_YIN_GUI[$this->timeGanIndex + 1];
  }

  /**
   * 获取时辰阴贵神方位描述
   * @return string 阴贵神方位描述，如东北
   */
  public function getTimePositionYinGuiDesc()
  {
    return LunarUtil::$POSITION_DESC[$this->getTimePositionYinGui()];
  }

  /**
   * 获取日福神方位
   * @return string 福神方位，如艮
   */
  public function getPositionFu()
  {
    return $this->getDayPositionFu();
  }

  /**
   * 获取日福神方位描述
   * @return string 福神方位描述，如东北
   */
  public function getPositionFuDesc()
  {
    return $this->getDayPositionFuDesc();
  }

  /**
   * 获取日福神方位，默认流派2
   * @return string 福神方位，如艮
   */
  public function getDayPositionFu()
  {
    return $this->getDayPositionFuBySect(2);
  }

  /**
   * 获取日福神方位
   * @param int $sect 流派，可选1或2
   * @return string 福神方位，如艮
   */
  public function getDayPositionFuBySect($sect)
  {
    $fu = 1 == $sect ? LunarUtil::$POSITION_FU : LunarUtil::$POSITION_FU_2;
    return $fu[$this->dayGanIndex + 1];
  }

  /**
   * 获取日福神方位描述，默认流派2
   * @return string 福神方位描述，如东北
   */
  public function getDayPositionFuDesc()
  {
    return $this->getDayPositionFuDescBySect(2);
  }

  /**
   * 获取日福神方位描述
   * @param int $sect 流派，可选1或2
   * @return string 福神方位描述，如东北
   */
  public function getDayPositionFuDescBySect($sect)
  {
    return LunarUtil::$POSITION_DESC[$this->getDayPositionFuBySect($sect)];
  }

  /**
   * 获取时辰福神方位，默认流派2
   * @return string 福神方位，如艮
   */
  public function getTimePositionFu()
  {
    return $this->getTimePositionFuBySect(2);
  }

  /**
   * 获取时辰福神方位
   * @param int $sect 流派，可选1或2
   * @return string 福神方位，如艮
   */
  public function getTimePositionFuBySect($sect)
  {
    $fu = 1 == $sect ? LunarUtil::$POSITION_FU : LunarUtil::$POSITION_FU_2;
    return $fu[$this->timeGanIndex + 1];
  }

  /**
   * 获取时辰福神方位描述，默认流派2
   * @return string 福神方位描述，如东北
   */
  public function getTimePositionFuDesc()
  {
    return $this->getTimePositionFuDescBySect(2);
  }

  /**
   * 获取时辰福神方位描述
   * @param int $sect 流派，可选1或2
   * @return string 福神方位描述，如东北
   */
  public function getTimePositionFuDescBySect($sect)
  {
    return LunarUtil::$POSITION_DESC[$this->getTimePositionFuBySect($sect)];
  }

  /**
   * 获取日财神方位
   * @return string 财神方位，如艮
   */
  public function getPositionCai()
  {
    return $this->getDayPositionCai();
  }

  /**
   * 获取日财神方位描述
   * @return string 财神方位描述，如东北
   */
  public function getPositionCaiDesc()
  {
    return $this->getDayPositionCaiDesc();
  }

  /**
   * 获取日财神方位
   * @return string 财神方位，如艮
   */
  public function getDayPositionCai()
  {
    return LunarUtil::$POSITION_CAI[$this->dayGanIndex + 1];
  }

  /**
   * 获取日财神方位描述
   * @return string 财神方位描述，如东北
   */
  public function getDayPositionCaiDesc()
  {
    return LunarUtil::$POSITION_DESC[$this->getDayPositionCai()];
  }

  /**
   * 获取时辰财神方位
   * @return string 财神方位，如艮
   */
  public function getTimePositionCai()
  {
    return LunarUtil::$POSITION_CAI[$this->timeGanIndex + 1];
  }

  /**
   * 获取时辰财神方位描述
   * @return string 财神方位描述，如东北
   */
  public function getTimePositionCaiDesc()
  {
    return LunarUtil::$POSITION_DESC[$this->getTimePositionCai()];
  }

  public function getYearPositionTaiSui()
  {
    return $this->getYearPositionTaiSuiBySect(2);
  }

  public function getYearPositionTaiSuiBySect($sect)
  {
    switch ($sect) {
      case 1:
        $yearZhiIndex = $this->yearZhiIndex;
        break;
      case 3:
        $yearZhiIndex = $this->yearZhiIndexExact;
        break;
      default:
        $yearZhiIndex = $this->yearZhiIndexByLiChun;
    }
    return LunarUtil::$POSITION_TAI_SUI_YEAR[$yearZhiIndex];
  }

  public function getYearPositionTaiSuiDesc()
  {
    return $this->getYearPositionTaiSuiDescBySect(2);
  }

  public function getYearPositionTaiSuiDescBySect($sect)
  {
    return LunarUtil::$POSITION_DESC[$this->getYearPositionTaiSuiBySect($sect)];
  }

  protected function _getMonthPositionTaiSui($monthZhiIndex, $monthGanIndex)
  {
    $m = $monthZhiIndex - LunarUtil::$BASE_MONTH_ZHI_INDEX;
    if ($m < 0) {
      $m += 12;
    }
    switch ($m) {
      case 0:
      case 4:
      case 8:
        $p = '艮';
        break;
      case 2:
      case 6:
      case 10:
        $p = '坤';
        break;
      case 3:
      case 7:
      case 11:
        $p = '巽';
        break;
      default:
        $p = LunarUtil::$POSITION_GAN[$monthGanIndex];
    }
    return $p;
  }

  public function getMonthPositionTaiSuiBySect($sect)
  {
    switch ($sect) {
      case 3:
        $monthZhiIndex = $this->monthZhiIndexExact;
        $monthGanIndex = $this->monthGanIndexExact;
        break;
      default:
        $monthZhiIndex = $this->monthZhiIndex;
        $monthGanIndex = $this->monthGanIndex;
    }
    return $this->_getMonthPositionTaiSui($monthZhiIndex, $monthGanIndex);
  }

  public function getMonthPositionTaiSui()
  {
    return $this->getMonthPositionTaiSuiBySect(2);
  }

  public function getMonthPositionTaiSuiDesc()
  {
    return $this->getMonthPositionTaiSuiDescBySect(2);
  }

  public function getMonthPositionTaiSuiDescBySect($sect)
  {
    return LunarUtil::$POSITION_DESC[$this->getMonthPositionTaiSuiBySect($sect)];
  }

  protected function _getDayPositionTaiSui($dayInGanZhi, $yearZhiIndex)
  {
    if (strpos('甲子,乙丑,丙寅,丁卯,戊辰,已巳',$dayInGanZhi) !== false) {
      $p = '震';
    } else if (strpos('丙子,丁丑,戊寅,已卯,庚辰,辛巳',$dayInGanZhi) !== false) {
      $p = '离';
    } else if (strpos('戊子,已丑,庚寅,辛卯,壬辰,癸巳',$dayInGanZhi) !== false) {
      $p = '中';
    } else if (strpos('庚子,辛丑,壬寅,癸卯,甲辰,乙巳',$dayInGanZhi) !== false) {
      $p = '兑';
    } else if (strpos('壬子,癸丑,甲寅,乙卯,丙辰,丁巳',$dayInGanZhi) !== false) {
      $p = '坎';
    } else {
      $p = LunarUtil::$POSITION_TAI_SUI_YEAR[$yearZhiIndex];
    }
    return $p;
  }

  public function getDayPositionTaiSuiBySect($sect)
  {
    switch ($sect) {
      case 1:
        $dayInGanZhi = $this->getDayInGanZhi();
        $yearZhiIndex = $this->yearZhiIndex;
        break;
      case 3:
        $dayInGanZhi = $this->getDayInGanZhi();
        $yearZhiIndex = $this->yearZhiIndexExact;
        break;
      default:
        $dayInGanZhi = $this->getDayInGanZhiExact2();
        $yearZhiIndex = $this->yearZhiIndexByLiChun;
    }
    return $this->_getDayPositionTaiSui($dayInGanZhi, $yearZhiIndex);
  }

  public function getDayPositionTaiSui()
  {
    return $this->getDayPositionTaiSuiBySect(2);
  }

  public function getDayPositionTaiSuiDesc()
  {
    return $this->getDayPositionTaiSuiDescBySect(2);
  }

  public function getDayPositionTaiSuiDescBySect($sect)
  {
    return LunarUtil::$POSITION_DESC[$this->getDayPositionTaiSuiBySect($sect)];
  }

  /**
   * 获取冲
   * @return string 冲，如申
   */
  public function getChong()
  {
    return $this->getDayChong();
  }

  /**
   * 获取日冲
   * @return string 日冲，如申
   */
  public function getDayChong()
  {
    return LunarUtil::$CHONG[$this->dayZhiIndex];
  }

  /**
   * 获取时冲
   * @return string 时冲，如申
   */
  public function getTimeChong()
  {
    return LunarUtil::$CHONG[$this->timeZhiIndex];
  }

  /**
   * 获取无情之克的冲天干
   * @return string 无情之克的冲天干，如甲
   */
  public function getChongGan()
  {
    return $this->getDayChongGan();
  }

  /**
   * 获取无情之克的日冲天干
   * @return string 无情之克的日冲天干，如甲
   */
  public function getDayChongGan()
  {
    return LunarUtil::$CHONG_GAN[$this->dayGanIndex];
  }

  /**
   * 获取无情之克的时冲天干
   * @return string 无情之克的时冲天干，如甲
   */
  public function getTimeChongGan()
  {
    return LunarUtil::$CHONG_GAN[$this->timeGanIndex];
  }

  /**
   * 获取有情之克的冲天干
   * @return string 有情之克的冲天干，如甲
   */
  public function getChongGanTie()
  {
    return $this->getDayChongGanTie();
  }

  /**
   * 获取有情之克的日冲天干
   * @return string 有情之克的日冲天干，如甲
   */
  public function getDayChongGanTie()
  {
    return LunarUtil::$CHONG_GAN_TIE[$this->dayGanIndex];
  }

  /**
   * 获取有情之克的时冲天干
   * @return string 有情之克的时冲天干，如甲
   */
  public function getTimeChongGanTie()
  {
    return LunarUtil::$CHONG_GAN_TIE[$this->timeGanIndex];
  }

  /**
   * 获取冲生肖
   * @return string 冲生肖，如猴
   */
  public function getChongShengXiao()
  {
    return $this->getDayChongShengXiao();
  }

  /**
   * 获取日冲生肖
   * @return string 日冲生肖，如猴
   */
  public function getDayChongShengXiao()
  {
    $chong = $this->getDayChong();
    for ($i = 0, $j = count(LunarUtil::$ZHI); $i < $j; $i++) {
      if (strcmp(LunarUtil::$ZHI[$i], $chong) === 0) {
        return LunarUtil::$SHENG_XIAO[$i];
      }
    }
    return '';
  }

  /**
   * 获取时冲生肖
   * @return string 时冲生肖，如猴
   */
  public function getTimeChongShengXiao()
  {
    $chong = $this->getTimeChong();
    for ($i = 0, $j = count(LunarUtil::$ZHI); $i < $j; $i++) {
      if (strcmp(LunarUtil::$ZHI[$i], $chong) === 0) {
        return LunarUtil::$SHENG_XIAO[$i];
      }
    }
    return '';
  }

  /**
   * 获取冲描述
   * @return string 冲描述，如(壬申)猴
   */
  public function getChongDesc()
  {
    return $this->getDayChongDesc();
  }

  /**
   * 获取日冲描述
   * @return string 日冲描述，如(壬申)猴
   */
  public function getDayChongDesc()
  {
    return '(' . $this->getDayChongGan() . $this->getDayChong() . ')' . $this->getDayChongShengXiao();
  }

  /**
   * 获取时冲描述
   * @return string 时冲描述，如(壬申)猴
   */
  public function getTimeChongDesc()
  {
    return '(' . $this->getTimeChongGan() . $this->getTimeChong() . ')' . $this->getTimeChongShengXiao();
  }

  /**
   * 获取煞
   * @return string 煞，如北
   */
  public function getSha()
  {
    return $this->getDaySha();
  }

  /**
   * 获取日煞
   * @return string 日煞，如北
   */
  public function getDaySha()
  {
    return LunarUtil::$SHA[$this->getDayZhi()];
  }

  /**
   * 获取时煞
   * @return string 时煞，如北
   */
  public function getTimeSha()
  {
    return LunarUtil::$SHA[$this->getTimeZhi()];
  }

  /**
   * 获取年纳音
   * @return string 年纳音，如剑锋金
   */
  public function getYearNaYin()
  {
    return LunarUtil::$NAYIN[$this->getYearInGanZhi()];
  }

  /**
   * 获取月纳音
   * @return string 月纳音，如剑锋金
   */
  public function getMonthNaYin()
  {
    return LunarUtil::$NAYIN[$this->getMonthInGanZhi()];
  }

  /**
   * 获取日纳音
   * @return string 日纳音，如剑锋金
   */
  public function getDayNaYin()
  {
    return LunarUtil::$NAYIN[$this->getDayInGanZhi()];
  }

  /**
   * 获取时辰纳音
   * @return string 时辰纳音，如剑锋金
   */
  public function getTimeNaYin()
  {
    return LunarUtil::$NAYIN[$this->getTimeInGanZhi()];
  }

  /**
   * 获取八字，男性也称乾造，女性也称坤造（以立春交接时刻作为新年的开始）
   * @return string[] 八字（男性也称乾造，女性也称坤造）
   */
  public function getBaZi()
  {
    $baZi = $this->getEightChar();
    $l = array();
    $l[] = $baZi->getYear();
    $l[] = $baZi->getMonth();
    $l[] = $baZi->getDay();
    $l[] = $baZi->getTime();
    return $l;
  }

  /**
   * 获取八字五行
   * @return string[] 八字五行
   */
  public function getBaZiWuXing()
  {
    $baZi = $this->getEightChar();
    $l = array();
    $l[] = $baZi->getYearWuXing();
    $l[] = $baZi->getMonthWuXing();
    $l[] = $baZi->getDayWuXing();
    $l[] = $baZi->getTimeWuXing();
    return $l;
  }

  /**
   * 获取八字纳音
   * @return string[] 八字纳音
   */
  public function getBaZiNaYin()
  {
    $baZi = $this->getEightChar();
    $l = array();
    $l[] = $baZi->getYearNaYin();
    $l[] = $baZi->getMonthNaYin();
    $l[] = $baZi->getDayNaYin();
    $l[] = $baZi->getTimeNaYin();
    return $l;
  }

  /**
   * 获取八字天干十神，日柱十神为日主，其余三柱根据天干十神表查询
   * @return string[] 八字天干十神
   */
  public function getBaZiShiShenGan()
  {
    $baZi = $this->getEightChar();
    $l = array();
    $l[] = $baZi->getYearShiShenGan();
    $l[] = $baZi->getMonthShiShenGan();
    $l[] = $baZi->getDayShiShenGan();
    $l[] = $baZi->getTimeShiShenGan();
    return $l;
  }

  /**
   * 获取八字地支十神，根据地支十神表查询
   * @return string[] 八字地支十神
   */
  public function getBaZiShiShenZhi()
  {
    $baZi = $this->getEightChar();
    $yearShiShenZhi = $baZi->getYearShiShenZhi();
    $monthShiShenZhi = $baZi->getMonthShiShenZhi();
    $dayShiShenZhi = $baZi->getDayShiShenZhi();
    $timeShiShenZhi = $baZi->getTimeShiShenZhi();
    $l = array();
    $l[] = $yearShiShenZhi[0];
    $l[] = $monthShiShenZhi[0];
    $l[] = $dayShiShenZhi[0];
    $l[] = $timeShiShenZhi[0];
    return $l;
  }

  /**
   * 获取八字年支十神
   * @return string[] 八字年支十神
   */
  public function getBaZiShiShenYearZhi()
  {
    return $this->getEightChar()->getYearShiShenZhi();
  }

  /**
   * 获取八字月支十神
   * @return string[] 八字月支十神
   */
  public function getBaZiShiShenMonthZhi()
  {
    return $this->getEightChar()->getMonthShiShenZhi();
  }

  /**
   * 获取八字日支十神
   * @return string[] 八字日支十神
   */
  public function getBaZiShiShenDayZhi()
  {
    return $this->getEightChar()->getDayShiShenZhi();
  }

  /**
   * 获取八字时支十神
   * @return string[] 八字时支十神
   */
  public function getBaZiShiShenTimeZhi()
  {
    return $this->getEightChar()->getTimeShiShenZhi();
  }

  /**
   * 获取十二执星：建、除、满、平、定、执、破、危、成、收、开、闭。当月支与日支相同即为建，依次类推
   * @return string 执星
   */
  public function getZhiXing()
  {
    $offset = $this->dayZhiIndex - $this->monthZhiIndex;
    if ($offset < 0) {
      $offset += 12;
    }
    return LunarUtil::$ZHI_XING[$offset + 1];
  }

  /**
   * 获取值日天神
   * @return string 值日天神
   */
  public function getDayTianShen()
  {
    $monthZhi = $this->getMonthZhi();
    $offset = LunarUtil::$ZHI_TIAN_SHEN_OFFSET[$monthZhi];
    return LunarUtil::$TIAN_SHEN[($this->dayZhiIndex + $offset) % 12 + 1];
  }

  /**
   * 获取值时天神
   * @return string 值时天神
   */
  public function getTimeTianShen()
  {
    $dayZhi = $this->getDayZhiExact();
    $offset = LunarUtil::$ZHI_TIAN_SHEN_OFFSET[$dayZhi];
    return LunarUtil::$TIAN_SHEN[($this->timeZhiIndex + $offset) % 12 + 1];
  }

  /**
   * 获取值日天神类型：黄道/黑道
   * @return string 值日天神类型：黄道/黑道
   */
  public function getDayTianShenType()
  {
    return LunarUtil::$TIAN_SHEN_TYPE[$this->getDayTianShen()];
  }

  /**
   * 获取值时天神类型：黄道/黑道
   * @return string 值时天神类型：黄道/黑道
   */
  public function getTimeTianShenType()
  {
    return LunarUtil::$TIAN_SHEN_TYPE[$this->getTimeTianShen()];
  }

  /**
   * 获取值日天神吉凶
   * @return string 吉/凶
   */
  public function getDayTianShenLuck()
  {
    return LunarUtil::$TIAN_SHEN_TYPE_LUCK[$this->getDayTianShenType()];
  }

  /**
   * 获取值时天神吉凶
   * @return string 吉/凶
   */
  public function getTimeTianShenLuck()
  {
    return LunarUtil::$TIAN_SHEN_TYPE_LUCK[$this->getTimeTianShenType()];
  }

  /**
   * 获取逐日胎神方位
   * @return string 逐日胎神方位
   */
  public function getDayPositionTai()
  {
    return LunarUtil::$POSITION_TAI_DAY[LunarUtil::getJiaZiIndex($this->getDayInGanZhi())];
  }

  /**
   * 获取逐月胎神方位，闰月无
   * @return string 逐月胎神方位
   */
  public function getMonthPositionTai()
  {
    if ($this->month < 0) {
      return '';
    }
    return LunarUtil::$POSITION_TAI_MONTH[$this->month - 1];
  }

  /**
   * 获取每日宜
   * @return string[] 宜
   */
  public function getDayYi()
  {
    return LunarUtil::getDayYi($this->getMonthInGanZhiExact(), $this->getDayInGanZhi());
  }

  /**
   * 获取时宜
   * @return string[] 宜
   */
  public function getTimeYi()
  {
    return LunarUtil::getTimeYi($this->getDayInGanZhiExact(), $this->getTimeInGanZhi());
  }

  /**
   * 获取每日忌
   * @return string[] 忌
   */
  public function getDayJi()
  {
    return LunarUtil::getDayJi($this->getMonthInGanZhiExact(), $this->getDayInGanZhi());
  }

  /**
   * 获取时忌
   * @return string[] 忌
   */
  public function getTimeJi()
  {
    return LunarUtil::getTimeJi($this->getDayInGanZhiExact(), $this->getTimeInGanZhi());
  }

  /**
   * 获取日吉神（宜趋），如果没有，返回['无']
   * @return string[] 吉神
   */
  public function getDayJiShen()
  {
    return LunarUtil::getDayJiShen($this->getMonth(), $this->getDayInGanZhi());
  }

  /**
   * 获取日凶煞（宜忌），如果没有，返回['无']
   * @return string[] 凶煞
   */
  public function getDayXiongSha()
  {
    return LunarUtil::getDayXiongSha($this->getMonth(), $this->getDayInGanZhi());
  }

  /**
   * 获取月相
   * @return string 月相
   */
  public function getYueXiang()
  {
    return LunarUtil::$YUE_XIANG[$this->getDay()];
  }

  protected function _getYearNineStar($yearInGanZhi)
  {
    $index = LunarUtil::getJiaZiIndex($yearInGanZhi) + 1;
    $yearOffset = 0;
    if ($index != LunarUtil::getJiaZiIndex($this->getYearInGanZhi()) + 1) {
      $yearOffset = -1;
    }
    $yuan = (int)(($this->year + $yearOffset + 2696) / 60) % 3;
    $offset = (62 + $yuan * 3 - $index) % 9;
    if (0 === $offset) {
      $offset = 9;
    }
    return NineStar::fromIndex($offset - 1);
  }

  public function getYearNineStarBySect($sect)
  {
    switch ($sect) {
      case 1:
        $yearInGanZhi = $this->getYearInGanZhi();
        break;
      case 3:
        $yearInGanZhi = $this->getYearInGanZhiExact();
        break;
      default:
        $yearInGanZhi = $this->getYearInGanZhiByLiChun();
    }
    return $this->_getYearNineStar($yearInGanZhi);
  }

  /**
   * 获取值年九星（流年紫白星起例歌诀：年上吉星论甲子，逐年星逆中宫起；上中下作三元汇，一上四中七下兑。）
   * @return NineStar 值年九星
   */
  public function getYearNineStar()
  {
    return $this->getYearNineStarBySect(2);
  }

  public function _getMonthNineStar($yearZhiIndex, $monthZhiIndex)
  {
    $index = $yearZhiIndex % 3;
    $n = 27 - ($index * 3);
    if ($monthZhiIndex < LunarUtil::$BASE_MONTH_ZHI_INDEX) {
      $n -= 3;
    }
    $offset = ($n - $monthZhiIndex) % 9;
    return NineStar::fromIndex($offset);
  }

  public function getMonthNineStarBySect($sect)
  {
    switch ($sect) {
      case 1:
        $yearZhiIndex = $this->yearZhiIndex;
        $monthZhiIndex = $this->monthZhiIndex;
        break;
      case 3:
        $yearZhiIndex = $this->yearZhiIndexExact;
        $monthZhiIndex = $this->monthZhiIndexExact;
        break;
      default:
        $yearZhiIndex = $this->yearZhiIndexByLiChun;
        $monthZhiIndex = $this->monthZhiIndex;
    }
    return $this->_getMonthNineStar($yearZhiIndex, $monthZhiIndex);
  }

  /**
   * 获取值月九星（月紫白星歌诀：子午卯酉八白起，寅申巳亥二黑求，辰戌丑未五黄中。）
   * @return NineStar 值月九星
   */
  public function getMonthNineStar()
  {
    return $this->getMonthNineStarBySect(2);
  }

  /**
   * 获取值日九星（日家紫白星歌诀：日家白法不难求，二十四气六宫周；冬至雨水及谷雨，阳顺一七四中游；夏至处暑霜降后，九三六星逆行求。）
   * @return NineStar 值日九星
   */
  public function getDayNineStar()
  {
    $solarYmd = $this->solar->toYmd();
    $dongZhi = $this->jieQi['冬至'];
    $dongZhi2 = $this->jieQi['DONG_ZHI'];
    $xiaZhi = $this->jieQi['夏至'];
    $dongZhiIndex = LunarUtil::getJiaZiIndex($dongZhi->getLunar()->getDayInGanZhi());
    $dongZhiIndex2 = LunarUtil::getJiaZiIndex($dongZhi2->getLunar()->getDayInGanZhi());
    $xiaZhiIndex = LunarUtil::getJiaZiIndex($xiaZhi->getLunar()->getDayInGanZhi());
    if ($dongZhiIndex > 29) {
      $solarShunBai = $dongZhi->next(60 - $dongZhiIndex);
    } else {
      $solarShunBai = $dongZhi->next(-$dongZhiIndex);
    }
    $solarShunBaiYmd = $solarShunBai->toYmd();
    if ($dongZhiIndex2 > 29) {
      $solarShunBai2 = $dongZhi2->next(60 - $dongZhiIndex2);
    } else {
      $solarShunBai2 = $dongZhi2->next(-$dongZhiIndex2);
    }
    $solarShunBaiYmd2 = $solarShunBai2->toYmd();
    if ($xiaZhiIndex > 29) {
      $solarNiZi = $xiaZhi->next(60 - $xiaZhiIndex);
    } else {
      $solarNiZi = $xiaZhi->next(-$xiaZhiIndex);
    }
    $solarNiZiYmd = $solarNiZi->toYmd();
    $offset = 0;
    if (strcmp($solarYmd, $solarShunBaiYmd) >= 0 && strcmp($solarYmd, $solarNiZiYmd) < 0) {
      $offset = ExactDate::getDaysBetweenDate($solarShunBai->getCalendar(), $this->getSolar()->getCalendar()) % 9;
    } else if (strcmp($solarYmd, $solarNiZiYmd) >= 0 && strcmp($solarYmd, $solarShunBaiYmd2) < 0) {
      $offset = 8 - (ExactDate::getDaysBetweenDate($solarNiZi->getCalendar(), $this->getSolar()->getCalendar()) % 9);
    } else if (strcmp($solarYmd, $solarShunBaiYmd2) >= 0) {
      $offset = ExactDate::getDaysBetweenDate($solarShunBai2->getCalendar(), $this->getSolar()->getCalendar()) % 9;
    } else if (strcmp($solarYmd, $solarShunBaiYmd) < 0) {
      $offset = (8 + ExactDate::getDaysBetweenDate($this->getSolar()->getCalendar(), $solarShunBai->getCalendar())) % 9;
    }
    return NineStar::fromIndex($offset);
  }

  /**
   * 获取值时九星（时家紫白星歌诀：三元时白最为佳，冬至阳生顺莫差，孟日七宫仲一白，季日四绿发萌芽，每把时辰起甲子，本时星耀照光华，时星移入中宫去，顺飞八方逐细查。夏至阴生逆回首，孟归三碧季加六，仲在九宫时起甲，依然掌中逆轮跨。）
   * @return NineStar 值时九星
   */
  public function getTimeNineStar()
  {
    //顺逆
    $solarYmd = $this->solar->toYmd();
    $asc = false;
    if (strcmp($solarYmd, $this->jieQi['冬至']->toYmd()) >= 0 && strcmp($solarYmd, $this->jieQi['夏至']->toYmd()) < 0) {
      $asc = true;
    } else if (strcmp($solarYmd, $this->jieQi['DONG_ZHI']->toYmd()) >= 0) {
      $asc = true;
    }
    $start = $asc ? 6 : 2;
    $dayZhi = $this->getDayZhi();
    if (strpos('子午卯酉', $dayZhi) !== false) {
      $start = $asc ? 0 : 8;
    } else if (strpos('辰戌丑未', $dayZhi) !== false) {
      $start = $asc ? 3 : 5;
    }
    $index = $asc ? $start + $this->timeZhiIndex : $start + 9 - $this->timeZhiIndex;
    return NineStar::fromIndex($index % 9);
  }

  /**
   * 获取节气表（节气名称:阳历），节气交接时刻精确到秒，以冬至开头，按先后顺序排列
   * @return Solar[] 节气表
   */
  public function getJieQiTable()
  {
    return $this->jieQi;
  }

  /**
   * 获取最近的节气，如果未找到匹配的，返回null
   * @param $forward bool 是否顺推，true为顺推，false为逆推
   * @param $conditions array|null 过滤条件，如果设置过滤条件，仅返回匹配该名称的
   * @param $wholeDay bool 是否按天计
   * @return JieQi|null 节气
   */
  protected function getNearJieQi($forward, $conditions, $wholeDay)
  {
    $name = null;
    $near = null;
    $filter = null != $conditions && count($conditions) > 0;
    $today = $wholeDay ? $this->solar->toYmd() : $this->solar->toYmdHms();
    foreach ($this->jieQi as $key => $solar) {
      $jq = $this->convertJieQi($key);
      if ($filter) {
        if (!in_array($jq, $conditions)) {
          continue;
        }
      }
      $day = $wholeDay ? $solar->toYmd() : $solar->toYmdHms();
      if ($forward) {
        if (strcmp($day, $today) < 0) {
          continue;
        }
        if (null == $near) {
          $name = $jq;
          $near = $solar;
        } else {
          $nearDay = $wholeDay ? $near->toYmd() : $near->toYmdHms();
          if (strcmp($day, $nearDay) < 0) {
            $name = $jq;
            $near = $solar;
          }
        }
      } else {
        if (strcmp($day, $today) > 0) {
          continue;
        }
        if (null == $near) {
          $name = $jq;
          $near = $solar;
        } else {
          $nearDay = $wholeDay ? $near->toYmd() : $near->toYmdHms();
          if (strcmp($day, $nearDay) > 0) {
            $name = $jq;
            $near = $solar;
          }
        }
      }
    }
    if (null == $near) {
      return null;
    }
    return new JieQi($name, $near);
  }

  public function getNextJieByWholeDay($wholeDay)
  {
    $conditions = array();
    for ($i = 0, $j = count(Lunar::$JIE_QI_IN_USE) / 2; $i < $j; $i++) {
      $conditions[] = Lunar::$JIE_QI_IN_USE[$i * 2];
    }
    return $this->getNearJieQi(true, $conditions, $wholeDay);
  }

  /**
   * 获取下一节（顺推的第一个节）
   * @return JieQi|null 节气
   */
  public function getNextJie()
  {
    return $this->getNextJieByWholeDay(false);
  }

  public function getPrevJieByWholeDay($wholeDay)
  {
    $conditions = array();
    for ($i = 0, $j = count(Lunar::$JIE_QI_IN_USE) / 2; $i < $j; $i++) {
      $conditions[] = Lunar::$JIE_QI_IN_USE[$i * 2];
    }
    return $this->getNearJieQi(false, $conditions, $wholeDay);
  }

  /**
   * 获取上一节（逆推的第一个节）
   * @return JieQi|null 节气
   */
  public function getPrevJie()
  {
    return $this->getPrevJieByWholeDay(false);
  }

  public function getNextQiByWholeDay($wholeDay)
  {
    $conditions = array();
    for ($i = 0, $j = count(Lunar::$JIE_QI_IN_USE) / 2; $i < $j; $i++) {
      $conditions[] = Lunar::$JIE_QI_IN_USE[$i * 2 + 1];
    }
    return $this->getNearJieQi(true, $conditions, $wholeDay);
  }

  /**
   * 获取下一气令（顺推的第一个气令）
   * @return JieQi|null 节气
   */
  public function getNextQi()
  {
    return $this->getNextQiByWholeDay(false);
  }

  public function getPrevQiByWholeDay($wholeDay)
  {
    $conditions = array();
    for ($i = 0, $j = count(Lunar::$JIE_QI_IN_USE) / 2; $i < $j; $i++) {
      $conditions[] = Lunar::$JIE_QI_IN_USE[$i * 2 + 1];
    }
    return $this->getNearJieQi(false, $conditions, $wholeDay);
  }

  /**
   * 获取上一气令（逆推的第一个气令）
   * @return JieQi|null 节气
   */
  public function getPrevQi()
  {
    return $this->getPrevQiByWholeDay(false);
  }

  public function getNextJieQiByWholeDay($wholeDay)
  {
    return $this->getNearJieQi(true, null, $wholeDay);
  }

  /**
   * 获取下一节气（顺推的第一个节气）
   * @return JieQi|null 节气
   */
  public function getNextJieQi()
  {
    return $this->getNextJieQiByWholeDay(false);
  }

  public function getPrevJieQiByWholeDay($wholeDay)
  {
    return $this->getNearJieQi(false, null, $wholeDay);
  }

  /**
   * 获取上一节气（逆推的第一个节气）
   * @return JieQi|null 节气
   */
  public function getPrevJieQi()
  {
    return $this->getPrevJieQiByWholeDay(false);
  }

  /**
   * 获取节气名称，如果无节气，返回空字符串
   * @return string 节气名称
   */
  public function getJieQi()
  {
    foreach ($this->jieQi as $key => $d) {
      if ($d->getYear() === $this->solar->getYear() && $d->getMonth() === $this->solar->getMonth() && $d->getDay() === $this->solar->getDay()) {
        return $this->convertJieQi($key);
      }
    }
    return '';
  }

  /**
   * 获取当天节气对象，如果无节气，返回null
   * @return JieQi|null 节气对象
   */
  public function getCurrentJieQi()
  {
    foreach ($this->jieQi as $key => $d) {
      if ($d->getYear() === $this->solar->getYear() && $d->getMonth() === $this->solar->getMonth() && $d->getDay() === $this->solar->getDay()) {
        return new JieQi($key, $d);
      }
    }
    return null;
  }

  /**
   * 获取当天节令对象，如果无节令，返回null
   * @return JieQi|null 节气对象
   */
  public function getCurrentJie()
  {
    for ($i = 0, $j = count(Lunar::$JIE_QI_IN_USE); $i < $j; $i += 2) {
      $key = Lunar::$JIE_QI_IN_USE[$i];
      $d = $this->jieQi[$key];
      if ($d->getYear() === $this->solar->getYear() && $d->getMonth() === $this->solar->getMonth() && $d->getDay() === $this->solar->getDay()) {
        return new JieQi($key, $d);
      }
    }
    return null;
  }

  /**
   * 获取当天气令对象，如果无气令，返回null
   * @return JieQi|null 节气对象
   */
  public function getCurrentQi()
  {
    for ($i = 1, $j = count(Lunar::$JIE_QI_IN_USE); $i < $j; $i += 2) {
      $key = Lunar::$JIE_QI_IN_USE[$i];
      $d = $this->jieQi[$key];
      if ($d->getYear() === $this->solar->getYear() && $d->getMonth() === $this->solar->getMonth() && $d->getDay() === $this->solar->getDay()) {
        return new JieQi($key, $d);
      }
    }
    return null;
  }

  public function getTimeGanIndex()
  {
    return $this->timeGanIndex;
  }

  public function getTimeZhiIndex()
  {
    return $this->timeZhiIndex;
  }

  public function getDayGanIndex()
  {
    return $this->dayGanIndex;
  }

  public function getDayZhiIndex()
  {
    return $this->dayZhiIndex;
  }

  public function getMonthGanIndex()
  {
    return $this->monthGanIndex;
  }

  public function getMonthZhiIndex()
  {
    return $this->monthZhiIndex;
  }

  public function getYearGanIndex()
  {
    return $this->yearGanIndex;
  }

  public function getYearZhiIndex()
  {
    return $this->yearZhiIndex;
  }

  public function getYearGanIndexByLiChun()
  {
    return $this->yearGanIndexByLiChun;
  }

  public function getYearZhiIndexByLiChun()
  {
    return $this->yearZhiIndexByLiChun;
  }

  public function getDayGanIndexExact()
  {
    return $this->dayGanIndexExact;
  }

  public function getDayGanIndexExact2()
  {
    return $this->dayGanIndexExact2;
  }

  public function getDayZhiIndexExact()
  {
    return $this->dayZhiIndexExact;
  }

  public function getDayZhiIndexExact2()
  {
    return $this->dayZhiIndexExact2;
  }

  public function getMonthGanIndexExact()
  {
    return $this->monthGanIndexExact;
  }

  public function getMonthZhiIndexExact()
  {
    return $this->monthZhiIndexExact;
  }

  public function getYearGanIndexExact()
  {
    return $this->yearGanIndexExact;
  }

  public function getYearZhiIndexExact()
  {
    return $this->yearZhiIndexExact;
  }

  public function getEightChar()
  {
    if (null == $this->eightChar) {
      $this->eightChar = EightChar::fromLunar($this);
    }
    return $this->eightChar;
  }

  /**
   * 获取往后推几天的农历日期，如果要往前推，则天数用负数
   * @param int days 天数
   * @return Lunar 农历日期
   */
  public function next($days)
  {
    return $this->solar->next($days)->getLunar();
  }

  /**
   * @return string
   */
  public function toFullString()
  {
    $s = '';
    $s .= $this;
    $s .= ' ';
    $s .= $this->getYearInGanZhi();
    $s .= '(';
    $s .= $this->getYearShengXiao();
    $s .= ')年 ';
    $s .= $this->getMonthInGanZhi();
    $s .= '(';
    $s .= $this->getMonthShengXiao();
    $s .= ')月 ';
    $s .= $this->getDayInGanZhi();
    $s .= '(';
    $s .= $this->getDayShengXiao();
    $s .= ')日 ';
    $s .= $this->getTimeZhi();
    $s .= '(';
    $s .= $this->getTimeShengXiao();
    $s .= ')时 纳音[';
    $s .= $this->getYearNaYin();
    $s .= ' ';
    $s .= $this->getMonthNaYin();
    $s .= ' ';
    $s .= $this->getDayNaYin();
    $s .= ' ';
    $s .= $this->getTimeNaYin();
    $s .= '] 星期';
    $s .= $this->getWeekInChinese();
    foreach ($this->getFestivals() as $f) {
      $s .= ' (' . $f . ')';
    }
    foreach ($this->getOtherFestivals() as $f) {
      $s .= ' (' . $f . ')';
    }
    $jq = $this->getJieQi();
    if (strlen($jq) > 0) {
      $s .= ' (' . $jq . ')';
    }
    $s .= ' ';
    $s .= $this->getGong();
    $s .= '方';
    $s .= $this->getShou();
    $s .= ' 星宿[';
    $s .= $this->getXiu();
    $s .= $this->getZheng();
    $s .= $this->getAnimal();
    $s .= '](';
    $s .= $this->getXiuLuck();
    $s .= ') 彭祖百忌[';
    $s .= $this->getPengZuGan();
    $s .= ' ';
    $s .= $this->getPengZuZhi();
    $s .= '] 喜神方位[';
    $s .= $this->getDayPositionXi();
    $s .= '](';
    $s .= $this->getDayPositionXiDesc();
    $s .= ') 阳贵神方位[';
    $s .= $this->getDayPositionYangGui();
    $s .= '](';
    $s .= $this->getDayPositionYangGuiDesc();
    $s .= ') 阴贵神方位[';
    $s .= $this->getDayPositionYinGui();
    $s .= '](';
    $s .= $this->getDayPositionYinGuiDesc();
    $s .= ') 福神方位[';
    $s .= $this->getDayPositionFu();
    $s .= '](';
    $s .= $this->getDayPositionFuDesc();
    $s .= ') 财神方位[';
    $s .= $this->getDayPositionCai();
    $s .= '](';
    $s .= $this->getDayPositionCaiDesc();
    $s .= ') 冲[';
    $s .= $this->getChongDesc();
    $s .= '] 煞[';
    $s .= $this->getSha();
    $s .= ']';
    return $s;
  }

  /**
   * @return string
   */
  public function toString()
  {
    return $this->getYearInChinese() . '年' . $this->getMonthInChinese() . '月' . $this->getDayInChinese();
  }

  public function __toString()
  {
    return $this->toString();
  }

  /**
   * 获取年所在旬（以正月初一作为新年的开始）
   * @return string 旬
   */
  public function getYearXun()
  {
    return LunarUtil::getXun($this->getYearInGanZhi());
  }

  /**
   * 获取年所在旬（以立春当天作为新年的开始）
   * @return string 旬
   */
  public function getYearXunByLiChun()
  {
    return LunarUtil::getXun($this->getYearInGanZhiByLiChun());
  }

  /**
   * 获取年所在旬（以立春交接时刻作为新年的开始）
   * @return string 旬
   */
  public function getYearXunExact()
  {
    return LunarUtil::getXun($this->getYearInGanZhiExact());
  }

  /**
   * 获取值年空亡（以正月初一作为新年的开始）
   * @return string 空亡(旬空)
   */
  public function getYearXunKong()
  {
    return LunarUtil::getXunKong($this->getYearInGanZhi());
  }

  /**
   * 获取值年空亡（以立春当天作为新年的开始）
   * @return string 空亡(旬空)
   */
  public function getYearXunKongByLiChun()
  {
    return LunarUtil::getXunKong($this->getYearInGanZhiByLiChun());
  }

  /**
   * 获取值年空亡（以立春交接时刻作为新年的开始）
   * @return string 空亡(旬空)
   */
  public function getYearXunKongExact()
  {
    return LunarUtil::getXunKong($this->getYearInGanZhiExact());
  }

  /**
   * 获取月所在旬（以节交接当天起算）
   * @return string 旬
   */
  public function getMonthXun()
  {
    return LunarUtil::getXun($this->getMonthInGanZhi());
  }

  /**
   * 获取月所在旬（以节交接时刻起算）
   * @return string 旬
   */
  public function getMonthXunExact()
  {
    return LunarUtil::getXun($this->getMonthInGanZhiExact());
  }

  /**
   * 获取值月空亡（以节交接当天起算）
   * @return string 空亡(旬空)
   */
  public function getMonthXunKong()
  {
    return LunarUtil::getXunKong($this->getMonthInGanZhi());
  }

  /**
   * 获取值月空亡（以节交接时刻起算）
   * @return string 空亡(旬空)
   */
  public function getMonthXunKongExact()
  {
    return LunarUtil::getXunKong($this->getMonthInGanZhiExact());
  }

  /**
   * 获取日所在旬（以节交接当天起算）
   * @return string 旬
   */
  public function getDayXun()
  {
    return LunarUtil::getXun($this->getDayInGanZhi());
  }

  /**
   * 获取日所在旬（八字流派1，晚子时日柱算明天）
   * @return string 旬
   */
  public function getDayXunExact()
  {
    return LunarUtil::getXun($this->getDayInGanZhiExact());
  }

  /**
   * 获取日所在旬（八字流派2，晚子时日柱算当天）
   * @return string 旬
   */
  public function getDayXunExact2()
  {
    return LunarUtil::getXun($this->getDayInGanZhiExact2());
  }

  /**
   * 获取值日空亡
   * @return string 空亡(旬空)
   */
  public function getDayXunKong()
  {
    return LunarUtil::getXunKong($this->getDayInGanZhi());
  }

  /**
   * 获取值日空亡（八字流派1，晚子时日柱算明天）
   * @return string 空亡(旬空)
   */
  public function getDayXunKongExact()
  {
    return LunarUtil::getXunKong($this->getDayInGanZhiExact());
  }

  /**
   * 获取值日空亡（八字流派2，晚子时日柱算当天）
   * @return string 空亡(旬空)
   */
  public function getDayXunKongExact2()
  {
    return LunarUtil::getXunKong($this->getDayInGanZhiExact2());
  }

  /**
   * 获取时辰所在旬
   * @return string 旬
   */
  public function getTimeXun()
  {
    return LunarUtil::getXun($this->getTimeInGanZhi());
  }

  /**
   * 获取值时空亡
   * @return string 空亡(旬空)
   */
  public function getTimeXunKong()
  {
    return LunarUtil::getXunKong($this->getTimeInGanZhi());
  }

  public function getShuJiu()
  {
    $currentCalendar = ExactDate::fromYmd($this->solar->getYear(), $this->solar->getMonth(), $this->solar->getDay());
    $start = $this->jieQi['DONG_ZHI'];
    $startCalendar = ExactDate::fromYmd($start->getYear(), $start->getMonth(), $start->getDay());
    if ($currentCalendar < $startCalendar) {
      $start = $this->jieQi['冬至'];
      $startCalendar = ExactDate::fromYmd($start->getYear(), $start->getMonth(), $start->getDay());
    }
    $endCalendar = ExactDate::fromYmd($start->getYear(), $start->getMonth(), $start->getDay());
    $endCalendar->modify('+81 day');
    if ($currentCalendar < $startCalendar || $currentCalendar >= $endCalendar) {
      return null;
    }
    $days = ExactDate::getDaysBetweenDate($startCalendar, $currentCalendar);
    return new ShuJiu(LunarUtil::$NUMBER[intval($days / 9) + 1] . '九', $days % 9 + 1);
  }

  public function getFu()
  {
    $currentCalendar = ExactDate::fromYmd($this->solar->getYear(), $this->solar->getMonth(), $this->solar->getDay());
    $xiaZhi = $this->jieQi['夏至'];
    $liQiu = $this->jieQi['立秋'];
    $startCalendar = ExactDate::fromYmd($xiaZhi->getYear(), $xiaZhi->getMonth(), $xiaZhi->getDay());
    $add = 6 - $xiaZhi->getLunar()->getDayGanIndex();
    if ($add < 0) {
      $add += 10;
    }
    $add += 20;
    $startCalendar->modify('+' . $add . ' day');
    if ($currentCalendar < $startCalendar) {
      return null;
    }
    $days = ExactDate::getDaysBetweenDate($startCalendar, $currentCalendar);
    if ($days < 10) {
      return new Fu('初伏', $days + 1);
    }
    $startCalendar->modify('+10 day');
    $days = ExactDate::getDaysBetweenDate($startCalendar, $currentCalendar);
    if ($days < 10) {
      return new Fu('中伏', $days + 1);
    }
    $startCalendar->modify('+10 day');
    $days = ExactDate::getDaysBetweenDate($startCalendar, $currentCalendar);
    $liQiuCalendar = ExactDate::fromYmd($liQiu->getYear(), $liQiu->getMonth(), $liQiu->getDay());
    if ($liQiuCalendar <= $startCalendar) {
      if ($days < 10) {
        return new Fu('末伏', $days + 1);
      }
    } else {
      if ($days < 10) {
        return new Fu('中伏', $days + 11);
      }
      $startCalendar->modify('+10 day');
      $days = ExactDate::getDaysBetweenDate($startCalendar, $currentCalendar);
      if ($days < 10) {
        return new Fu('末伏', $days + 1);
      }
    }
    return null;
  }

  /**
   * 获取六曜
   * @return string 六曜
   */
  public function getLiuYao()
  {
    return LunarUtil::$LIU_YAO[(abs($this->month) + $this->day - 2) % 6];
  }

  /**
   * 获取物候
   * @return string 物候
   */
  public function getWuHou()
  {
    $jieQi = $this->getPrevJieQiByWholeDay(true);
    $name = $jieQi->getName();
    $offset = 0;
    for ($i = 0, $j = count(Lunar::$JIE_QI); $i < $j; $i++) {
      if (strcmp($name, Lunar::$JIE_QI[$i]) === 0) {
        $offset = $i;
        break;
      }
    }
    $currentCalendar = ExactDate::fromYmd($this->solar->getYear(), $this->solar->getMonth(), $this->solar->getDay());
    $startSolar = $jieQi->getSolar();
    $startCalendar = ExactDate::fromYmd($startSolar->getYear(), $startSolar->getMonth(), $startSolar->getDay());
    $days = ExactDate::getDaysBetweenDate($startCalendar, $currentCalendar);
    return LunarUtil::$WU_HOU[($offset * 3 + floor($days / 5)) % count(LunarUtil::$WU_HOU)];
  }

  public function getHou()
  {
    $jieQi = $this->getPrevJieQiByWholeDay(true);
    $startSolar = $jieQi->getSolar();
    $days = ExactDate::getDaysBetween($startSolar->getYear(), $startSolar->getMonth(), $startSolar->getDay(), $this->solar->getYear(), $this->solar->getMonth(), $this->solar->getDay());
    $max = count(LunarUtil::$HOU) - 1;
    $offset = floor($days / 5);
    if ($offset > $max) {
      $offset = $max;
    }
    return $jieQi->getName() . ' ' . LunarUtil::$HOU[$offset];
  }

  public function getDayLu()
  {
    $gan = LunarUtil::$LU[$this->getDayGan()];
    $zhi = null;
    if (!empty(LunarUtil::$LU[$this->getDayZhi()])) {
      $zhi = LunarUtil::$LU[$this->getDayZhi()];
    }
    $lu = $gan . '命互禄';
    if (null != $zhi) {
      $lu .= ' ' . $zhi . '命进禄';
    }
    return $lu;
  }

  /**
   * 获取时辰
   * @return LunarTime 时辰
   */
  public function getTime()
  {
    return LunarTime::fromYmdHms($this->year, $this->month, $this->day, $this->hour, $this->minute, $this->second);
  }

  /**
   * 获取时辰列表
   * @return LunarTime[] 时辰列表
   */
  public function getTimes()
  {
    $l = array();
    $l[] = LunarTime::fromYmdHms($this->year, $this->month, $this->day, 0, 0, 0);
    for ($i = 0; $i < 12; $i++) {
      $l[] = LunarTime::fromYmdHms($this->year, $this->month, $this->day, ($i + 1) * 2 - 1, 0, 0);
    }
    return $l;
  }

  /**
   * 获取佛历
   * @return Foto 佛历
   */
  public function getFoto()
  {
    return Foto::fromLunar($this);
  }

  /**
   * 获取道历
   * @return Tao 道历
   */
  public function getTao()
  {
    return Tao::fromLunar($this);
  }

}
