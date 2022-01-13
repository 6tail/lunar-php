<?php

namespace com\nlf\calendar;

use com\nlf\calendar\util\LunarUtil;

/**
 * 九星
 * @package com\nlf\calendar
 */
class NineStar
{
  /**
   * 序号
   * @var int
   */
  private $index;

  /**
   * 九数
   * @var array
   */
  public static $NUMBER = array('一', '二', '三', '四', '五', '六', '七', '八', '九');

  /**
   * 七色
   * @var array
   */
  public static $COLOR = array('白', '黒', '碧', '绿', '黄', '白', '赤', '白', '紫');

  /**
   * 五行
   * @var array
   */
  public static $WU_XING = array('水', '土', '木', '木', '土', '金', '金', '土', '火');

  /**
   * 后天八卦方位
   * @var array
   */
  public static $POSITION = array('坎', '坤', '震', '巽', '中', '乾', '兑', '艮', '离');

  /**
   * 北斗九星
   * @var array
   */
  public static $NAME_BEI_DOU = array('天枢', '天璇', '天玑', '天权', '玉衡', '开阳', '摇光', '洞明', '隐元');

  /**
   * 玄空九星（玄空风水）
   * @var array
   */
  public static $NAME_XUAN_KONG = array('贪狼', '巨门', '禄存', '文曲', '廉贞', '武曲', '破军', '左辅', '右弼');

  /**
   * 奇门九星（奇门遁甲，也称天盘九星）
   * @var array
   */
  public static $NAME_QI_MEN = array('天蓬', '天芮', '天冲', '天辅', '天禽', '天心', '天柱', '天任', '天英');

  /**
   * 八门（奇门遁甲）
   * @var array
   */
  public static $BA_MEN_QI_MEN = array('休', '死', '伤', '杜', '', '开', '惊', '生', '景');

  /**
   * 太乙九神（太乙神数）
   * @var array
   */
  public static $NAME_TAI_YI = array('太乙', '摄提', '轩辕', '招摇', '天符', '青龙', '咸池', '太阴', '天乙');

  /**
   * 太乙九神对应类型
   * @var array
   */
  public static $TYPE_TAI_YI = array('吉神', '凶神', '安神', '安神', '凶神', '吉神', '凶神', '吉神', '吉神');

  /**
   * 太乙九神歌诀（太乙神数）
   * @var array
   */
  public static $SONG_TAI_YI = array('门中太乙明，星官号贪狼，赌彩财喜旺，婚姻大吉昌，出入无阻挡，参谒见贤良，此行三五里，黑衣别阴阳。', '门前见摄提，百事必忧疑，相生犹自可，相克祸必临，死门并相会，老妇哭悲啼，求谋并吉事，尽皆不相宜，只可藏隐遁，若动伤身疾。', '出入会轩辕，凡事必缠牵，相生全不美，相克更忧煎，远行多不利，博彩尽输钱，九天玄女法，句句不虚言。', '招摇号木星，当之事莫行，相克行人阻，阴人口舌迎，梦寐多惊惧，屋响斧自鸣，阴阳消息理，万法弗违情。', '五鬼为天符，当门阴女谋，相克无好事，行路阻中途，走失难寻觅，道逢有尼姑，此星当门值，万事有灾除。', '神光跃青龙，财气喜重重，投入有酒食，赌彩最兴隆，更逢相生旺，休言克破凶，见贵安营寨，万事总吉同。', '吾将为咸池，当之尽不宜，出入多不利，相克有灾情，赌彩全输尽，求财空手回，仙人真妙语，愚人莫与知，动用虚惊退，反复逆风吹。', '坐临太阴星，百祸不相侵，求谋悉成就，知交有觅寻，回风归来路，恐有殃伏起，密语中记取，慎乎莫轻行。', '迎来天乙星，相逢百事兴，运用和合庆，茶酒喜相迎，求谋并嫁娶，好合有天成，祸福如神验，吉凶甚分明。');

  /**
   * 吉凶（玄空风水）
   * @var array
   */
  public static $LUCK_XUAN_KONG = array('吉', '凶', '凶', '吉', '凶', '吉', '凶', '吉', '吉');

  /**
   * 吉凶（奇门遁甲）
   * @var array
   */
  public static $LUCK_QI_MEN = array('大凶', '大凶', '小吉', '大吉', '大吉', '大吉', '小凶', '小吉', '小凶');

  /**
   * 阴阳（奇门遁甲）
   * @var array
   */
  public static $YIN_YANG_QI_MEN = array('阳', '阴', '阳', '阳', '阳', '阴', '阴', '阳', '阴');

