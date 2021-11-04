<?php

namespace com\nlf\calendar;

use com\nlf\calendar\util\LunarUtil;

bcscale(12);

/**
 * 流月
 * @package com\nlf\calendar
 */
class LiuYue
{
  /**
   * 序数，0-9
   * @var int
   */
  private $index;

  /**
   * 流年
   * @var LiuNian
   */
  private $liuNian;

  /**
   * 初始化
   * @param LiuNian $liuNian
   * @param int $index
   */
  public function __construct(LiuNian $liuNian, $index)
  {
    $this->liuNian = $liuNian;
    $this->index = $index;
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
   * 获取流年
   * @return LiuNian
   */
  public function getLiuNian()
  {
    return $this->liuNian;
  }

  /**
   * 获取中文的月
   * @return string 中文月，如正
   */
  public function getMonthInChinese()
  {
    return LunarUtil::$MONTH[$this->index + 1];
  }

  /**
   * 获取干支
   * @return string
   */
  public function getGanZhi()
  {
    $offset = 0;
    $liuNianGanZhi = $this->liuNian->getGanZhi();
    $yearGan = substr($liuNianGanZhi, 0, strlen($liuNianGanZhi) / 2);
    if ('甲' == $yearGan || '己' == $yearGan) {
      $offset = 2;
    } else if ('乙' == $yearGan || '庚' == $yearGan) {
      $offset = 4;
    } else if ('丙' == $yearGan || '辛' == $yearGan) {
      $offset = 6;
    } else if ('丁' == $yearGan || '壬' == $yearGan) {
      $offset = 8;
    }
    $gan = LunarUtil::$GAN[($this->index + $offset) % 10 + 1];
    $zhi = LunarUtil::$ZHI[($this->index + LunarUtil::$BASE_MONTH_ZHI_INDEX) % 12 + 1];
    return $gan . $zhi;
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
