<?php

use com\nlf\calendar\Foto;
use com\nlf\calendar\Lunar;
use PHPUnit\Framework\TestCase;

class FotoTest extends TestCase
{
  public function test()
  {
    $foto = Foto::fromLunar(Lunar::fromYmd(2021, 10, 14));
    $this->assertEquals('二五六五年十月十四 (三元降) (四天王巡行)', $foto->toFullString());
  }

}
