<?php

use com\nlf\calendar\Tao;
use com\nlf\calendar\Lunar;
use PHPUnit\Framework\TestCase;

class TaoTest extends TestCase
{
  public function test()
  {
    $tao = Tao::fromLunar(Lunar::fromYmdHms(2021, 10, 17, 18, 0, 0));
    $this->assertEquals('四七一八年十月十七', $tao->toString());
    $this->assertEquals('道歷四七一八年，天運辛丑年，己亥月，癸酉日。十月十七日，酉時。', $tao->toFullString());
  }

  public function test1()
  {
    $tao = Tao::fromYmd(4718, 10, 18);
    $this->assertEquals(2, count($tao->getFestivals()));

    $tao = Lunar::fromYmd(2021, 10, 18)->getTao();
    $this->assertEquals(2, count($tao->getFestivals()));
  }

}
