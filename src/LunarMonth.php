<?php

namespace com\nlf\calendar;

use com\nlf\calendar\util\LunarUtil;

/**
 * 农历月
 * @package com\nlf\calendar
 */
class LunarMonth
{

  /**
   * 农历年
   * @var int
   */
  private $year;

  /**
   * 农历月：1-12，闰月为负数，如闰2月为-2
   * @var int
   */
  private $month;

  /**
   * 天数，大月30天，小月29天
   * @var int
   */
  private $dayCount;

  /**
   * 初一的儒略日
   * @var double
   */
  private $firstJulianDay;

  private $index;

  private $zhiIndex;

  function __construct($lunarYear, $lunarMonth, $dayCount, $firstJulianDay, $index)
  {
    $this->year = intval($lunarYear);
    $this->month = intval($lunarMonth);
    $this->dayCount = intval($dayCount);
    $this->firstJulianDay = $firstJulianDay;
    $this->index = $index;
    $this->zhiIndex = ($index - 1 + LunarUtil::$BASE_MONTH_ZHI_INDEX) % 12;
  }

  public function toString()
  {
    return $this->year . '.' . $this->month;
  }

  public function toFullString()
  {
    return $this->year . '年' . ($this->isLeap() ? '闰' : '') . abs($this->month) . '月(' . $this->dayCount . '天)';
  }

  public function __toString()
  {
    return $this->toString();
  }

  /**
   * 通过农历年月初始化
   * @param int $lunarYear 农历年
   * @param int $lunarMonth 农历月：1-12，闰月为负数，如闰2月为-2
   * @return LunarMonth
   */
  public static function fromYm($lunarYear, $lunarMonth)
  {
    return LunarYear::fromYear($lunarYear)->getMonth($lunarMonth);
  }

  /**
   * 获取农历年
   * @return int 获取农历年
   */
  public function getYear()
  {
    return $this->year;
  }

  /**
   * 获取农历月
   * @return int 农历月：1-12，闰月为负数，如闰2月为-2
   */
  public function getMonth()
  {
    return $this->month;
  }

  /**
   * 获取天数
   * @return int 天数
   */
  public function getDayCount()
  {
    return $this->dayCount;
  }

  /**
   * 获取初一的儒略日
   * @return double 获取初一的儒略日
   */
  public function getFirstJulianDay()
  {
    return $this->firstJulianDay;
  }

  public function getIndex()
  {
    return $this->index;
  }

  public function getZhiIndex()
  {
    return $this->zhiIndex;
  }

  public function getGanIndex()
  {
    $offset = (LunarYear::fromYear($this->year)->getGanIndex() + 1) % 5 * 2;
    return ($this->index - 1 + $offset) % 10;
  }

  public function getGan()
  {
    return LunarUtil::$GAN[$this->getGanIndex() + 1];
  }

  public function getZhi()
  {
    return LunarUtil::$ZHI[$this->getZhiIndex() + 1];
  }

  public function getGanZhi()
  {
    return $this->getGan() . $this->getZhi();
  }

  /**
   * 获取喜神方位
   * @return string 喜神方位，如艮
   */
  public function getPositionXi()
  {
    return LunarUtil::$POSITION_XI[$this->getGanIndex() + 1];
  }

  /**
   * 获取喜神方位描述
   * @return string 喜神方位描述，如东北
   */
  public function getPositionXiDesc()
  {
    return LunarUtil::$POSITION_DESC[$this->getPositionXi()];
  }

  /**
   * 获取阳贵神方位
   * @return string 阳贵神方位，如艮
   */
  public function getPositionYangGui()
  {
    return LunarUtil::$POSITION_YANG_GUI[$this->getGanIndex() + 1];
  }

  /**
   * 获取阳贵神方位描述
   * @return string 阳贵神方位描述，如东北
   */
  public function getPositionYangGuiDesc()
  {
    return LunarUtil::$POSITION_DESC[$this->getPositionYangGui()];
  }

  /**
   * 获取阴贵神方位
   * @return string 阴贵神方位，如艮
   */
  public function getPositionYinGui()
  {
    return LunarUtil::$POSITION_YIN_GUI[$this->getGanIndex() + 1];
  }

  /**
   * 获取阴贵神方位描述
   * @return string 阴贵神方位描述，如东北
   */
  public function getPositionYinGuiDesc()
  {
    return LunarUtil::$POSITION_DESC[$this->getPositionYinGui()];
  }

