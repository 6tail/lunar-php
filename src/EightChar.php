<?php

namespace com\nlf\calendar;

use com\nlf\calendar\util\LunarUtil;

bcscale(12);

/**
 * 八字
 * @package com\nlf\calendar
 */
class EightChar
{

  /**
   * 流派
   * @var int
   */
  private $sect = 2;

  /**
   * 阴历
   * @var Lunar
   */
  private $lunar;

  private static $CHANG_SHENG_OFFSET = array(
    '甲' => 1,
    '丙' => 10,
    '戊' => 10,
    '庚' => 7,
    '壬' => 4,
    '乙' => 6,
    '丁' => 9,
    '己' => 9,
    '辛' => 0,
    '癸' => 3
  );

  /**
   * 月支，按正月起寅排列
   * @var array
   */
  public static $MONTH_ZHI = array('', '寅', '卯', '辰', '巳', '午', '未', '申', '酉', '戌', '亥', '子', '丑');

  /**
   * 长生十二神
   * @var array
   */
  public static $CHANG_SHENG = array('长生', '沐浴', '冠带', '临官', '帝旺', '衰', '病', '死', '墓', '绝', '胎', '养');

  function __construct($lunar)
  {
    $this->lunar = $lunar;
  }

  public static function fromLunar($lunar)
  {
    return new EightChar($lunar);
  }

  public function toString()
  {
    return $this->getYear() . ' ' . $this->getMonth() . ' ' . $this->getDay() . ' ' . $this->getTime();
  }

  public function __toString()
  {
    return $this->toString();
  }

  /**
   * 获取流派
   * @return int 流派，2晚子时日柱按当天，1晚子时日柱按明天
   */
  public function getSect()
  {
    return $this->sect;
  }

  /**
   * 设置流派
   * @param int $sect 流派，2晚子时日柱按当天，1晚子时日柱按明天
   */
  public function setSect($sect)
  {
    $this->sect = (1 == $sect) ? 1 : 2;
  }

  /**
   * 获取阴历对象
   * @return Lunar 阴历对象
   */
  public function getLunar()
  {
    return $this->lunar;
  }

  /**
   * 获取年柱
   * @return string 年柱
   */
  public function getYear()
  {
    return $this->lunar->getYearInGanZhiExact();
  }

  /**
   * 获取年干
   * @return string 天干
   */
  public function getYearGan()
  {
    return $this->lunar->getYearGanExact();
  }

  /**
   * 获取年支
   * @return string 地支
   */
  public function getYearZhi()
  {
    return $this->lunar->getYearZhiExact();
  }

  /**
   * 获取年柱地支藏干，由于藏干分主气、余气、杂气，所以返回结果可能为1到3个元素
   * @return string[] 天干
   */
  public function getYearHideGan()
  {
    return LunarUtil::$ZHI_HIDE_GAN[$this->getYearZhi()];
  }

  /**
   * 获取年柱五行
   * @return string 五行
   */
  public function getYearWuXing()
  {
    return LunarUtil::$WU_XING_GAN[$this->getYearGan()] . LunarUtil::$WU_XING_ZHI[$this->getYearZhi()];
  }

  /**
   * 获取年柱纳音
   * @return string 纳音
   */
  public function getYearNaYin()
  {
    return LunarUtil::$NAYIN[$this->getYear()];
  }

  /**
   * 获取年柱天干十神
   * @return string 十神
   */
  public function getYearShiShenGan()
  {
    return LunarUtil::$SHI_SHEN_GAN[$this->getDayGan() . $this->getYearGan()];
  }

  /**
   * 获取十神地支
   * @param $zhi string 地支
   * @return string[]
   */
  private function getShiShenZhi($zhi)
  {
    $hideGan = LunarUtil::$ZHI_HIDE_GAN[$zhi];
    $l = array();
    foreach ($hideGan as $gan) {
      $l[] = LunarUtil::$SHI_SHEN_ZHI[$this->getDayGan() . $zhi . $gan];
    }
    return $l;
  }

  /**
   * 获取年柱地支十神，由于藏干分主气、余气、杂气，所以返回结果可能为1到3个元素
   * @return string[] 十神
   */
  public function getYearShiShenZhi()
  {
    return $this->getShiShenZhi($this->getYearZhi());
  }

