<?php

namespace com\nlf\calendar\util;

use com\nlf\calendar\Solar;
use RuntimeException;

/**
 * 公历工具
 * @package com\nlf\calendar\util
 */
class SolarUtil
{
  /**
   * 星期
   * @var array
   */
  public static $WEEK = array('日', '一', '二', '三', '四', '五', '六');

  /**
   * 每月天数
   * @var array
   */
  public static $DAYS_OF_MONTH = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

  /**
   * 星座
   * @var array
   */
  public static $XING_ZUO = array('白羊', '金牛', '双子', '巨蟹', '狮子', '处女', '天秤', '天蝎', '射手', '摩羯', '水瓶', '双鱼');

  /**
   * 日期对应的节日
   * @var array
   */
  public static $FESTIVAL = array(
    '1-1' => '元旦节',
    '2-14' => '情人节',
    '3-8' => '妇女节',
    '3-12' => '植树节',
    '3-15' => '消费者权益日',
    '4-1' => '愚人节',
    '5-1' => '劳动节',
    '5-4' => '青年节',
    '6-1' => '儿童节',
    '7-1' => '建党节',
    '8-1' => '建军节',
    '9-10' => '教师节',
    '10-1' => '国庆节',
    '10-31' => '万圣节前夜',
    '11-1' => '万圣节',
    '12-24' => '平安夜',
    '12-25' => '圣诞节'
  );

  /**
   * 几月第几个星期几对应的节日
   * @var array
   */
  public static $WEEK_FESTIVAL = array(
    '3-0-1' => '全国中小学生安全教育日',
    '5-2-0' => '母亲节',
    '6-3-0' => '父亲节',
    '11-4-4' => '感恩节'
  );

