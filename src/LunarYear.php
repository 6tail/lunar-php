<?php

namespace com\nlf\calendar;

use com\nlf\calendar\util\LunarUtil;
use com\nlf\calendar\util\ShouXingUtil;

bcscale(12);

/**
 * 农历年
 * @package com\nlf\calendar
 */
class LunarYear
{

  /**
   * 元
   * @var string[]
   */
  public static $YUAN =  array('下', '上', '中');

  /**
   * 运
   * @var string[]
   */
  public static $YUN =  array('七', '八', '九', '一', '二', '三', '四', '五', '六');
  private static $LEAP_11 = array(75, 94, 170, 238, 265, 322, 389, 469, 553, 583, 610, 678, 735, 754, 773, 849, 887, 936, 1050, 1069, 1126, 1145, 1164, 1183, 1259, 1278, 1308, 1373, 1403, 1441, 1460, 1498, 1555, 1593, 1612, 1631, 1642, 2033, 2128, 2147, 2242, 2614, 2728, 2910, 3062, 3244, 3339, 3616, 3711, 3730, 3825, 4007, 4159, 4197, 4322, 4341, 4379, 4417, 4531, 4599, 4694, 4713, 4789, 4808, 4971, 5085, 5104, 5161, 5180, 5199, 5294, 5305, 5476, 5677, 5696, 5772, 5791, 5848, 5886, 6049, 6068, 6144, 6163, 6258, 6402, 6440, 6497, 6516, 6630, 6641, 6660, 6679, 6736, 6774, 6850, 6869, 6899, 6918, 6994, 7013, 7032, 7051, 7070, 7089, 7108, 7127, 7146, 7222, 7271, 7290, 7309, 7366, 7385, 7404, 7442, 7461, 7480, 7491, 7499, 7594, 7624, 7643, 7662, 7681, 7719, 7738, 7814, 7863, 7882, 7901, 7939, 7958, 7977, 7996, 8034, 8053, 8072, 8091, 8121, 8159, 8186, 8216, 8235, 8254, 8273, 8311, 8330, 8341, 8349, 8368, 8444, 8463, 8474, 8493, 8531, 8569, 8588, 8626, 8664, 8683, 8694, 8702, 8713, 8721, 8751, 8789, 8808, 8816, 8827, 8846, 8884, 8903, 8922, 8941, 8971, 9036, 9066, 9085, 9104, 9123, 9142, 9161, 9180, 9199, 9218, 9256, 9294, 9313, 9324, 9343, 9362, 9381, 9419, 9438, 9476, 9514, 9533, 9544, 9552, 9563, 9571, 9582, 9601, 9639, 9658, 9666, 9677, 9696, 9734, 9753, 9772, 9791, 9802, 9821, 9886, 9897, 9916, 9935, 9954, 9973, 9992);
  private static $LEAP_12 = array(37, 56, 113, 132, 151, 189, 208, 227, 246, 284, 303, 341, 360, 379, 417, 436, 458, 477, 496, 515, 534, 572, 591, 629, 648, 667, 697, 716, 792, 811, 830, 868, 906, 925, 944, 963, 982, 1001, 1020, 1039, 1058, 1088, 1153, 1202, 1221, 1240, 1297, 1335, 1392, 1411, 1422, 1430, 1517, 1525, 1536, 1574, 3358, 3472, 3806, 3988, 4751, 4941, 5066, 5123, 5275, 5343, 5438, 5457, 5495, 5533, 5552, 5715, 5810, 5829, 5905, 5924, 6421, 6535, 6793, 6812, 6888, 6907, 7002, 7184, 7260, 7279, 7374, 7556, 7746, 7757, 7776, 7833, 7852, 7871, 7966, 8015, 8110, 8129, 8148, 8224, 8243, 8338, 8406, 8425, 8482, 8501, 8520, 8558, 8596, 8607, 8615, 8645, 8740, 8778, 8835, 8865, 8930, 8960, 8979, 8998, 9017, 9055, 9074, 9093, 9112, 9150, 9188, 9237, 9275, 9332, 9351, 9370, 9408, 9427, 9446, 9457, 9465, 9495, 9560, 9590, 9628, 9647, 9685, 9715, 9742, 9780, 9810, 9818, 9829, 9848, 9867, 9905, 9924, 9943, 9962, 10000);
  private static $LEAP = array();
  private static $CACHE = array();

  /**
   * 年
   * @var int
   */
  private $year;

  /**
   * 天干序号
   * @var int
   */
  private $ganIndex;

  /**
   * 地支序号
   * @var int
   */
  private $zhiIndex;

  /**
   * 农历月们
   * @var LunarMonth[]
   */
  private $months = array();

  /**
   * 节气儒略日们
   * @var double[]
   */
  private $jieQiJulianDays = array();

  function __construct($lunarYear)
  {
    LunarYear::_initLeap();
    $this->year = $lunarYear;
    $offset = $lunarYear - 4;
    $yearGanIndex = $offset % 10;
    $yearZhiIndex = $offset % 12;
    if ($yearGanIndex < 0) {
      $yearGanIndex += 10;
    }
    if ($yearZhiIndex < 0) {
      $yearZhiIndex += 12;
    }
    $this->ganIndex = $yearGanIndex;
    $this->zhiIndex = $yearZhiIndex;
    $this->compute();
  }

