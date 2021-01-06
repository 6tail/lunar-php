<?php

namespace com\nlf\calendar;

/**
 * 三伏
 * 从夏至后第3个庚日算起，初伏为10天，中伏为10天或20天，末伏为10天。当夏至与立秋之间出现4个庚日时中伏为10天，出现5个庚日则为20天。
 *
 * @package com\nlf\calendar
 */
class Fu
{
  /**
   * 名称，如：初伏、中伏、末伏
   * @var string
   */
  private $name;

  /**
   * 当前入伏第几天，1-20
   * @var int
   */
  private $index;

  function __construct($name, $index)
  {
    $this->name = $name;
    $this->index = $index;
  }

  public function getName()
  {
    return $this->name;
  }

  public function setName($name)
  {
    $this->name = $name;
  }

  public function getIndex()
  {
    return $this->index;
  }

  public function setIndex($index)
  {
    $this->index = $index;
  }

  public function toString()
  {
    return $this->name;
  }

  public function __toString()
  {
    return $this->toString();
  }

  public function toFullString()
  {
    return $this->name . '第' . $this->index . '天';
  }

}