  /**
   * 日期对应的非正式节日
   * @var array
   */
  public static $OTHER_FESTIVAL = array(
    '1-8' => array('周恩来逝世纪念日'),
    '1-10' => array('中国人民警察节'),
    '1-14' => array('日记情人节'),
    '1-21' => array('列宁逝世纪念日'),
    '1-26' => array('国际海关日'),
    '1-27' => array('国际大屠杀纪念日'),
    '2-2' => array('世界湿地日'),
    '2-4' => array('世界抗癌日'),
    '2-7' => array('京汉铁路罢工纪念日'),
    '2-10' => array('国际气象节'),
    '2-19' => array('邓小平逝世纪念日'),
    '2-20' => array('世界社会公正日'),
    '2-21' => array('国际母语日'),
    '2-24' => array('第三世界青年日'),
    '3-1' => array('国际海豹日'),
    '3-3' => array('世界野生动植物日', '全国爱耳日'),
    '3-5' => array('周恩来诞辰纪念日', '中国青年志愿者服务日'),
    '3-6' => array('世界青光眼日'),
    '3-7' => array('女生节'),
    '3-12' => array('孙中山逝世纪念日'),
    '3-14' => array('马克思逝世纪念日', '白色情人节'),
    '3-17' => array('国际航海日'),
    '3-18' => array('全国科技人才活动日', '全国爱肝日'),
    '3-20' => array('国际幸福日'),
    '3-21' => array('世界森林日', '世界睡眠日', '国际消除种族歧视日'),
    '3-22' => array('世界水日'),
    '3-23' => array('世界气象日'),
    '3-24' => array('世界防治结核病日'),
    '3-29' => array('中国黄花岗七十二烈士殉难纪念日'),
    '4-2' => array('国际儿童图书日', '世界自闭症日'),
    '4-4' => array('国际地雷行动日'),
    '4-7' => array('世界卫生日'),
    '4-8' => array('国际珍稀动物保护日'),
    '4-12' => array('世界航天日'),
    '4-14' => array('黑色情人节'),
    '4-15' => array('全民国家安全教育日'),
    '4-22' => array('世界地球日', '列宁诞辰纪念日'),
    '4-23' => array('世界读书日'),
    '4-24' => array('中国航天日'),
    '4-25' => array('儿童预防接种宣传日'),
    '4-26' => array('世界知识产权日', '全国疟疾日'),
    '4-28' => array('世界安全生产与健康日'),
    '4-30' => array('全国交通安全反思日'),
    '5-2' => array('世界金枪鱼日'),
    '5-3' => array('世界新闻自由日'),
    '5-5' => array('马克思诞辰纪念日'),
    '5-8' => array('世界红十字日'),
    '5-11' => array('世界肥胖日'),
    '5-12' => array('全国防灾减灾日', '护士节'),
    '5-14' => array('玫瑰情人节'),
    '5-15' => array('国际家庭日'),
    '5-19' => array('中国旅游日'),
    '5-20' => array('网络情人节'),
    '5-22' => array('国际生物多样性日'),
    '5-25' => array('525心理健康节'),
    '5-27' => array('上海解放日'),
    '5-29' => array('国际维和人员日'),
    '5-30' => array('中国五卅运动纪念日'),
    '5-31' => array('世界无烟日'),
    '6-3' => array('世界自行车日'),
    '6-5' => array('世界环境日'),
    '6-6' => array('全国爱眼日'),
    '6-8' => array('世界海洋日'),
    '6-11' => array('中国人口日'),
    '6-14' => array('世界献血日', '亲亲情人节'),
    '6-17' => array('世界防治荒漠化与干旱日'),
    '6-20' => array('世界难民日'),
    '6-21' => array('国际瑜伽日'),
    '6-25' => array('全国土地日'),
    '6-26' => array('国际禁毒日', '联合国宪章日'),
    '7-1' => array('香港回归纪念日'),
    '7-6' => array('国际接吻日', '朱德逝世纪念日'),
    '7-7' => array('七七事变纪念日'),
    '7-11' => array('世界人口日', '中国航海日'),
    '7-14' => array('银色情人节'),
    '7-18' => array('曼德拉国际日'),
    '7-30' => array('国际友谊日'),
    '8-3' => array('男人节'),
    '8-5' => array('恩格斯逝世纪念日'),
    '8-6' => array('国际电影节'),
    '8-8' => array('全民健身日'),
    '8-9' => array('国际土著人日'),
    '8-12' => array('国际青年节'),
    '8-14' => array('绿色情人节'),
    '8-19' => array('世界人道主义日', '中国医师节'),
    '8-22' => array('邓小平诞辰纪念日'),
    '8-29' => array('全国测绘法宣传日'),
    '9-3' => array('中国抗日战争胜利纪念日'),
    '9-5' => array('中华慈善日'),
    '9-8' => array('世界扫盲日'),
    '9-9' => array('毛泽东逝世纪念日', '全国拒绝酒驾日'),
    '9-14' => array('世界清洁地球日', '相片情人节'),
    '9-15' => array('国际民主日'),
    '9-16' => array('国际臭氧层保护日'),
    '9-17' => array('世界骑行日'),
    '9-18' => array('九一八事变纪念日'),
    '9-20' => array('全国爱牙日'),
    '9-21' => array('国际和平日'),
    '9-27' => array('世界旅游日'),
    '9-30' => array('中国烈士纪念日'),
    '10-1' => array('国际老年人日'),
    '10-2' => array('国际非暴力日'),
    '10-4' => array('世界动物日'),
    '10-11' => array('国际女童日'),
    '10-10' => array('辛亥革命纪念日'),
    '10-13' => array('国际减轻自然灾害日', '中国少年先锋队诞辰日'),
    '10-14' => array('葡萄酒情人节'),
    '10-16' => array('世界粮食日'),
    '10-17' => array('全国扶贫日'),
    '10-20' => array('世界统计日'),
    '10-24' => array('世界发展信息日', '程序员节'),
    '10-25' => array('抗美援朝纪念日'),
    '11-5' => array('世界海啸日'),
    '11-8' => array('记者节'),
    '11-9' => array('全国消防日'),
    '11-11' => array('光棍节'),
    '11-12' => array('孙中山诞辰纪念日'),
    '11-14' => array('电影情人节'),
    '11-16' => array('国际宽容日'),
    '11-17' => array('国际大学生节'),
    '11-19' => array('世界厕所日'),
    '11-28' => array('恩格斯诞辰纪念日'),
    '11-29' => array('国际声援巴勒斯坦人民日'),
    '12-1' => array('世界艾滋病日'),
    '12-2' => array('全国交通安全日'),
    '12-3' => array('世界残疾人日'),
    '12-4' => array('全国法制宣传日'),
    '12-5' => array('世界弱能人士日', '国际志愿人员日'),
    '12-7' => array('国际民航日'),
    '12-9' => array('世界足球日', '国际反腐败日'),
    '12-10' => array('世界人权日'),
    '12-11' => array('国际山岳日'),
    '12-12' => array('西安事变纪念日'),
    '12-13' => array('国家公祭日'),
    '12-14' => array('拥抱情人节'),
    '12-18' => array('国际移徙者日'),
    '12-26' => array('毛泽东诞辰纪念日')
  );

