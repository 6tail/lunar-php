<?php

namespace com\nlf\calendar;

/**
 * 佛历因果犯忌
 * @package com\nlf\calendar
 */
class FotoFestival
{

  private $name;

  private $result;

  private $everyMonth;

  private $remark;

  function __construct($name, $result = null, $everyMonth = false, $remark = null)
  {
    $this->name = $name;
    $this->result = null == $result ? '' : $result;
    $this->everyMonth = $everyMonth;
    $this->remark = null == $remark ? '' : $remark;
  }

  public function getName()
  {
    return $this->name;
  }

  public function getResult()
  {
    return $this->result;
  }

  public function isEveryMonth()
  {
    return $this->everyMonth;
  }

  public function getRemark()
  {
    return $this->remark;
  }

  public function toString()
  {
    $s = $this->name;
    if (null != $this->result && strlen($this->result) > 0) {
      $s .= ' ' . $this->result;
    }
    if (null != $this->remark && strlen($this->remark) > 0) {
      $s .= ' ' . $this->remark;
    }
    return $s;
  }

  public function __toString()
  {
    return $this->toString();
  }

}
