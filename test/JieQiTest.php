<?php

use com\nlf\calendar\Solar;
use PHPUnit\Framework\TestCase;

class JieQiTest extends TestCase
{
  public function test()
  {
    $solar = Solar::fromYmd(2021, 12, 21);
    $lunar = $solar->getLunar();
    $this->assertEquals('冬至', $lunar->getJieQi());
  }

}
