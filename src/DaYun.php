<?php

namespace com\nlf\calendar;

use com\nlf\calendar\util\LunarUtil;

bcscale(12);

/**
 * 大运
 * @package com\nlf\calendar
 */
class DaYun
{
  /**
   * 开始年(含)
   * @var int
   */
  private $startYear;

  /**
   * 结束年(含)
   * @var int
   */
  private $endYear;

  /**
   * 开始年龄(含)
   * @var int
   */
  private $startAge;

  /**
   * 结束年龄(含)
   * @var int
   */
  private $endAge;

  /**
   * 序数，0-9
   * @var int
   */
  private $index;

  /**
   * 运
   * @var Yun
   */
  private $yun;

  /**
   * 阴历
   * @var Lunar
   */
  private $lunar;

  /**
   * DaYun constructor.
   * @param $yun Yun 运
   * @param $index int
   */
  public function __construct($yun, $index)
  {
    $this->yun = $yun;
    $this->lunar = $yun->getLunar();
    $this->index = $index;
    $birthYear = $this->lunar->getSolar()->getYear();
    $year = $yun->getStartSolar()->getYear();
    if ($index < 1) {
      $this->startYear = $birthYear;
      $this->startAge = 1;
      $this->endYear = $year - 1;
      $this->endAge = $year - $birthYear;
    } else {
      $add = ($index - 1) * 10;
      $this->startYear = $year + $add;
      $this->startAge = $this->startYear - $birthYear + 1;
      $this->endYear = $this->startYear + 9;
      $this->endAge = $this->startAge + 9;
    }
  }

  /**
   * 获取起始年
   * @return int
   */
  public function getStartYear()
  {
    return $this->startYear;
  }

  /**
   * 获取结束年
   * @return int
   */
  public function getEndYear()
  {
    return $this->endYear;
  }

  /**
   * 获取开始年龄
   * @return int
   */
  public function getStartAge()
  {
    return $this->startAge;
  }

  /**
   * 获取结束年龄
   * @return int
   */
  public function getEndAge()
  {
    return $this->endAge;
  }

  /**
   * 获取序数
   * @return int
   */
  public function getIndex()
  {
    return $this->index;
  }

  /**
   * 获取运
   * @return Yun
   */
  public function getYun()
  {
    return $this->yun;
  }

  /**
   * 获取阴历
   * @return Lunar
   */
  public function getLunar()
  {
    return $this->lunar;
  }

  /**
   * 获取干支
   * @return string
   */
  public function getGanZhi()
  {
    if ($this->index < 1) {
      return '';
    }
    $offset = LunarUtil::getJiaZiIndex($this->lunar->getMonthInGanZhiExact());
    $offset += $this->yun->isForward() ? $this->index : -$this->index;
    $size = count(LunarUtil::$JIA_ZI);
    if ($offset >= $size) {
      $offset -= $size;
    }
    if ($offset < 0) {
      $offset += $size;
    }
    return LunarUtil::$JIA_ZI[$offset];
  }

  /**
   * 获取所在旬
   * @return string 旬
   */
  public function getXun()
  {
    return LunarUtil::getXun($this->getGanZhi());
  }

  /**
   * 获取旬空(空亡)
   * @return string 旬空(空亡)
   */
  public function getXunKong()
  {
    return LunarUtil::getXunKong($this->getGanZhi());
  }

  /**
   * 获取10轮流年
   * @return LiuNian[]
   */
  public function getLiuNian()
  {
    return $this->getLiuNianBy(10);
  }

  /**
   * 获取流年
   * @param $n int 轮数
   * @return LiuNian[]
   */
  public function getLiuNianBy($n)
  {
    if ($this->index < 1) {
      $n = $this->endYear - $this->startYear + 1;
    }
    $l = array();
    for ($i = 0; $i < $n; $i++) {
      $l[] = new LiuNian($this, $i);
    }
    return $l;
  }

  /**
   * 获取10轮小运
   * @return XiaoYun[]
   */
  public function getXiaoYun()
  {
    return $this->getXiaoYunBy(10);
  }

  /**
   * 获取小运
   * @param $n int 轮数
   * @return XiaoYun[]
   */
  public function getXiaoYunBy($n)
  {
    if ($this->index < 1) {
      $n = $this->endYear - $this->startYear + 1;
    }
    $l = array();
    for ($i = 0; $i < $n; $i++) {
      $l[] = new XiaoYun($this, $i, $this->yun->isForward());
    }
    return $l;
  }

}
