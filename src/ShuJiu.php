<?php

namespace com\nlf\calendar;

/**
 * 数九
 * @package com\nlf\calendar
 */
class ShuJiu
{
  /**
   * 名称，如：一九、二九
   * @var string
   */
  private $name;

  /**
   * 当前数九第几天，1-9
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
