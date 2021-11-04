<?php

namespace com\nlf\calendar;

use com\nlf\calendar\util\LunarUtil;

bcscale(12);

/**
 * 小运
 * @package com\nlf\calendar
 */
class XiaoYun
{
  /**
   * 序数，0-9
   * @var int
   */
  private $index;

  /**
   * 大运
   * @var DaYun
   */
  private $daYun;

  /**
   * 年
   * @var int
   */
  private $year;

  /**
   * 年龄
   * @var int
   */
  private $age;

  /**
   * 阴历
   * @var Lunar
   */
  private $lunar;

  /**
   * 是否顺推
   * @var bool
   */
  private $forward;

  /**
   * 初始化
   * @param int $index 序数
   * @param DaYun $daYun 大运
   * @param bool $forward 是否顺推
   */
  public function __construct(DaYun $daYun, $index, $forward)
  {
    $this->daYun = $daYun;
    $this->lunar = $daYun->getLunar();
    $this->index = $index;
    $this->year = $daYun->getStartYear() + $index;
    $this->age = $daYun->getStartAge() + $index;
    $this->forward = $forward;
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
   * 获取大运
   * @return DaYun
   */
  public function getDaYun()
  {
    return $this->daYun;
  }

  /**
   * 获取年
   * @return int
   */
  public function getYear()
  {
    return $this->year;
  }

  /**
   * 获取年龄
   * @return int
   */
  public function getAge()
  {
    return $this->age;
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
   * 是否顺推
   * @return bool
   */
  public function isForward()
  {
    return $this->forward;
  }

  /**
   * 获取干支
   * @return string
   */
  public function getGanZhi()
  {
    $offset = LunarUtil::getJiaZiIndex($this->lunar->getTimeInGanZhi());
    $add = $this->index + 1;
    if ($this->daYun->getIndex() > 0) {
      $add += $this->daYun->getStartAge() - 1;
    }
    $offset += $this->forward ? $add : -$add;
    $size = count(LunarUtil::$JIA_ZI);
    while ($offset < 0) {
      $offset += $size;
    }
    $offset %= $size;
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

}
