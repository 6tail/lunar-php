<?php

use com\nlf\calendar\Solar;
use PHPUnit\Framework\TestCase;

/**
 * 三伏测试
 * Class FuTest
 */
class FuTest extends TestCase
{

  public function test1()
  {
    $solar = Solar::fromYmd(2011, 7, 14);
    $lunar = $solar->getLunar();
    $fu = $lunar->getFu();
    $this->assertEquals('初伏', $fu->toString());
    $this->assertEquals('初伏第1天', $fu->toFullString());
  }

  public function test2()
  {
    $solar = Solar::fromYmd(2011, 7, 23);
    $lunar = $solar->getLunar();
    $fu = $lunar->getFu();
    $this->assertEquals('初伏', $fu->toString());
    $this->assertEquals('初伏第10天', $fu->toFullString());
  }

  public function test3()
  {
    $solar = Solar::fromYmd(2011, 7, 24);
    $lunar = $solar->getLunar();
    $fu = $lunar->getFu();
    $this->assertEquals('中伏', $fu->toString());
    $this->assertEquals('中伏第1天', $fu->toFullString());
  }

  public function test4()
  {
    $solar = Solar::fromYmd(2011, 8, 12);
    $lunar = $solar->getLunar();
    $fu = $lunar->getFu();
    $this->assertEquals('中伏', $fu->toString());
    $this->assertEquals('中伏第20天', $fu->toFullString());
  }

  public function test5()
  {
    $solar = Solar::fromYmd(2011, 8, 13);
    $lunar = $solar->getLunar();
    $fu = $lunar->getFu();
    $this->assertEquals('末伏', $fu->toString());
    $this->assertEquals('末伏第1天', $fu->toFullString());
  }

  public function test6()
  {
    $solar = Solar::fromYmd(2011, 8, 22);
    $lunar = $solar->getLunar();
    $fu = $lunar->getFu();
    $this->assertEquals('末伏', $fu->toString());
    $this->assertEquals('末伏第10天', $fu->toFullString());
  }

  public function test7()
  {
    $solar = Solar::fromYmd(2011, 7, 13);
    $lunar = $solar->getLunar();
    $fu = $lunar->getFu();
    $this->assertNull($fu);
  }

  public function test8()
  {
    $solar = Solar::fromYmd(2011, 8, 23);
    $lunar = $solar->getLunar();
    $fu = $lunar->getFu();
    $this->assertNull($fu);
  }

  public function test9()
  {
    $solar = Solar::fromYmd(2012, 7, 18);
    $lunar = $solar->getLunar();
    $fu = $lunar->getFu();
    $this->assertEquals('初伏', $fu->toString());
    $this->assertEquals('初伏第1天', $fu->toFullString());
  }

  public function test10()
  {
    $solar = Solar::fromYmd(2012, 8, 5);
    $lunar = $solar->getLunar();
    $fu = $lunar->getFu();
    $this->assertEquals('中伏', $fu->toString());
    $this->assertEquals('中伏第9天', $fu->toFullString());
  }

  public function test11()
  {
    $solar = Solar::fromYmd(2012, 8, 8);
    $lunar = $solar->getLunar();
    $fu = $lunar->getFu();
    $this->assertEquals('末伏', $fu->toString());
    $this->assertEquals('末伏第2天', $fu->toFullString());
  }

  public function test12()
  {
    $solar = Solar::fromYmd(2020, 7, 17);
    $lunar = $solar->getLunar();
    $fu = $lunar->getFu();
    $this->assertEquals('初伏', $fu->toString());
    $this->assertEquals('初伏第2天', $fu->toFullString());
  }

  public function test13()
  {
    $solar = Solar::fromYmd(2020, 7, 26);
    $lunar = $solar->getLunar();
    $fu = $lunar->getFu();
    $this->assertEquals('中伏', $fu->toString());
    $this->assertEquals('中伏第1天', $fu->toFullString());
  }

  public function test14()
  {
    $solar = Solar::fromYmd(2020, 8, 24);
    $lunar = $solar->getLunar();
    $fu = $lunar->getFu();
    $this->assertEquals('末伏', $fu->toString());
    $this->assertEquals('末伏第10天', $fu->toFullString());
  }

}
