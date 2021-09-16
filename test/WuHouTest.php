<?php

use com\nlf\calendar\Solar;
use PHPUnit\Framework\TestCase;

/**
 * 物候测试
 * Class WuHouTest
 */
class WuHouTest extends TestCase
{

  public function test1()
  {
    $solar = Solar::fromYmd(2020, 4, 23);
    $lunar = $solar->getLunar();
    $this->assertEquals('萍始生', $lunar->getWuHou());
  }

  public function test2()
  {
    $solar = Solar::fromYmd(2021, 1, 15);
    $lunar = $solar->getLunar();
    $this->assertEquals('雉始雊', $lunar->getWuHou());
  }

  public function test3()
  {
    $solar = Solar::fromYmd(2017, 1, 5);
    $lunar = $solar->getLunar();
    $this->assertEquals('雁北乡', $lunar->getWuHou());
  }

  public function test4()
  {
    $solar = Solar::fromYmd(2020, 4, 10);
    $lunar = $solar->getLunar();
    $this->assertEquals('田鼠化为鴽', $lunar->getWuHou());
  }

  public function test5()
  {
    $solar = Solar::fromYmd(2020, 6, 11);
    $lunar = $solar->getLunar();
    $this->assertEquals('鵙始鸣', $lunar->getWuHou());
  }

  public function test6()
  {
    $solar = Solar::fromYmd(2020, 6, 1);
    $lunar = $solar->getLunar();
    $this->assertEquals('麦秋至', $lunar->getWuHou());
  }

  public function test7()
  {
    $solar = Solar::fromYmd(2020, 12, 8);
    $lunar = $solar->getLunar();
    $this->assertEquals('鹖鴠不鸣', $lunar->getWuHou());
  }

  public function test8()
  {
    $solar = Solar::fromYmd(2020, 12, 11);
    $lunar = $solar->getLunar();
    $this->assertEquals('鹖鴠不鸣', $lunar->getWuHou());
  }

  public function test9()
  {
    $solar = Solar::fromYmd(1982,12,22);
    $lunar = $solar->getLunar();
    $this->assertEquals('蚯蚓结', $lunar->getWuHou());
  }

}
