/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50525
Source Host           : localhost:3306
Source Database       : kppw_google

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2012-10-28 18:15:21
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `keke_witkey_recharge`
-- ----------------------------
DROP TABLE IF EXISTS `keke_witkey_recharge`;
CREATE TABLE `keke_witkey_recharge` (
  `rid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` char(10) DEFAULT NULL,
  `bank` char(20) DEFAULT '0',
  `order_id` int(11) DEFAULT '0',
  `uid` int(10) DEFAULT NULL,
  `username` char(20) DEFAULT '0',
  `pay_time` int(11) DEFAULT '0',
  `cash` decimal(11,2) DEFAULT '0.00',
  `status` char(20) DEFAULT NULL,
  `pay_info` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`rid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=455 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of keke_witkey_recharge
-- ----------------------------
INSERT INTO keke_witkey_recharge VALUES ('370', 'online', 'alipayjs', '0', '5053', 'php2', '1340791081', '323.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('371', 'offline', 'chinabank', '0', '5053', 'php2', '1340791102', '323.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('372', 'online', 'yeepay', '0', '1', 'admin', '1341888143', '500.00', 'ok', '0');
INSERT INTO keke_witkey_recharge VALUES ('374', 'online', 'yeepay', '0', '5052', 'php1', '1341888937', '512.00', 'ok', '0');
INSERT INTO keke_witkey_recharge VALUES ('375', 'online', 'yeepay', '0', '1', 'admin', '1341888584', '56.00', 'ok', '0');
INSERT INTO keke_witkey_recharge VALUES ('376', 'online', 'yeepay', '0', '5057', 'php100', '1341889152', '100.00', 'ok', '0');
INSERT INTO keke_witkey_recharge VALUES ('377', 'online', 'alipayjs', '0', '5057', 'php100', '1341889842', '78.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('378', 'online', 'yeepay', '0', '5057', 'php100', '1341889849', '78.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('379', 'online', 'yeepay', '0', '5057', 'php100', '1341889873', '78.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('380', 'online', 'yeepay', '0', '5057', 'php100', '1341889880', '78.00', 'ok', '0');
INSERT INTO keke_witkey_recharge VALUES ('381', 'online', 'yeepay', '0', '5057', 'php100', '1341890081', '2323.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('382', 'online', 'yeepay', '0', '5057', 'php100', '1341890127', '2323.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('383', 'online', 'yeepay', '0', '5057', 'php100', '1341890137', '56.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('384', 'online', 'yeepay', '0', '5057', 'php100', '1341890159', '56.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('385', 'online', 'tenpay', '0', '1', 'admin', '1346639390', '10.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('386', 'online', 'paypal', '0', '1', 'admin', '1343097210', '100.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('387', 'online', 'yeepay', '0', '1', 'admin', '1341993059', '80.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('388', 'online', 'yeepay', '0', '1', 'admin', '1341993066', '80.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('389', 'online', 'yeepay', '0', '1', 'admin', '1341993504', '900.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('390', 'online', 'yeepay', '0', '1', 'admin', '1341993536', '45.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('391', 'online', 'yeepay', '0', '1', 'admin', '1341993654', '45.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('394', 'offline', 'icbc', '0', '5066', 'lele', '1342677606', '200.00', 'ok', '200');
INSERT INTO keke_witkey_recharge VALUES ('395', 'offline', 'icbc', '0', '1', 'admin', '1343097613', '319.00', 'ok', 'gggggggggggggg');
INSERT INTO keke_witkey_recharge VALUES ('398', 'offline', 'icbc', '0', '1', 'admin', '1343443956', '100.00', 'ok', 'llllllllllllllllll');
INSERT INTO keke_witkey_recharge VALUES ('400', 'online', 'tenpay', '0', '5041', 'keke321yuza', '1343614562', '10000.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('401', 'offline', 'icbc', '0', '5041', 'keke321yuza', '1343614599', '10000.00', 'ok', '0');
INSERT INTO keke_witkey_recharge VALUES ('403', 'offline', 'aboc', '0', '5066', 'lele', '1344490471', '200.00', 'ok', 'dyhdfyruyrgtu');
INSERT INTO keke_witkey_recharge VALUES ('404', 'online', 'aboc', '0', '1', 'admin', '1344490754', '100.00', 'ok', 'weyyty');
INSERT INTO keke_witkey_recharge VALUES ('405', 'online', 'cib', '0', '1', 'admin', '1346145770', '1000.00', 'wait', 'trgryryrdtu');
INSERT INTO keke_witkey_recharge VALUES ('419', 'online', 'icbc', '0', '5099', 'keke001', '1345887139', '1000.00', 'ok', 'gfhgggg');
INSERT INTO keke_witkey_recharge VALUES ('420', 'online', 'cib', '0', '5099', 'keke001', '1345887340', '2222.00', 'ok', '0');
INSERT INTO keke_witkey_recharge VALUES ('409', 'online', 'icbc', '0', '1', 'admin', '1344498598', '200.00', 'ok', 'we4t4t6w346t4wtw');
INSERT INTO keke_witkey_recharge VALUES ('410', 'online', 'icbc', '0', '1', 'admin', '1344498617', '200.00', 'ok', 'retryryututyu');
INSERT INTO keke_witkey_recharge VALUES ('411', 'online', 'icbc', '0', '1', 'admin', '1344498683', '100.00', 'ok', 'ryr5u65uir67');
INSERT INTO keke_witkey_recharge VALUES ('412', 'online', 'icbc', '0', '1', 'admin', '1344498694', '200.00', 'ok', 'r567586r8u68');
INSERT INTO keke_witkey_recharge VALUES ('413', 'online', 'icbc', '0', '1', 'admin', '1344498789', '200.00', 'ok', 'ewrtewtwtertr');
INSERT INTO keke_witkey_recharge VALUES ('414', 'online', 'icbc', '0', '1', 'admin', '1344498857', '200.00', 'ok', 'et6rytrutyutyi');
INSERT INTO keke_witkey_recharge VALUES ('415', 'online', 'icbc', '0', '1', 'admin', '1344498956', '100.00', 'ok', 'y6ryuiyiyti');
INSERT INTO keke_witkey_recharge VALUES ('416', 'online', 'icbc', '0', '1', 'admin', '1344498991', '100.00', 'ok', 'yiygiyuguiyuiououiouio');
INSERT INTO keke_witkey_recharge VALUES ('421', 'online', 'yeepay', '0', '5099', 'keke001', '1346034046', '1000.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('422', 'online', 'alipayjs', '0', '5099', 'keke001', '1346034428', '0.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('423', 'online', 'tenpay', '0', '5099', 'keke001', '1346396777', '0.01', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('424', 'online', 'chinabank', '0', '5099', 'keke001', '1346034033', '1000.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('425', 'online', 'paypal', '0', '5099', 'keke001', '1346034101', '0.10', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('426', 'online', 'cmb', '0', '5099', 'keke001', '1346034654', '1.00', 'ok', '0');
INSERT INTO keke_witkey_recharge VALUES ('427', 'online', 'boc', '0', '5100', 'keke002', '1346116849', '1000.00', 'ok', '0');
INSERT INTO keke_witkey_recharge VALUES ('428', 'online', 'yeepay', '0', '1', 'admin', '1346145731', '1122.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('429', 'online', 'icbc', '0', '1', 'admin', '1346294678', '22.00', 'ok', '222222');
INSERT INTO keke_witkey_recharge VALUES ('430', 'online', 'icbc', '0', '5099', 'keke001', '1346396838', '0.02', 'ok', '0');
INSERT INTO keke_witkey_recharge VALUES ('431', 'online', 'tenpay', '0', '5157', 'ppoo', '1346398116', '12.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('432', 'online', 'alipayjs', '0', '5157', 'ppoo', '1346397233', '100.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('433', 'online', 'chinabank', '0', '5157', 'ppoo', '1346397269', '100.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('434', 'online', 'yeepay', '0', '5157', 'ppoo', '1346397283', '100.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('441', 'online', 'tenpay', '0', '5108', 'danren001', '1346401385', '11.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('442', 'online', 'icbc', '0', '5156', 'keke008', '1346408103', '100.00', 'ok', '0');
INSERT INTO keke_witkey_recharge VALUES ('443', 'online', 'icbc', '0', '5156', 'keke008', '1346408274', '1.00', 'ok', '0');
INSERT INTO keke_witkey_recharge VALUES ('444', 'online', 'icbc', '0', '1', 'admin', '1346410734', '100.00', 'ok', '111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111');
INSERT INTO keke_witkey_recharge VALUES ('445', 'online', 'cib', '0', '5156', 'keke008', '1346637215', '0.22', 'ok', 'jhhhhhhhhhhhhhhhhhhhhhhhhhh');
INSERT INTO keke_witkey_recharge VALUES ('447', 'online', 'cib', '0', '5156', 'keke008', '1346637414', '12.00', 'ok', '0');
INSERT INTO keke_witkey_recharge VALUES ('448', 'online', 'cib', '0', '5156', 'keke008', '1346637555', '1000.00', 'ok', '10002012-09-03');
INSERT INTO keke_witkey_recharge VALUES ('449', 'online', 'icbc', '0', '1', 'admin', '1346637761', '11111.00', 'ok', '11111111111');
INSERT INTO keke_witkey_recharge VALUES ('450', 'online', 'icbc', '0', '1', 'admin', '1346637865', '120.00', 'ok', '0');
INSERT INTO keke_witkey_recharge VALUES ('451', 'online', 'icbc', '0', '1', 'admin', '1346638059', '22222.00', 'ok', '娃娃娃娃娃娃娃娃娃娃娃娃娃娃娃娃娃娃娃娃娃娃娃娃娃娃娃娃');
INSERT INTO keke_witkey_recharge VALUES ('452', 'online', 'tenpay', '0', '5137', 'wl', '1346641944', '100.00', 'wait', '0');
INSERT INTO keke_witkey_recharge VALUES ('453', 'online', 'icbc', '0', '5137', 'wl', '1346638733', '100.00', 'ok', '0');
INSERT INTO keke_witkey_recharge VALUES ('454', 'online', 'icbc', '0', '5156', 'keke008', '1346640034', '100000.00', 'ok', '100000');

-- ----------------------------
-- Table structure for `keke_witkey_withdraw`
-- ----------------------------
DROP TABLE IF EXISTS `keke_witkey_withdraw`;
CREATE TABLE `keke_witkey_withdraw` (
  `wid` int(11) NOT NULL AUTO_INCREMENT,
  `cash` decimal(10,2) DEFAULT '0.00',
  `uid` int(11) DEFAULT '0',
  `username` varchar(50) DEFAULT NULL,
  `bank_username` char(20) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_account` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `on_time` int(11) DEFAULT '0',
  `op_uid` int(11) DEFAULT '0',
  `op_username` varchar(50) DEFAULT NULL,
  `op_time` int(11) DEFAULT '0',
  `type` char(10) DEFAULT '0',
  `mem` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`wid`)
) ENGINE=MyISAM AUTO_INCREMENT=175 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of keke_witkey_withdraw
-- ----------------------------
INSERT INTO keke_witkey_withdraw VALUES ('172', '500.00', '1', 'admin', 'asd', 'alipayjs', 'asdasd@qq.com', '0', '0', '1', 'admin', '0', 'online', null);
INSERT INTO keke_witkey_withdraw VALUES ('173', '600.00', '1', 'admin', 'boc', 'boc', 'ads', '0', '0', '0', null, '0', 'offline', '343434343434');
INSERT INTO keke_witkey_withdraw VALUES ('174', '800.00', '1', 'admin', 'icbo', 'icbc', 'adasd阿苏大阿', '0', '0', '0', null, '0', 'offline', '厅使用价值工可耕地');
