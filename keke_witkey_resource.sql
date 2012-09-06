/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50051
Source Host           : localhost:3306
Source Database       : keke_witkey

Target Server Type    : MYSQL
Target Server Version : 50051
File Encoding         : 65001

Date: 2012-09-06 18:17:57
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `keke_witkey_resource`
-- ----------------------------
DROP TABLE IF EXISTS `keke_witkey_resource`;
CREATE TABLE `keke_witkey_resource` (
  `resource_id` int(11) NOT NULL auto_increment,
  `resource_name` varchar(20) default NULL,
  `resource_url` varchar(100) default NULL,
  `submenu_id` varchar(20) default NULL,
  `listorder` int(11) default '0',
  PRIMARY KEY  (`resource_id`),
  KEY `resource_id` (`resource_id`),
  KEY `submenu_id` (`submenu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=149 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of keke_witkey_resource
-- ----------------------------
INSERT INTO keke_witkey_resource VALUES ('2', '支付接口', 'index.php?do=config&view=pay', '28', '5');
INSERT INTO keke_witkey_resource VALUES ('3', '财务分析', 'index.php?do=finance&view=report', '2', '2');
INSERT INTO keke_witkey_resource VALUES ('4', '财务明细', 'index.php?do=finance&view=all', '2', '1');
INSERT INTO keke_witkey_resource VALUES ('5', '提现审核', 'index.php?do=finance&view=withdraw', '2', '5');
INSERT INTO keke_witkey_resource VALUES ('6', '行业添加', 'index.php?do=task&view=industry_edit', '5', '2');
INSERT INTO keke_witkey_resource VALUES ('7', '行业管理', 'index.php?do=task&view=industry', '5', '1');
INSERT INTO keke_witkey_resource VALUES ('8', '技能管理', 'index.php?do=task&view=skill&op=list', '5', '3');
INSERT INTO keke_witkey_resource VALUES ('9', '任务留言', 'index.php?do=task&view=comment', '37', '0');
INSERT INTO keke_witkey_resource VALUES ('10', '添加用户', 'index.php?do=user&view=add', '7', '1');
INSERT INTO keke_witkey_resource VALUES ('11', '用户管理', 'index.php?do=user&view=list', '7', '2');
INSERT INTO keke_witkey_resource VALUES ('12', '添加系统组', 'index.php?do=user&view=group_add&op=add', '8', '0');
INSERT INTO keke_witkey_resource VALUES ('13', '系统组管理', 'index.php?do=user&view=group_list', '8', '0');
INSERT INTO keke_witkey_resource VALUES ('14', '分类管理', 'index.php?do=article&view=cat_list&type=art', '9', '3');
INSERT INTO keke_witkey_resource VALUES ('15', '文章添加', 'index.php?do=article&view=edit', '9', '2');
INSERT INTO keke_witkey_resource VALUES ('16', '文章管理', 'index.php?do=article&view=list', '9', '1');
INSERT INTO keke_witkey_resource VALUES ('19', '系统日志', 'index.php?do=tool&view=log', '10', '4');
INSERT INTO keke_witkey_resource VALUES ('20', '更新缓存', 'index.php?do=tool&view=cache&sbt_edit=1&ckb_obj_cache=1&ckb_tpl_cache=1', '10', '7');
INSERT INTO keke_witkey_resource VALUES ('21', '附件管理', 'index.php?do=tool&view=file', '10', '5');
INSERT INTO keke_witkey_resource VALUES ('22', '分类添加', 'index.php?do=article&view=cat_edit&type=art', '9', '4');
INSERT INTO keke_witkey_resource VALUES ('141', '地图接口', 'index.php?do=msg&view=map', '28', '2');
INSERT INTO keke_witkey_resource VALUES ('24', '技能添加', 'index.php?do=task&view=skill_edit', '5', '4');
INSERT INTO keke_witkey_resource VALUES ('77', '手机认证', 'index.php?do=auth&view=list&auth_code=mobile', '29', '4');
INSERT INTO keke_witkey_resource VALUES ('140', '微博关注', 'index.php?do=msg&view=attention', '0', '2');
INSERT INTO keke_witkey_resource VALUES ('28', '模板管理', 'index.php?do=config&view=tpl', '12', '1');
INSERT INTO keke_witkey_resource VALUES ('29', '标签管理', 'index.php?do=tpl&view=taglist', '12', '2');
INSERT INTO keke_witkey_resource VALUES ('30', '友情链接', 'index.php?do=tpl&view=link', '12', '3');
INSERT INTO keke_witkey_resource VALUES ('32', '广告管理', 'index.php?do=tpl&view=ad', '12', '4');
INSERT INTO keke_witkey_resource VALUES ('33', '客服管理', 'index.php?do=user&view=custom_list', '7', '20');
INSERT INTO keke_witkey_resource VALUES ('34', '全局配置', 'index.php?do=config&view=basic&op=info', '1', '0');
INSERT INTO keke_witkey_resource VALUES ('35', '会员整合', 'index.php?do=config&view=integration', '1', '20');
INSERT INTO keke_witkey_resource VALUES ('36', '信誉规则', 'index.php?do=config&view=mark', '14', '1');
INSERT INTO keke_witkey_resource VALUES ('37', '模型管理', 'index.php?do=config&view=model', '1', '10');
INSERT INTO keke_witkey_resource VALUES ('38', '认证项目', 'index.php?do=auth&view=item_list', '29', '0');
INSERT INTO keke_witkey_resource VALUES ('40', '客服留言', 'index.php?do=task&view=custom', '371', '0');
INSERT INTO keke_witkey_resource VALUES ('41', '导航菜单', 'index.php?do=config&view=nav', '1', '100');
INSERT INTO keke_witkey_resource VALUES ('42', '帮助管理', 'index.php?do=article&view=list&type=help', '17', '0');
INSERT INTO keke_witkey_resource VALUES ('43', '帮助添加', 'index.php?do=article&view=edit&type=help', '17', '0');
INSERT INTO keke_witkey_resource VALUES ('44', '帮助分类', 'index.php?do=article&view=cat_list&type=help', '17', '0');
INSERT INTO keke_witkey_resource VALUES ('45', '分类添加', 'index.php?do=article&view=cat_edit&type=help', '17', '0');
INSERT INTO keke_witkey_resource VALUES ('46', '店铺主题', 'index.php?do=shop&view=banner', '20', '0');
INSERT INTO keke_witkey_resource VALUES ('47', '添加主题', 'index.php?do=shop&view=edit_banner', '20', '0');
INSERT INTO keke_witkey_resource VALUES ('49', '用户组', 'index.php?do=group', '22', '0');
INSERT INTO keke_witkey_resource VALUES ('52', '案例管理', 'index.php?do=case&view=list', '37', '0');
INSERT INTO keke_witkey_resource VALUES ('53', '单页管理', 'index.php?do=article&view=list&type=single', '24', '0');
INSERT INTO keke_witkey_resource VALUES ('54', '单页添加', 'index.php?do=article&view=edit&type=single', '24', '1');
INSERT INTO keke_witkey_resource VALUES ('139', '购买记录', 'index.php?do=payitem&view=buy', '34', '1');
INSERT INTO keke_witkey_resource VALUES ('138', '服务项管理', 'index.php?do=payitem', '34', '0');
INSERT INTO keke_witkey_resource VALUES ('57', '动态管理', 'index.php?do=tpl&view=feed', '12', '3');
INSERT INTO keke_witkey_resource VALUES ('58', '推广关系管理', 'index.php?do=prom&view=relation', '27', '5');
INSERT INTO keke_witkey_resource VALUES ('59', '推广配置管理', 'index.php?do=prom&view=config', '27', '1');
INSERT INTO keke_witkey_resource VALUES ('60', '推广素材管理', 'index.php?do=prom&view=item', '0', '2');
INSERT INTO keke_witkey_resource VALUES ('61', '推广财务管理', 'index.php?do=prom&view=event', '27', '6');
INSERT INTO keke_witkey_resource VALUES ('63', 'OAuth登录', 'index.php?do=msg&view=weibo', '28', '1');
INSERT INTO keke_witkey_resource VALUES ('66', '短信配置', 'index.php?do=msg&view=config', '28', '3');
INSERT INTO keke_witkey_resource VALUES ('67', '短信发送', 'index.php?do=msg&view=send', '0', '4');
INSERT INTO keke_witkey_resource VALUES ('68', '银行认证', 'index.php?do=auth&view=list&auth_code=bank', '29', '1');
INSERT INTO keke_witkey_resource VALUES ('69', '企业认证', 'index.php?do=auth&view=list&auth_code=enterprise', '29', '2');
INSERT INTO keke_witkey_resource VALUES ('70', '实名认证', 'index.php?do=auth&view=list&auth_code=realname', '29', '3');
INSERT INTO keke_witkey_resource VALUES ('71', '邮箱认证', 'index.php?do=auth&view=list&auth_code=email', '29', '4');
INSERT INTO keke_witkey_resource VALUES ('73', '短信模板', 'index.php?do=msg&view=internal', '28', '5');
INSERT INTO keke_witkey_resource VALUES ('76', '充值审核', 'index.php?do=finance&view=recharge', '2', '4');
INSERT INTO keke_witkey_resource VALUES ('78', '互评配置', 'index.php?do=config&view=mark&op=config', '14', '2');
INSERT INTO keke_witkey_resource VALUES ('79', '互评记录', 'index.php?do=config&view=mark&op=log', '14', '3');
INSERT INTO keke_witkey_resource VALUES ('80', '维权管理', 'index.php?do=trans&view=rights', '30', '1');
INSERT INTO keke_witkey_resource VALUES ('81', '举报管理', 'index.php?do=trans&view=report', '30', '2');
INSERT INTO keke_witkey_resource VALUES ('82', '投诉管理', 'index.php?do=trans&view=complaint', '30', '3');
INSERT INTO keke_witkey_resource VALUES ('133', '联盟API', 'index.php?do=keke&view=account', '33', '1');
INSERT INTO keke_witkey_resource VALUES ('134', '推广财务', 'index.php?do=keke&view=finance', '33', '2');
INSERT INTO keke_witkey_resource VALUES ('135', '获取任务', 'index.php?do=keke&view=gettask', '33', '3');
INSERT INTO keke_witkey_resource VALUES ('137', '提交任务', 'index.php?do=keke&view=posttask', '33', '4');
INSERT INTO keke_witkey_resource VALUES ('142', '数据库管理', 'index.php?do=tool&view=dbbackup', '10', '0');
INSERT INTO keke_witkey_resource VALUES ('146', '服务介绍', 'index.php?do=tool&view=payitem', '39', '1');
INSERT INTO keke_witkey_resource VALUES ('147', '链接管理', 'index.php/admin/link', '40', '1');
INSERT INTO keke_witkey_resource VALUES ('148', '链接添加', 'index.php/admin/link/add', '40', '2');

-- ----------------------------
-- Table structure for `keke_witkey_resource_submenu`
-- ----------------------------
DROP TABLE IF EXISTS `keke_witkey_resource_submenu`;
CREATE TABLE `keke_witkey_resource_submenu` (
  `submenu_id` int(11) NOT NULL auto_increment,
  `submenu_name` varchar(20) default NULL,
  `menu_name` varchar(10) default NULL,
  `listorder` int(11) default '0',
  PRIMARY KEY  (`submenu_id`),
  KEY `submenu_id` (`submenu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of keke_witkey_resource_submenu
-- ----------------------------
INSERT INTO keke_witkey_resource_submenu VALUES ('1', '系统配置', 'config', '1');
INSERT INTO keke_witkey_resource_submenu VALUES ('2', '财务模块', 'finance', '0');
INSERT INTO keke_witkey_resource_submenu VALUES ('5', '行业技能', 'config', '2');
INSERT INTO keke_witkey_resource_submenu VALUES ('7', '用户管理', 'user', '0');
INSERT INTO keke_witkey_resource_submenu VALUES ('8', '系统组管理', 'user', '0');
INSERT INTO keke_witkey_resource_submenu VALUES ('9', '文章模块', 'article', '2');
INSERT INTO keke_witkey_resource_submenu VALUES ('10', '站长工具', 'tool', '1');
INSERT INTO keke_witkey_resource_submenu VALUES ('12', '模板标签', 'tool', '2');
INSERT INTO keke_witkey_resource_submenu VALUES ('14', '用户体系', 'user', '3');
INSERT INTO keke_witkey_resource_submenu VALUES ('17', '帮助模块', 'article', '3');
INSERT INTO keke_witkey_resource_submenu VALUES ('34', '增值服务', 'finance', '0');
INSERT INTO keke_witkey_resource_submenu VALUES ('24', '单页面管理', 'article', '5');
INSERT INTO keke_witkey_resource_submenu VALUES ('27', '本站推广', 'keke', '1');
INSERT INTO keke_witkey_resource_submenu VALUES ('28', '接口管理', 'config', '3');
INSERT INTO keke_witkey_resource_submenu VALUES ('29', '认证管理', 'user', '4');
INSERT INTO keke_witkey_resource_submenu VALUES ('30', '交易维权', 'user', '4');
INSERT INTO keke_witkey_resource_submenu VALUES ('33', '推广联盟', 'keke', '2');
INSERT INTO keke_witkey_resource_submenu VALUES ('37', '任务杂项', 'task', '8');
INSERT INTO keke_witkey_resource_submenu VALUES ('39', '增值服务', 'tool', '3');
INSERT INTO keke_witkey_resource_submenu VALUES ('40', '友情链接', 'demo', '0');