  private function getDiShi($zhiIndex)
  {
    $offset = EightChar::$CHANG_SHENG_OFFSET[$this->getDayGan()];
    $index = $offset + ($this->getDayGanIndex() % 2 == 0 ? $zhiIndex : 0 - $zhiIndex);
    if ($index >= 12) {
      $index -= 12;
    }
    if ($index < 0) {
      $index += 12;
    }
    return EightChar::$CHANG_SHENG[$index];
  }

  /**
   * 获取年柱地势（长生十二神）
   * @return string 地势
   */
  public function getYearDiShi()
  {
    return $this->getDiShi($this->lunar->getYearZhiIndexExact());
  }

  /**
   * 获取月柱
   * @return string 月柱
   */
  public function getMonth()
  {
    return $this->lunar->getMonthInGanZhiExact();
  }

  /**
   * 获取月干
   * @return string 天干
   */
  public function getMonthGan()
  {
    return $this->lunar->getMonthGanExact();
  }

  /**
   * 获取月支
   * @return string 地支
   */
  public function getMonthZhi()
  {
    return $this->lunar->getMonthZhiExact();
  }

  /**
   * 获取月柱地支藏干，由于藏干分主气、余气、杂气，所以返回结果可能为1到3个元素
   * @return string[] 天干
   */
  public function getMonthHideGan()
  {
    return LunarUtil::$ZHI_HIDE_GAN[$this->getMonthZhi()];
  }

  /**
   * 获取月柱五行
   * @return string 五行
   */
  public function getMonthWuXing()
  {
    return LunarUtil::$WU_XING_GAN[$this->getMonthGan()] . LunarUtil::$WU_XING_ZHI[$this->getMonthZhi()];
  }

  /**
   * 获取月柱纳音
   * @return string 纳音
   */
  public function getMonthNaYin()
  {
    return LunarUtil::$NAYIN[$this->getMonth()];
  }

  /**
   * 获取月柱天干十神
   * @return string 十神
   */
  public function getMonthShiShenGan()
  {
    return LunarUtil::$SHI_SHEN_GAN[$this->getDayGan() . $this->getMonthGan()];
  }

  /**
   * 获取月柱地支十神，由于藏干分主气、余气、杂气，所以返回结果可能为1到3个元素
   * @return string[] 十神
   */
  public function getMonthShiShenZhi()
  {
    return $this->getShiShenZhi($this->getMonthZhi());
  }

  /**
   * 获取月柱地势（长生十二神）
   * @return string 地势
   */
  public function getMonthDiShi()
  {
    return $this->getDiShi($this->lunar->getMonthZhiIndexExact());
  }

  /**
   * 获取日柱
   * @return string 日柱
   */
  public function getDay()
  {
    return (2 == $this->sect) ? $this->lunar->getDayInGanZhiExact2() : $this->lunar->getDayInGanZhiExact();
  }

  /**
   * 获取日干
   * @return string 天干
   */
  public function getDayGan()
  {
    return (2 == $this->sect) ? $this->lunar->getDayGanExact2() : $this->lunar->getDayGanExact();
  }

  /**
   * 获取日支
   * @return string 地支
   */
  public function getDayZhi()
  {
    return (2 == $this->sect) ? $this->lunar->getDayZhiExact2() : $this->lunar->getDayZhiExact();
  }

  /**
   * 获取日柱地支藏干，由于藏干分主气、余气、杂气，所以返回结果可能为1到3个元素
   * @return string[] 天干
   */
  public function getDayHideGan()
  {
    return LunarUtil::$ZHI_HIDE_GAN[$this->getDayZhi()];
  }

  /**
   * 获取日柱五行
   * @return string 五行
   */
  public function getDayWuXing()
  {
    return LunarUtil::$WU_XING_GAN[$this->getDayGan()] . LunarUtil::$WU_XING_ZHI[$this->getDayZhi()];
  }

  /**
   * 获取日柱纳音
   * @return string 纳音
   */
  public function getDayNaYin()
  {
    return LunarUtil::$NAYIN[$this->getDay()];
  }

  /**
   * 获取日柱天干十神，也称日元、日干
   * @return string 十神
   */
  public function getDayShiShenGan()
  {
    return '日主';
  }

  /**
   * 获取日柱地支十神，由于藏干分主气、余气、杂气，所以返回结果可能为1到3个元素
   * @return string[] 十神
   */
  public function getDayShiShenZhi()
  {
    return $this->getShiShenZhi($this->getDayZhi());
  }

  /**
   * 获取日柱天干序号
   * @return int 日柱天干序号，0-9
   */
  public function getDayGanIndex()
  {
    return (2 == $this->sect) ? $this->lunar->getDayGanIndexExact2() : $this->lunar->getDayGanIndexExact();
  }

