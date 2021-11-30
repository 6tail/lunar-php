<?php

namespace com\nlf\calendar;

/**
 * 道历节日
 * @package com\nlf\calendar
 */
class TaoFestival
{

  private $name;

  private $remark;

  function __construct($name, $remark = null)
  {
    $this->name = $name;
    $this->remark = null == $remark ? '' : $remark;
  }

  public function getName()
  {
    return $this->name;
  }

  public function getRemark()
  {
    return $this->remark;
  }

  public function toString()
  {
    return $this->name;
  }

  public function toFullString()
  {
    $s = $this->name;
    if (null != $this->remark && strlen($this->remark) > 0) {
      $s .= '[' . $this->remark . ']';
    }
    return $s;
  }

  public function __toString()
  {
    return $this->toString();
  }

}
