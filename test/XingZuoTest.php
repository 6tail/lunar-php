<?php

use com\nlf\calendar\Solar;
use PHPUnit\Framework\TestCase;

/**
 * 星座测试
 * Class XingZuoTest
 */
class XingZuoTest extends TestCase
{

  public function test1()
  {
    $solar = Solar::fromYmd(2020, 3, 21);
    $this->assertEquals('白羊', $solar->getXingZuo());
    $solar = Solar::fromYmd(2020, 4, 19);
    $this->assertEquals('白羊', $solar->getXingZuo());
  }

  public function test2()
  {
    $solar = Solar::fromYmd(2020, 4, 20);
    $this->assertEquals('金牛', $solar->getXingZuo());
    $solar = Solar::fromYmd(2020, 5, 20);
    $this->assertEquals('金牛', $solar->getXingZuo());
  }

  public function test3()
  {
    $solar = Solar::fromYmd(2020, 5, 21);
    $this->assertEquals('双子', $solar->getXingZuo());
    $solar = Solar::fromYmd(2020, 6, 21);
    $this->assertEquals('双子', $solar->getXingZuo());
  }

  public function test4()
  {
    $solar = Solar::fromYmd(2020, 6, 22);
    $this->assertEquals('巨蟹', $solar->getXingZuo());
    $solar = Solar::fromYmd(2020, 7, 22);
    $this->assertEquals('巨蟹', $solar->getXingZuo());
  }

  public function test5()
  {
    $solar = Solar::fromYmd(2020, 7, 23);
    $this->assertEquals('狮子', $solar->getXingZuo());
    $solar = Solar::fromYmd(2020, 8, 22);
    $this->assertEquals('狮子', $solar->getXingZuo());
  }

  public function test6()
  {
    $solar = Solar::fromYmd(2020, 8, 23);
    $this->assertEquals('处女', $solar->getXingZuo());
    $solar = Solar::fromYmd(2020, 9, 22);
    $this->assertEquals('处女', $solar->getXingZuo());
  }

  public function test7()
  {
    $solar = Solar::fromYmd(2020, 9, 23);
    $this->assertEquals('天秤', $solar->getXingZuo());
    $solar = Solar::fromYmd(2020, 10, 23);
    $this->assertEquals('天秤', $solar->getXingZuo());
  }

  public function test8()
  {
    $solar = Solar::fromYmd(2020, 10, 24);
    $this->assertEquals('天蝎', $solar->getXingZuo());
    $solar = Solar::fromYmd(2020, 11, 22);
    $this->assertEquals('天蝎', $solar->getXingZuo());
  }

  public function test9()
  {
    $solar = Solar::fromYmd(2020, 11, 23);
    $this->assertEquals('射手', $solar->getXingZuo());
    $solar = Solar::fromYmd(2020, 12, 21);
    $this->assertEquals('射手', $solar->getXingZuo());
  }

  public function test10()
  {
    $solar = Solar::fromYmd(2020, 12, 22);
    $this->assertEquals('摩羯', $solar->getXingZuo());
    $solar = Solar::fromYmd(2021, 1, 19);
    $this->assertEquals('摩羯', $solar->getXingZuo());
  }

  public function test11()
  {
    $solar = Solar::fromYmd(2021, 1, 20);
    $this->assertEquals('水瓶', $solar->getXingZuo());
    $solar = Solar::fromYmd(2021, 2, 18);
    $this->assertEquals('水瓶', $solar->getXingZuo());
  }

  public function test12()
  {
    $solar = Solar::fromYmd(2021, 2, 19);
    $this->assertEquals('双鱼', $solar->getXingZuo());
    $solar = Solar::fromYmd(2021, 3, 20);
    $this->assertEquals('双鱼', $solar->getXingZuo());
  }

}