  /**
   * 获取日柱地支序号
   * @return int 日柱地支序号，0-11
   */
  public function getDayZhiIndex()
  {
    return (2 == $this->sect) ? $this->lunar->getDayZhiIndexExact2() : $this->lunar->getDayZhiIndexExact();
  }

  /**
   * 获取日柱地势（长生十二神）
   * @return string 地势
   */
  public function getDayDiShi()
  {
    return $this->getDiShi($this->getDayZhiIndex());
  }

  /**
   * 获取时柱
   * @return string 时柱
   */
  public function getTime()
  {
    return $this->lunar->getTimeInGanZhi();
  }

  /**
   * 获取时干
   * @return string 天干
   */
  public function getTimeGan()
  {
    return $this->lunar->getTimeGan();
  }

  /**
   * 获取时支
   * @return string 地支
   */
  public function getTimeZhi()
  {
    return $this->lunar->getTimeZhi();
  }

  /**
   * 获取时柱地支藏干，由于藏干分主气、余气、杂气，所以返回结果可能为1到3个元素
   * @return string[] 天干
   */
  public function getTimeHideGan()
  {
    return LunarUtil::$ZHI_HIDE_GAN[$this->getTimeZhi()];
  }

  /**
   * 获取时柱五行
   * @return string 五行
   */
  public function getTimeWuXing()
  {
    return LunarUtil::$WU_XING_GAN[$this->lunar->getTimeGan()] . LunarUtil::$WU_XING_ZHI[$this->lunar->getTimeZhi()];
  }

  /**
   * 获取时柱纳音
   * @return string 纳音
   */
  public function getTimeNaYin()
  {
    return LunarUtil::$NAYIN[$this->getTime()];
  }

  /**
   * 获取时柱天干十神
   * @return string 十神
   */
  public function getTimeShiShenGan()
  {
    return LunarUtil::$SHI_SHEN_GAN[$this->getDayGan() . $this->getTimeGan()];
  }

  /**
   * 获取时柱地支十神，由于藏干分主气、余气、杂气，所以返回结果可能为1到3个元素
   * @return string[] 十神
   */
  public function getTimeShiShenZhi()
  {
    return $this->getShiShenZhi($this->getTimeZhi());
  }

  /**
   * 获取时柱地势（长生十二神）
   * @return string 地势
   */
  public function getTimeDiShi()
  {
    return $this->getDiShi($this->lunar->getTimeZhiIndex());
  }

  /**
   * 获取胎元
   * @return string 胎元
   */
  public function getTaiYuan()
  {
    $ganIndex = $this->lunar->getMonthGanIndexExact() + 1;
    if ($ganIndex >= 10) {
      $ganIndex -= 10;
    }
    $zhiIndex = $this->lunar->getMonthZhiIndexExact() + 3;
    if ($zhiIndex >= 12) {
      $zhiIndex -= 12;
    }
    return LunarUtil::$GAN[$ganIndex + 1] . LunarUtil::$ZHI[$zhiIndex + 1];
  }

  /**
   * 获取胎元纳音
   * @return string 纳音
   */
  public function getTaiYuanNaYin()
  {
    return LunarUtil::$NAYIN[$this->getTaiYuan()];
  }

  /**
   * 获取胎息
   * @return string 胎息
   */
  public function getTaiXi()
  {
    $ganIndex = (2 == $this->sect) ? $this->lunar->getDayGanIndexExact2() : $this->lunar->getDayGanIndexExact();
    $zhiIndex = (2 == $this->sect) ? $this->lunar->getDayZhiIndexExact2() : $this->lunar->getDayZhiIndexExact();
    return LunarUtil::$HE_GAN_5[$ganIndex] . LunarUtil::$HE_ZHI_6[$zhiIndex];
  }

  /**
   * 获取胎息纳音
   * @return string 纳音
   */
  public function getTaiXiNaYin()
  {
    return LunarUtil::$NAYIN[$this->getTaiXi()];
  }

