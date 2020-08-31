<?php
include 'lunar.php';

use com\nlf\calendar\util\HolidayUtil;
use com\nlf\calendar\Lunar;
use com\nlf\calendar\Solar;

$lunar = Lunar::fromYmd(1986, 4, 21);
echo $lunar->toFullString() . "\n";
echo $lunar->getSolar()->toFullString() . "\n";

// 节假日
echo HolidayUtil::getHoliday('2020-05-02') . "\n";

// 儒略日
$solar = Solar::fromYmd(2020, 7, 15);
echo $solar->getJulianDay() . "\n";

$solar = Solar::fromJulianDay(2459045.5);
echo $solar->toFullString() . "\n";

// 遍历节气表
$lunar = Lunar::fromDate(new DateTime());
$jieQi = $lunar->getJieQiTable();
foreach ($jieQi as $key => $value) {
  echo $key . " = " . $value->toYmdHms() . "\n";
}

// 遍历日吉神（宜趋）
foreach ($lunar->getDayJiShen() as $js) {
  echo $js . " ";
}
echo "\n";

// 遍历时辰宜
foreach ($lunar->getTimeYi() as $yi) {
  echo $yi . " ";
}
echo "\n";

// 八字
$baZi = $lunar->getEightChar();
echo $baZi . " ";
echo "\n";

// 八字五行
echo $baZi->getYearWuXing() . " " . $baZi->getMonthWuXing() . " " . $baZi->getDayWuXing() . " " . $baZi->getTimeWuXing();
echo "\n";

// 八字纳音
echo $baZi->getYearNaYin() . " " . $baZi->getMonthNaYin() . " " . $baZi->getDayNaYin() . " " . $baZi->getTimeNaYin();
echo "\n";

// 八字天干十神
echo $baZi->getYearShiShenGan() . " " . $baZi->getMonthShiShenGan() . " " . $baZi->getDayShiShenGan() . " " . $baZi->getTimeShiShenGan();
echo "\n";

// 遍历八字年支十神
foreach ($baZi->getYearShiShenZhi() as $shen) {
  echo $shen . " ";
}
echo "\n";

// 遍历八字月支十神
foreach ($baZi->getMonthShiShenZhi() as $shen) {
  echo $shen . " ";
}
echo "\n";

// 遍历八字日支十神
foreach ($baZi->getDayShiShenZhi() as $shen) {
  echo $shen . " ";
}
echo "\n";

// 遍历八字时支十神
foreach ($baZi->getTimeShiShenZhi() as $shen) {
  echo $shen . " ";
}
echo "\n";

// 八字胎元
echo $baZi->getTaiYuan();
echo "\n";

// 八字命宫
echo $baZi->getMingGong();
echo "\n";

// 八字身宫
echo $baZi->getShenGong();
echo "\n";

// 时辰吉神方位
echo $lunar->getTimePositionFu() . "\n";
echo $lunar->getTimePositionXi() . "\n";
echo $lunar->getTimePositionCai() . "\n";
echo $lunar->getTimePositionYinGui() . "\n";
echo $lunar->getTimePositionYangGui() . "\n";

echo $lunar->getTimePositionFuDesc() . "\n";
echo $lunar->getTimePositionXiDesc() . "\n";
echo $lunar->getTimePositionCaiDesc() . "\n";
echo $lunar->getTimePositionYinGuiDesc() . "\n";
echo $lunar->getTimePositionYangGuiDesc() . "\n";

// 指定阳历时间得到八字信息
$solar = Solar::fromYmdHms(1988, 3, 20, 18, 0, 0);
$lunar = $solar->getLunar();
$baZi = $lunar->getEightChar();

// 男运
$yun = $baZi->getYun(1);

echo "\n";
echo "阳历" . $solar->toYmdHms() . "出生\n";
echo "出生" . $yun->getStartYear() . "年" . $yun->getStartMonth() . "个月" . $yun->getStartDay() . "天后起运" . "\n";
echo "阳历" . $yun->getStartSolar()->toYmd() . "后起运" . "\n";

echo "\n";

// 大运
$daYunArr = $yun->getDaYun();
for ($i = 0; $i < count($daYunArr); $i++) {
  $daYun = $daYunArr[$i];
  echo "大运[" . $daYun->getIndex() . "] = " . $daYun->getStartYear() . "年 " . $daYun->getStartAge() . "岁 " . $daYun->getGanZhi() . "\n";
}

echo "\n";

// 第1次大运流年
$liuNianArr = $daYunArr[1]->getLiuNian();
for ($i = 0; $i < count($liuNianArr); $i++) {
  $liuNian = $liuNianArr[$i];
  echo "流年[" . $liuNian->getIndex() . "] = " . $liuNian->getYear() . "年 " . $liuNian->getAge() . "岁 " . $liuNian->getGanZhi() . "\n";
}

echo "\n";

// 第1次大运小运
$xiaoYunArr = $daYunArr[1]->getXiaoYun();
for ($i = 0; $i < count($xiaoYunArr); $i++) {
  $xiaoYun = $xiaoYunArr[$i];
  echo "小运[" . $xiaoYun->getIndex() . "] = " . $xiaoYun->getYear() . "年 " . $xiaoYun->getAge() . "岁 " . $xiaoYun->getGanZhi() . "\n";
}

echo "\n";

// 第1次大运首个流年的流月
$liuYueArr = $liuNianArr[0]->getLiuYue();
for ($i = 0; $i < count($liuYueArr); $i++) {
  $liuYue = $liuYueArr[$i];
  echo "流月[" . $liuYue->getIndex() . "] = " . $liuYue->getMonthInChinese() . "月 " . $liuYue->getGanZhi() . "\n";
}

echo "\n";

// 八字转阳历
try {
  $l = Solar::fromBaZi("庚子", "癸未", "乙丑", "丁亥");
  foreach ($l as $d) {
    echo $d->toFullString() . "\n";
  }
} catch (Exception $e) {
  echo $e->getMessage();
}