  /**
   * 通过农历年初始化
   * @param int $lunarYear 农历年
   * @return LunarYear
   */
  public static function fromYear($lunarYear)
  {
    $key = '_' . $lunarYear;
    if (array_key_exists($key, LunarYear::$CACHE)) {
      $obj = LunarYear::$CACHE[$key];
    } else {
      $obj = new LunarYear($lunarYear);
      LunarYear::$CACHE[$key] = $obj;
    }
    return $obj;
  }

  private static function _initLeap()
  {
    if (empty(LunarYear::$LEAP)) {
      for ($i = 0, $j = count(LunarYear::$LEAP_11); $i < $j; $i++) {
        LunarYear::$LEAP['_' . LunarYear::$LEAP_11[$i]] = 13;
      }
      for ($i = 0, $j = count(LunarYear::$LEAP_12); $i < $j; $i++) {
        LunarYear::$LEAP['_' . LunarYear::$LEAP_12[$i]] = 14;
      }
    }
  }

  public function toString()
  {
    return $this->year . '';
  }

  public function __toString()
  {
    return $this->toString();
  }

  public function toFullString()
  {
    return $this->year . '年';
  }

  public function getYear()
  {
    return $this->year;
  }

  public function getGanIndex()
  {
    return $this->ganIndex;
  }

  public function getZhiIndex()
  {
    return $this->zhiIndex;
  }

  public function getGan()
  {
    return LunarUtil::$GAN[$this->ganIndex + 1];
  }

  public function getZhi()
  {
    return LunarUtil::$ZHI[$this->zhiIndex + 1];
  }

  public function getGanZhi()
  {
    return $this->getGan() . $this->getZhi();
  }

  /**
   * @return double[]
   */
  public function getJieQiJulianDays()
  {
    return $this->jieQiJulianDays;
  }

  /**
   * 获取本年的月份
   * @return LunarMonth[]
   */
  public function getMonths()
  {
    return $this->months;
  }

  /**
   * 获取农历月
   * @param int $lunarMonth 月，1-12，闰月为负数，如闰2月为-2
   * @return LunarMonth|null
   */
  public function getMonth($lunarMonth)
  {
    foreach ($this->months as $m) {
      if ($m->getYear() == $this->year && $m->getMonth() == $lunarMonth) {
        return $m;
      }
    }
    return null;
  }

  /**
   * 获取闰月
   * @return int 闰月数字，1代表闰1月，0代表无闰月
   */
  public function getLeapMonth()
  {
    foreach ($this->months as $m) {
      if ($m->getYear() == $this->year && $m->isLeap()) {
        return abs($m->getMonth());
      }
    }
    return 0;
  }

  private function compute()
  {
    // 节气(中午12点，长度27)
    $jq = array();
    // 合朔，即每月初一(中午12点，长度16)
    $hs = array();
    // 每月天数(长度15)
    $dayCounts = array();

    $currentYear = $this->year;
    $year = $currentYear - 2000;
    // 从上年的大雪到下年的立春
    for ($i = 0, $j = count(Lunar::$JIE_QI_IN_USE); $i < $j; $i++) {
      // 精确的节气
      $t = 36525 * ShouXingUtil::saLonT(($year + (17 + $i) * 15.0 / 360) * 2 * M_PI);
      $t += ShouXingUtil::$ONE_THIRD - ShouXingUtil::dtT($t);
      $this->jieQiJulianDays[] = $t + Solar::$J2000;
      // 按中午12点算的节气
      if ($i > 0 && $i < 28) {
        $jq[] = round($t);
      }
    }

    //冬至前的初一
    $w = ShouXingUtil::calcShuo($jq[0]);
    if ($w > $jq[0]) {
      $w -= 29.5306;
    }
    // 递推每月初一
    for ($i = 0; $i < 16; $i++) {
      $hs[] = ShouXingUtil::calcShuo($w + 29.5306 * $i);
    }
    // 每月天数
    for ($i = 0; $i < 15; $i++) {
      $dayCounts[] = (int)($hs[$i + 1] - $hs[$i]);
    }

    $currentYearLeap = -1;
    if (array_key_exists('_' . $currentYear, LunarYear::$LEAP)) {
      $currentYearLeap = LunarYear::$LEAP['_' . $currentYear];
    } else {
      if ($hs[13] <= $jq[24]) {
        $i = 1;
        while ($hs[$i + 1] > $jq[2 * $i] && $i < 13) {
          $i++;
        }
        $currentYearLeap = $i;
      }
    }

    $prevYear = $currentYear - 1;
    $prevYearLeap = -1;
    if (array_key_exists('_' . $prevYear, LunarYear::$LEAP)) {
      $prevYearLeap = LunarYear::$LEAP['_' . $prevYear] - 12;
    }

    $y = $prevYear;
    $m = 11;
    for ($i = 0, $j = count($dayCounts); $i < $j; $i++) {
      $cm = $m;
      $isNextLeap = false;
      if ($y == $currentYear && $i == $currentYearLeap) {
        $cm = -$cm;
      } else if ($y == $prevYear && $i == $prevYearLeap) {
        $cm = -$cm;
      }
      if ($y == $currentYear && $i + 1 == $currentYearLeap) {
        $isNextLeap = true;
      } else if ($y == $prevYear && $i + 1 == $prevYearLeap) {
        $isNextLeap = true;
      }
      $this->months[] = new LunarMonth($y, $cm, $dayCounts[$i], $hs[$i] + Solar::$J2000);
      if (!$isNextLeap) {
        $m++;
      }
      if ($m == 13) {
        $m = 1;
        $y++;
      }
    }
  }