  /**
   * 获取命宫
   * @return string 命宫
   */
  public function getMingGong()
  {
    $monthZhiIndex = 0;
    $timeZhiIndex = 0;
    for ($i = 0, $j = count(EightChar::$MONTH_ZHI); $i < $j; $i++) {
      $zhi = EightChar::$MONTH_ZHI[$i];
      if ($this->lunar->getMonthZhiExact() == $zhi) {
        $monthZhiIndex = $i;
      }
      if ($this->lunar->getTimeZhi() == $zhi) {
        $timeZhiIndex = $i;
      }
    }
    $zhiIndex = 26 - ($monthZhiIndex + $timeZhiIndex);
    if ($zhiIndex > 12) {
      $zhiIndex -= 12;
    }
    $jiaZiIndex = LunarUtil::getJiaZiIndex($this->lunar->getMonthInGanZhiExact()) - ($monthZhiIndex - $zhiIndex);
    if ($jiaZiIndex >= 60) {
      $jiaZiIndex -= 60;
    }
    if ($jiaZiIndex < 0) {
      $jiaZiIndex += 60;
    }
    return LunarUtil::$JIA_ZI[$jiaZiIndex];
  }

  /**
   * 获取命宫纳音
   * @return string 纳音
   */
  public function getMingGongNaYin()
  {
    return LunarUtil::$NAYIN[$this->getMingGong()];
  }

  /**
   * 获取身宫
   * @return string 身宫
   */
  public function getShenGong()
  {
    $monthZhiIndex = 0;
    $timeZhiIndex = 0;
    for ($i = 0, $j = count(EightChar::$MONTH_ZHI); $i < $j; $i++) {
      $zhi = EightChar::$MONTH_ZHI[$i];
      if ($this->lunar->getMonthZhiExact() == $zhi) {
        $monthZhiIndex = $i;
      }
      if ($this->lunar->getTimeZhi() == $zhi) {
        $timeZhiIndex = $i;
      }
    }
    $zhiIndex = 2 + $monthZhiIndex + $timeZhiIndex;
    if ($zhiIndex > 12) {
      $zhiIndex -= 12;
    }
    $jiaZiIndex = LunarUtil::getJiaZiIndex($this->lunar->getMonthInGanZhiExact()) - ($monthZhiIndex - $zhiIndex);
    if ($jiaZiIndex >= 60) {
      $jiaZiIndex -= 60;
    }
    if ($jiaZiIndex < 0) {
      $jiaZiIndex += 60;
    }
    return LunarUtil::$JIA_ZI[$jiaZiIndex];
  }

  /**
   * 获取身宫纳音
   * @return string 纳音
   */
  public function getShenGongNaYin()
  {
    return LunarUtil::$NAYIN[$this->getShenGong()];
  }

  /**
   * 获取运
   * @param int $gender 性别，1男，0女
   * @return Yun 运
   */
  public function getYun($gender)
  {
    return $this->getYunBySect($gender, 1);
  }

  /**
   * 获取运
   * @param int $gender 性别，1男，0女
   * @param int $sect 流派，1按天数和时辰数计算，3天1年，1天4个月，1时辰10天；2按分钟数计算
   * @return Yun 运
   */
  public function getYunBySect($gender, $sect)
  {
    return new Yun($this, $gender, $sect);
  }

  /**
   * 获取年柱所在旬
   * @return string 旬
   */
  public function getYearXun()
  {
    return $this->lunar->getYearXunExact();
  }

  /**
   * 获取年柱旬空(空亡)
   * @return string 旬空(空亡)
   */
  public function getYearXunKong()
  {
    return $this->lunar->getYearXunKongExact();
  }

  /**
   * 获取月柱所在旬
   * @return string 旬
   */
  public function getMonthXun()
  {
    return $this->lunar->getMonthXunExact();
  }

  /**
   * 获取月柱旬空(空亡)
   * @return string 旬空(空亡)
   */
  public function getMonthXunKong()
  {
    return $this->lunar->getMonthXunKongExact();
  }

  /**
   * 获取日柱所在旬
   * @return string 旬
   */
  public function getDayXun()
  {
    return (2 == $this->sect) ? $this->lunar->getDayXunExact2() : $this->lunar->getDayXunExact();
  }

  /**
   * 获取日柱旬空(空亡)
   * @return string 旬空(空亡)
   */
  public function getDayXunKong()
  {
    return (2 == $this->sect) ? $this->lunar->getDayXunKongExact2() : $this->lunar->getDayXunKongExact();
  }

  /**
   * 获取时柱所在旬
   * @return string 旬
   */
  public function getTimeXun()
  {
    return $this->lunar->getTimeXun();
  }

  /**
   * 获取时柱旬空(空亡)
   * @return string 旬空(空亡)
   */
  public function getTimeXunKong()
  {
    return $this->lunar->getTimeXunKong();
  }
}