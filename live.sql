/*
Navicat MySQL Data Transfer

Source Server         : 47.100.95.220
Source Server Version : 50646
Source Host           : 47.100.95.220:3306
Source Database       : live

Target Server Type    : MYSQL
Target Server Version : 50646
File Encoding         : 65001

Date: 2020-01-09 20:50:12
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `live_chart`
-- ----------------------------
DROP TABLE IF EXISTS `live_chart`;
CREATE TABLE `live_chart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '聊天室表',
  `game_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '赛事id',
  `user_id` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `content` varchar(200) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of live_chart
-- ----------------------------

-- ----------------------------
-- Table structure for `live_game`
-- ----------------------------
DROP TABLE IF EXISTS `live_game`;
CREATE TABLE `live_game` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '直播表',
  `a_id` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'A球队',
  `b_id` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'B球队',
  `a_score` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'A队比分',
  `b_score` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'B队比分',
  `narrator` varchar(20) NOT NULL DEFAULT '' COMMENT '直播员',
  `image` varchar(20) NOT NULL DEFAULT '',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of live_game
-- ----------------------------

-- ----------------------------
-- Table structure for `live_outs`
-- ----------------------------
DROP TABLE IF EXISTS `live_outs`;
CREATE TABLE `live_outs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '赛况表',
  `game_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '直播ID',
  `team_id` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '球队id',
  `content` varchar(200) NOT NULL DEFAULT '' COMMENT '内容',
  `image` varchar(200) NOT NULL DEFAULT '',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '第几节',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of live_outs
-- ----------------------------
INSERT INTO `live_outs` VALUES ('1', '1', '1', 'dasdsad', '/upload/20200105/e54e6633ab24c88f72cffc0c76479819.jpg', '2', '0', '1578229326');
INSERT INTO `live_outs` VALUES ('2', '1', '1', 'wdawad', '/upload/20200105/e54e6633ab24c88f72cffc0c76479819.jpg', '2', '0', '1578229371');
INSERT INTO `live_outs` VALUES ('3', '1', '1', 'dasdsad', '/upload/20200105/e54e6633ab24c88f72cffc0c76479819.jpg', '2', '0', '1578232660');
INSERT INTO `live_outs` VALUES ('4', '1', '0', '213312', '', '1', '0', '1578233106');
INSERT INTO `live_outs` VALUES ('5', '1', '0', '213312', '', '1', '0', '1578233242');
INSERT INTO `live_outs` VALUES ('6', '1', '0', '213312', '', '1', '0', '1578233272');
INSERT INTO `live_outs` VALUES ('7', '1', '1', '马刺牛逼', '', '1', '0', '1578233442');
INSERT INTO `live_outs` VALUES ('8', '1', '1', '奥利给', '', '1', '0', '1578233490');
INSERT INTO `live_outs` VALUES ('9', '1', '0', '奥利给', '', '1', '0', '1578233560');
INSERT INTO `live_outs` VALUES ('10', '1', '0', '奥利给', '', '1', '0', '1578233763');
INSERT INTO `live_outs` VALUES ('11', '1', '1', '21313', 'http://47.100.95.220:8811/upload/20200105/bc731621d233734d5ed3e8e2d6c7edf9.jpg', '1', '0', '1578233977');
INSERT INTO `live_outs` VALUES ('12', '1', '1', '21313', 'http://47.100.95.220:8811/upload/20200105/bc731621d233734d5ed3e8e2d6c7edf9.jpg', '1', '0', '1578233990');
INSERT INTO `live_outs` VALUES ('13', '1', '1', '21313', 'http://47.100.95.220:8811/upload/20200105/bc731621d233734d5ed3e8e2d6c7edf9.jpg', '1', '0', '1578234098');
INSERT INTO `live_outs` VALUES ('14', '1', '1', '加油', 'http://47.100.95.220:8811/upload/20200105/823251cc3a9a7dbb433fcb91ee4d35d2.jpg', '1', '0', '1578234117');
INSERT INTO `live_outs` VALUES ('15', '1', '1', '冲鸭！', 'http://47.100.95.220:8811/upload/20200107/16479acccd6f00bb8de2cfde0dc06781.jpg', '1', '0', '1578403585');
INSERT INTO `live_outs` VALUES ('16', '1', '2', '打完大无', '', '1', '0', '1578403630');
INSERT INTO `live_outs` VALUES ('17', '1', '1', '111', '', '1', '0', '1578405641');
INSERT INTO `live_outs` VALUES ('18', '1', '0', '', '', '1', '0', '1578405661');
INSERT INTO `live_outs` VALUES ('19', '1', '0', '', '', '1', '0', '1578405667');

-- ----------------------------
-- Table structure for `live_player`
-- ----------------------------
DROP TABLE IF EXISTS `live_player`;
CREATE TABLE `live_player` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '',
  `image` varchar(20) NOT NULL DEFAULT '',
  `age` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `position` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of live_player
-- ----------------------------

-- ----------------------------
-- Table structure for `live_team`
-- ----------------------------
DROP TABLE IF EXISTS `live_team`;
CREATE TABLE `live_team` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT COMMENT '球队表',
  `name` varchar(20) NOT NULL DEFAULT '',
  `image` varchar(20) NOT NULL DEFAULT '',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '球队分区，西部1 东部2',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of live_team
-- ----------------------------
INSERT INTO `live_team` VALUES ('1', '马刺', '/live/imgs/team1.png', '1', '1578211929', '0');
INSERT INTO `live_team` VALUES ('2', '火箭', '/live/imgs/team2.png', '1', '1578211929', '0');
