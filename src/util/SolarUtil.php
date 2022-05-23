<?php

namespace com\nlf\calendar\util;

use com\nlf\calendar\ExactDate;

/**
 * 阳历工具
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
    '1-10' => array('中国人民警察节', '中国公安110宣传日'),
    '1-21' => array('列宁逝世纪念日'),
    '1-26' => array('国际海关日'),
    '2-2' => array('世界湿地日'),
    '2-4' => array('世界抗癌日'),
    '2-7' => array('京汉铁路罢工纪念'),
    '2-10' => array('国际气象节'),
    '2-19' => array('邓小平逝世纪念日'),
    '2-21' => array('国际母语日'),
    '2-24' => array('第三世界青年日'),
    '3-1' => array('国际海豹日'),
    '3-3' => array('全国爱耳日'),
    '3-5' => array('周恩来诞辰纪念日', '中国青年志愿者服务日'),
    '3-6' => array('世界青光眼日'),
    '3-12' => array('孙中山逝世纪念日'),
    '3-14' => array('马克思逝世纪念日'),
    '3-17' => array('国际航海日'),
    '3-18' => array('全国科技人才活动日'),
    '3-21' => array('世界森林日', '世界睡眠日'),
    '3-22' => array('世界水日'),
    '3-23' => array('世界气象日'),
    '3-24' => array('世界防治结核病日'),
    '4-2' => array('国际儿童图书日'),
    '4-7' => array('世界卫生日'),
    '4-22' => array('列宁诞辰纪念日'),
    '4-23' => array('世界图书和版权日'),
    '4-26' => array('世界知识产权日'),
    '5-3' => array('世界新闻自由日'),
    '5-5' => array('马克思诞辰纪念日'),
    '5-8' => array('世界红十字日'),
    '5-11' => array('世界肥胖日'),
    '5-25' => array('525心理健康节'),
    '5-27' => array('上海解放日'),
    '5-31' => array('世界无烟日'),
    '6-5' => array('世界环境日'),
    '6-6' => array('全国爱眼日'),
    '6-8' => array('世界海洋日'),
    '6-11' => array('中国人口日'),
    '6-14' => array('世界献血日'),
    '7-1' => array('香港回归纪念日'),
    '7-7' => array('中国人民抗日战争纪念日'),
    '7-11' => array('世界人口日'),
    '8-5' => array('恩格斯逝世纪念日'),
    '8-6' => array('国际电影节'),
    '8-12' => array('国际青年日'),
    '8-22' => array('邓小平诞辰纪念日'),
    '9-3' => array('中国抗日战争胜利纪念日'),
    '9-8' => array('世界扫盲日'),
    '9-9' => array('毛泽东逝世纪念日'),
    '9-14' => array('世界清洁地球日'),
    '9-18' => array('九一八事变纪念日'),
    '9-20' => array('全国爱牙日'),
    '9-21' => array('国际和平日'),
    '9-27' => array('世界旅游日'),
    '10-4' => array('世界动物日'),
    '10-10' => array('辛亥革命纪念日'),
    '10-13' => array('中国少年先锋队诞辰日'),
    '10-25' => array('抗美援朝纪念日'),
    '11-12' => array('孙中山诞辰纪念日'),
    '11-17' => array('国际大学生节'),
    '11-28' => array('恩格斯诞辰纪念日'),
    '12-1' => array('世界艾滋病日'),
    '12-12' => array('西安事变纪念日'),
    '12-13' => array('国家公祭日'),
    '12-26' => array('毛泽东诞辰纪念日')
  );

  /**
   * 是否闰年
   * @param int $year 年
   * @return bool 是否闰年
   */
  public static function isLeapYear($year)
  {
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
    $d = SolarUtil::$DAYS_OF_MONTH[$month - 1];
    //公历闰年2月多一天
    if ($month === 2 && SolarUtil::isLeapYear($year)) {
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
    return SolarUtil::isLeapYear($year) ? 366 : 365;
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
      $days += SolarUtil::getDaysOfMonth($year, $i);
    }
    $days += $day;
    if (1582 === $year && 10 === $month && $day >= 15) {
      $days -= 10;
    }
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
    $days = SolarUtil::getDaysOfMonth($year, $month);
    $week = intval(ExactDate::fromYmd($year, $month, 1)->format('w'));
    return ceil(($days + $week - $start) / count(SolarUtil::$WEEK));
  }
}