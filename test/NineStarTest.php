<?php

use com\nlf\calendar\Solar;
use com\nlf\calendar\Lunar;
use PHPUnit\Framework\TestCase;

class NineStarTest extends TestCase
{
  public function test1()
  {
    $lunar = Solar::fromYmd(1985, 2, 19)->getLunar();
    $this->assertEquals('六', $lunar->getYearNineStar()->getNumber());
  }

  public function test22()
  {
    $lunar = Lunar::fromYmd(2022, 1, 1);
    $this->assertEquals('六白金开阳', $lunar->getYearNineStar()->toString());
  }

  public function test23()
  {
    $lunar = Lunar::fromYmd(2033, 1, 1);
    $this->assertEquals('四绿木天权', $lunar->getYearNineStar()->toString());
  }

}