  protected function getZaoByGan($index, $name)
  {
    $month = $this->getMonth(1);
    if (null == $month) {
      return '';
    }
    $offset = $index - Solar::fromJulianDay($month->getFirstJulianDay())->getLunar()->getDayGanIndex();
    if ($offset < 0) {
      $offset += 10;
    }
    return preg_replace('/几/', LunarUtil::$NUMBER[$offset + 1], $name, 1);
  }

  protected function getZaoByZhi($index, $name)
  {
    $month = $this->getMonth(1);
    if (null == $month) {
      return '';
    }
    $offset = $index - Solar::fromJulianDay($month->getFirstJulianDay())->getLunar()->getDayZhiIndex();
    if ($offset < 0) {
      $offset += 12;
    }
    return preg_replace('/几/', LunarUtil::$NUMBER[$offset + 1], $name, 1);
  }

  public function getTouLiang()
  {
    return $this->getZaoByZhi(0, '几鼠偷粮');
  }

  public function getCaoZi()
  {
    return $this->getZaoByZhi(0, '草子几分');
  }

  public function getGengTian()
  {
    return $this->getZaoByZhi(1, '几牛耕田');
  }

  public function getHuaShou()
  {
    return $this->getZaoByZhi(3, '花收几分');
  }

  public function getZhiShui()
  {
    return $this->getZaoByZhi(4, '几龙治水');
  }

  public function getTuoGu()
  {
    return $this->getZaoByZhi(6, '几马驮谷');
  }

  public function getQiangMi()
  {
    return $this->getZaoByZhi(9, '几鸡抢米');
  }

  public function getKanCan()
  {
    return $this->getZaoByZhi(9, '几姑看蚕');
  }

  public function getGongZhu()
  {
    return $this->getZaoByZhi(11, '几屠共猪');
  }

  public function getJiaTian()
  {
    return $this->getZaoByGan(0, '甲田几分');
  }

  public function getFenBing()
  {
    return $this->getZaoByGan(2, '几人分饼');
  }

  public function getDeJin()
  {
    return $this->getZaoByGan(7, '几日得金');
  }

  public function getRenBing()
  {
    return $this->getZaoByGan(2, $this->getZaoByZhi(2, '几人几丙'));
  }

  public function getRenChu()
  {
    return $this->getZaoByGan(3, $this->getZaoByZhi(2, '几人几锄'));
  }

  public function getYuan()
  {
    return LunarYear::$YUAN[(int)(($this->year + 2696) / 60) % 3] . '元';
  }

  public function getYun()
  {
    return LunarYear::$YUAN[(int)(($this->year + 2696) / 20) % 9] . '运';
  }

  public function getNineStar()
  {
    $index = LunarUtil::getJiaZiIndex($this->getGanZhi()) + 1;
    $yuan = ((int)($this->year + 2696) / 60) % 3;
    $offset = (62 + $yuan * 3 - $index) % 9;
    if (0 === $offset) {
      $offset = 9;
    }
    return NineStar::fromIndex($offset - 1);
  }

  public function getPositionXi()
  {
    return LunarUtil::$POSITION_XI[$this->ganIndex + 1];
  }

  public function getPositionXiDesc()
  {
    return LunarUtil::$POSITION_DESC[$this->getPositionXi()];
  }

  public function getPositionYangGui()
  {
    return LunarUtil::$POSITION_YANG_GUI[$this->ganIndex + 1];
  }

  public function getPositionYangGuiDesc()
  {
    return LunarUtil::$POSITION_DESC[$this->getPositionYangGui()];
  }

  public function getPositionYinGui()
  {
    return LunarUtil::$POSITION_YIN_GUI[$this->ganIndex + 1];
  }

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
    return $fu[$this->ganIndex + 1];
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

  public function getPositionCai()
  {
    return LunarUtil::$POSITION_CAI[$this->ganIndex + 1];
  }

  public function getPositionCaiDesc()
  {
    return LunarUtil::$POSITION_DESC[$this->getPositionCai()];
  }

  public function getPositionTaiSui()
  {
    return LunarUtil::$POSITION_TAI_SUI_YEAR[$this->zhiIndex];
  }

  public function getPositionTaiSuiDesc()
  {
    return LunarUtil::$POSITION_DESC[$this->getPositionTaiSui()];
  }

  public function next($n)
  {
    return LunarYear::fromYear($this->year + $n);
  }
}