  /**
   * 是否闰年
   * @param int $year 年
   * @return bool 是否闰年
   */
  public static function isLeapYear($year)
  {
    if ($year < 1600) {
      return $year % 4 === 0;
    }
    return ($year%4 === 0 && $year%100 != 0) || ($year%400 === 0);
  }

  /**
   * 获取某年某月有多少天
   * @param int $year 年
   * @param int $month 月
   * @return int 天数
   */
  public static function getDaysOfMonth($year, $month)
  {
    if (1582 === $year && 10 === $month) {
      return 21;
    }
    $d = self::$DAYS_OF_MONTH[$month - 1];
    //公历闰年2月多一天
    if ($month === 2 && self::isLeapYear($year)) {
      $d++;
    }
    return $d;
  }

  /**
   * 获取某年有多少天（平年365天，闰年366天）
   * @param $year int 年
   * @return int 天数
   */
  public static function getDaysOfYear($year)
  {
    if (1582 == $year) {
      return 355;
    }
    return self::isLeapYear($year) ? 366 : 365;
  }

  /**
   * 获取某天为当年的第几天
   * @param $year int 年
   * @param $month int 月
   * @param $day int 日
   * @return int 第几天
   */
  public static function getDaysInYear($year, $month, $day)
  {
    $days = 0;
    for ($i = 1; $i < $month; $i++) {
      $days += self::getDaysOfMonth($year, $i);
    }
    $d = $day;
    if (1582 === $year && 10 === $month) {
      if ($day >= 15) {
        $d -= 10;
      } else if ($day > 4){
        throw new RuntimeException(sprintf('wrong solar year %d month %d day %d', $year, $month, $day));
      }
    }
    $days += $d;
    return $days;
  }

  /**
   * 获取某年某月有多少周
   * @param int $year 年
   * @param int $month 月
   * @param int $start 星期几作为一周的开始，1234560分别代表星期一至星期天
   * @return int 周数
   */
  public static function getWeeksOfMonth($year, $month, $start)
  {
    return (int)ceil((self::getDaysOfMonth($year, $month) + Solar::fromYmd($year, $month, 1)->getWeek() - $start) / count(self::$WEEK));
  }

  /**
   * 获取两个日期之间相差的天数（如果日期a比日期b小，天数为正，如果日期a比日期b大，天数为负）
   * @param $ay int 年a
   * @param $am int 月a
   * @param $ad int 日a
   * @param $by int 年b
   * @param $bm int 月b
   * @param $bd int 日b
   * @return int
   */
  public static function getDaysBetween($ay, $am, $ad, $by, $bm, $bd)
  {
    if ($ay == $by) {
      $n = self::getDaysInYear($by, $bm, $bd) - self::getDaysInYear($ay, $am, $ad);
    } else if ($ay > $by) {
      $days = self::getDaysOfYear($by) - self::getDaysInYear($by, $bm, $bd);
      for ($i = $by + 1; $i < $ay; $i++) {
        $days += self::getDaysOfYear($i);
      }
      $days += self::getDaysInYear($ay, $am, $ad);
      $n = -$days;
    } else {
      $days = self::getDaysOfYear($ay) - self::getDaysInYear($ay, $am, $ad);
      for ($i = $ay + 1; $i < $by; $i++) {
        $days += self::getDaysOfYear($i);
      }
      $days += self::getDaysInYear($by, $bm, $bd);
      $n = $days;
    }
    return $n;
  }

}