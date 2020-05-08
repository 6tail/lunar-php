<?php
include 'lunar.php';

use com\nlf\calendar\util\HolidayUtil;
use com\nlf\calendar\Lunar;

$lunar = Lunar::fromYmd(1986,4,21);
echo $lunar->toFullString()."\n";
echo $lunar->getSolar()->toFullString()."\n";

echo HolidayUtil::getHoliday('2020-05-02')."\n";