  /**
   * 获取福神方位，默认流派2
   * @return string 福神方位，如艮
   */
  public function getPositionFu()
  {
    return $this->getPositionFuBySect(2);
  }

  /**
   * 获取福神方位
   * @param int $sect 流派，可选1或2
   * @return string 福神方位，如艮
   */
  public function getPositionFuBySect($sect)
  {
    $fu = 1 == $sect ? LunarUtil::$POSITION_FU : LunarUtil::$POSITION_FU_2;
    return $fu[$this->getGanIndex() + 1];
  }

  /**
   * 获取福神方位描述，默认流派2
   * @return string 福神方位描述，如东北
   */
  public function getPositionFuDesc()
  {
    return $this->getPositionFuDescBySect(2);
  }

  /**
   * 获取福神方位描述
   * @param int $sect 流派，可选1或2
   * @return string 福神方位描述，如东北
   */
  public function getPositionFuDescBySect($sect)
  {
    return LunarUtil::$POSITION_DESC[$this->getPositionFuBySect($sect)];
  }

  /**
   * 获取财神方位
   * @return string 财神方位，如艮
   */
  public function getPositionCai()
  {
    return LunarUtil::$POSITION_CAI[$this->getGanIndex() + 1];
  }

  /**
   * 获取财神方位描述
   * @return string 财神方位描述，如东北
   */
  public function getPositionCaiDesc()
  {
    return LunarUtil::$POSITION_DESC[$this->getPositionCai()];
  }

  /**
   * 是否闰月
   * @return bool true/false
   */
  public function isLeap()
  {
    return $this->month < 0;
  }

  public function getPositionTaiSui()
  {
    $m = abs($this->month);
    switch ($m) {
      case 1:
      case 5:
      case 9:
        $p = '艮';
        break;
      case 3:
      case 7:
      case 11:
        $p = '坤';
        break;
      case 4:
      case 8:
      case 12:
        $p = '巽';
        break;
      default:
        $p = LunarUtil::$POSITION_GAN[Solar::fromJulianDay($this->getFirstJulianDay())->getLunar()->getMonthGanIndex()];
    }
    return $p;
  }

  public function getPositionTaiSuiDesc()
  {
    return LunarUtil::$POSITION_DESC[$this->getPositionTaiSui()];
  }

  public function getNineStar()
  {
    $index = LunarYear::fromYear($this->year)->getZhiIndex() % 3;
    $m = abs($this->month);
    $monthZhiIndex = (13 + $m) % 12;
    $n = 27 - ($index * 3);
    if ($monthZhiIndex < LunarUtil::$BASE_MONTH_ZHI_INDEX) {
      $n -= 3;
    }
    $offset = ($n - $monthZhiIndex) % 9;
    return NineStar::fromIndex($offset);
  }

  public function next($n)
  {
    if (0 == $n) {
      return LunarMonth::fromYm($this->year, $this->month);
    } else {
      $rest = abs($n);
      $ny = $this->year;
      $iy = $ny;
      $im = $this->month;
      $index = 0;
      $months = LunarYear::fromYear($ny)->getMonths();
      if ($n > 0) {
        while (true) {
          $size = count($months);
          for ($i = 0; $i < $size; $i++) {
            $m = $months[$i];
            if ($m->getYear() == $iy && $m->getMonth() == $im) {
              $index = $i;
              break;
            }
          }
          $more = $size - $index - 1;
          if ($rest < $more) {
            break;
          }
          $rest -= $more;
          $lastMonth = $months[$size - 1];
          $iy = $lastMonth->getYear();
          $im = $lastMonth->getMonth();
          $ny++;
          $months = LunarYear::fromYear($ny)->getMonths();
        }
        return $months[$index + $rest];
      } else {
        while (true) {
          $size = count($months);
          for ($i = 0; $i < $size; $i++) {
            $m = $months[$i];
            if ($m->getYear() == $iy && $m->getMonth() == $im) {
              $index = $i;
              break;
            }
          }
          if ($rest <= $index) {
            break;
          }
          $rest -= $index;
          $firstMonth = $months[0];
          $iy = $firstMonth->getYear();
          $im = $firstMonth->getMonth();
          $ny--;
          $months = LunarYear::fromYear($ny)->getMonths();
        }
        return $months[$index - $rest];
      }
    }
  }
}
