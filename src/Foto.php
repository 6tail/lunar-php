<?php

namespace com\nlf\calendar;

use com\nlf\calendar\util\FotoUtil;
use com\nlf\calendar\util\LunarUtil;

/**
 * 佛历
 * @package com\nlf\calendar
 */
class Foto
{

  public static $DEAD_YEAR = -543;

  private $lunar;

  function __construct(Lunar $lunar)
  {
    $this->lunar = $lunar;
  }

  public static function fromLunar($lunar)
  {
    return new Foto($lunar);
  }

  public static function fromYmdHms($year, $month, $day, $hour, $minute, $second)
  {
    return Foto::fromLunar(Lunar::fromYmdHms($year + Foto::$DEAD_YEAR - 1, $month, $day, $hour, $minute, $second));
  }

  public static function fromYmd($year, $month, $day)
  {
    return Foto::fromYmdHms($year, $month, $day, 0, 0, 0);
  }

  public function getLunar()
  {
    return $this->lunar;
  }

  public function getYear()
  {
    $sy = $this->lunar->getSolar()->getYear();
    $y = $sy - Foto::$DEAD_YEAR;
    if ($sy == $this->lunar->getYear()) {
      $y++;
    }
    return $y;
  }

  public function getMonth()
  {
    return $this->lunar->getMonth();
  }

  public function getDay()
  {
    return $this->lunar->getDay();
  }

  public function getYearInChinese()
  {
    $y = $this->getYear() . '';
    $s = '';
    for ($i = 0, $j = strlen($y); $i < $j; $i++) {
      $s .= LunarUtil::$NUMBER[ord(substr($y, $i, 1)) - 48];
    }
    return $s;
  }

  public function getMonthInChinese()
  {
    return $this->lunar->getMonthInChinese();
  }

  public function getDayInChinese()
  {
    return $this->lunar->getDayInChinese();
  }

  /**
   * 获取因果犯忌
   *
   * @return FotoFestival[] 因果犯忌列表
   */
  public function getFestivals()
  {
    return FotoUtil::getFestivals($this->getMonth() . '-' . $this->getDay());
  }

  public function isMonthZhai()
  {
    $m = $this->getMonth();
    return 1 == $m || 5 == $m || 9 == $m;
  }

  public function isDayYangGong()
  {
    foreach ($this->getFestivals() as $f) {
      if (strcmp('杨公忌', $f->getName()) == 0) {
        return true;
      }
    }
    return false;
  }

  public function isDayZhaiShuoWang()
  {
    $d = $this->getDay();
    return 1 == $d || 15 == $d;
  }

  public function isDayZhaiSix()
  {
    $d = $this->getDay();
    if (8 == $d || 14 == $d || 15 == $d || 23 == $d || 29 == $d || 30 == $d) {
      return true;
    } else if (28 == $d) {
      $m = LunarMonth::fromYm($this->lunar->getYear(), $this->getMonth());
      return null != $m && 30 != $m->getDayCount();
    }
    return false;
  }

  public function isDayZhaiTen()
  {
    $d = $this->getDay();
    return 1 == $d || 8 == $d || 14 == $d || 15 == $d || 18 == $d || 23 == $d || 24 == $d || 28 == $d || 29 == $d || 30 == $d;
  }

  public function isDayZhaiGuanYin()
  {
    $k = $this->getMonth() . '-' . $this->getDay();
    foreach (FotoUtil::$DAY_ZHAI_GUAN_YIN as $d) {
      if (strcmp($k, $d) == 0) {
        return true;
      }
    }
    return false;
  }

  /**
   * 获取星宿
   *
   * @return string 星宿
   */
  public function getXiu()
  {
    return FotoUtil::getXiu($this->getMonth(), $this->getDay());
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
   *
   * @return string 动物
   */
  public function getAnimal()
  {
    return LunarUtil::$ANIMAL[$this->getXiu()];
  }

  /**
   * 获取宫
   *
   * @return string 宫
   */
  public function getGong()
  {
    return LunarUtil::$GONG[$this->getXiu()];
  }

  /**
   * 获取兽
   *
   * @return string 兽
   */
  public function getShou()
  {
    return LunarUtil::$SHOU[$this->getGong()];
  }

  public function toString()
  {
    return sprintf('%s年%s月%s', $this->getYearInChinese(), $this->getMonthInChinese(), $this->getDayInChinese());
  }

  public function __toString()
  {
    return $this->toString();
  }

  public function toFullString()
  {
    $s = $this->toString();
    foreach ($this->getFestivals() as $f) {
      $s .= ' (' . $f . ')';
    }
    return $s;
  }

}
