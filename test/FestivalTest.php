<?php

use com\nlf\calendar\Solar;
use PHPUnit\Framework\TestCase;

/**
 * 节日测试
 * Class FestivalTest
 */
class FestivalTest extends TestCase
{

  public function test1()
  {
    $solar = Solar::fromYmd(2020, 11, 26);
    $this->assertEquals('感恩节', implode($solar->getFestivals()));

    $solar = Solar::fromYmd(2020, 6, 21);
    $this->assertEquals('父亲节', implode($solar->getFestivals()));

    $solar = Solar::fromYmd(2021, 5, 9);
    $this->assertEquals('母亲节', implode($solar->getFestivals()));

    $solar = Solar::fromYmd(1986, 11, 27);
    $this->assertEquals('感恩节', implode($solar->getFestivals()));

    $solar = Solar::fromYmd(1985, 6, 16);
    $this->assertEquals('父亲节', implode($solar->getFestivals()));

    $solar = Solar::fromYmd(1984, 5, 13);
    $this->assertEquals('母亲节', implode($solar->getFestivals()));
  }

}
