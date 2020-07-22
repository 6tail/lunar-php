<?php
include 'lunar.php';

use com\nlf\calendar\util\HolidayUtil;
use com\nlf\calendar\Lunar;
use com\nlf\calendar\Solar;

$lunar = Lunar::fromYmd(1986,4,21);
echo $lunar->toFullString()."\n";
echo $lunar->getSolar()->toFullString()."\n";

// 节假日
echo HolidayUtil::getHoliday('2020-05-02')."\n";

// 儒略日
$solar = Solar::fromYmd(2020, 7, 15);
echo $solar->getJulianDay()."\n";

$solar = Solar::fromJulianDay(2459045.5);
echo $solar->toFullString()."\n";

// 节气表
$lunar = Lunar::fromDate(new DateTime());
$jieQi = $lunar->getJieQiTable();
foreach ($jieQi as $key => $value) {
  echo $key." = ".$value->toYmdHms()."\n";
}

// 时辰吉神方位
echo $lunar->getTimePositionFu()."\n";
echo $lunar->getTimePositionXi()."\n";
echo $lunar->getTimePositionCai()."\n";
echo $lunar->getTimePositionYinGui()."\n";
echo $lunar->getTimePositionYangGui()."\n";

echo $lunar->getTimePositionFuDesc()."\n";
echo $lunar->getTimePositionXiDesc()."\n";
echo $lunar->getTimePositionCaiDesc()."\n";
echo $lunar->getTimePositionYinGuiDesc()."\n";
echo $lunar->getTimePositionYangGuiDesc()."\n";

// 八字转阳历
$l = Solar::fromBaZi("庚子", "癸未", "乙丑", "丁亥");
foreach ($l as $d){
  echo $d->toFullString()."\n";
}