  function __construct($index)
  {
    $this->index = $index;
  }

  public static function fromIndex($index)
  {
    return new NineStar($index);
  }

  /**
   * 获取九数
   * @return string 九数
   */
  public function getNumber()
  {
    return NineStar::$NUMBER[$this->index];
  }

  /**
   * 获取七色
   * @return string 七色
   */
  public function getColor()
  {
    return NineStar::$COLOR[$this->index];
  }

  /**
   * 获取五行
   * @return string 五行
   */
  public function getWuXing()
  {
    return NineStar::$WU_XING[$this->index];
  }


  /**
   * 获取方位
   * @return string 方位
   */
  public function getPosition()
  {
    return NineStar::$POSITION[$this->index];
  }

  /**
   * 获取方位描述
   * @return string 方位描述
   */
  public function getPositionDesc()
  {
    return LunarUtil::$POSITION_DESC [$this->getPosition()];
  }

  /**
   * 获取玄空九星名称
   * @return string 玄空九星名称
   */
  public function getNameInXuanKong()
  {
    return NineStar::$NAME_XUAN_KONG[$this->index];
  }

  /**
   * 获取北斗九星名称
   * @return string 北斗九星名称
   */
  public function getNameInBeiDou()
  {
    return NineStar::$NAME_BEI_DOU[$this->index];
  }

  /**
   * 获取奇门九星名称
   * @return string 奇门九星名称
   */
  public function getNameInQiMen()
  {
    return NineStar::$NAME_QI_MEN[$this->index];
  }

  /**
   * 获取太乙九神名称
   * @return string 太乙九神名称
   */
  public function getNameInTaiYi()
  {
    return NineStar::$NAME_TAI_YI[$this->index];
  }

  /**
   * 获取奇门九星吉凶
   * @return string 大吉/小吉/大凶/小凶
   */
  public function getLuckInQiMen()
  {
    return NineStar::$LUCK_QI_MEN[$this->index];
  }

  /**
   * 获取玄空九星吉凶
   * @return string 吉/凶
   */
  public function getLuckInXuanKong()
  {
    return NineStar::$LUCK_XUAN_KONG[$this->index];
  }

  /**
   * 获取奇门九星阴阳
   * @return string 阴/阳
   */
  public function getYinYangInQiMen()
  {
    return NineStar::$YIN_YANG_QI_MEN[$this->index];
  }

  /**
   * 获取太乙九神类型
   * @return string 吉神/凶神/安神
   */
  public function getTypeInTaiYi()
  {
    return NineStar::$TYPE_TAI_YI[$this->index];
  }

  /**
   * 获取八门（奇门遁甲）
   * @return string 八门
   */
  public function getBaMenInQiMen()
  {
    return NineStar::$BA_MEN_QI_MEN[$this->index];
  }

  /**
   * 获取太乙九神歌诀
   * @return string 太乙九神歌诀
   */
  public function getSongInTaiYi()
  {
    return NineStar::$SONG_TAI_YI[$this->index];
  }

  /**
   * 获取九星序号，从0开始
   * @return int 序号
   */
  public function getIndex()
  {
    return $this->index;
  }

  public function toString()
  {
    return $this->getNumber() . $this->getColor() . $this->getWuXing() . $this->getNameInBeiDou();
  }

  public function __toString()
  {
    return $this->toString();
  }

  public function toFullString()
  {
    $s = '';
    $s .= $this->getNumber();
    $s .= $this->getColor();
    $s .= $this->getWuXing();
    $s .= ' ';
    $s .= $this->getPosition();
    $s .= '(';
    $s .= $this->getPositionDesc();
    $s .= ') ';
    $s .= $this->getNameInBeiDou();
    $s .= ' 玄空[';
    $s .= $this->getNameInXuanKong();
    $s .= ' ';
    $s .= $this->getLuckInXuanKong();
    $s .= '] 奇门[';
    $s .= $this->getNameInQiMen();
    $s .= ' ';
    $s .= $this->getLuckInQiMen();
    if (strlen($this->getBaMenInQiMen()) > 0) {
      $s .= ' ';
      $s .= $this->getBaMenInQiMen();
      $s .= '门';
    }
    $s .= ' ';
    $s .= $this->getYinYangInQiMen();
    $s .= '] 太乙[';
    $s .= $this->getNameInTaiYi();
    $s .= ' ';
    $s .= $this->getTypeInTaiYi();
    $s .= ']';
    return $s;
  }

}
