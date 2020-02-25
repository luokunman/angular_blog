/*
 Navicat Premium Data Transfer

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 50531
 Source Host           : localhost:3306
 Source Schema         : blog_angular

 Target Server Type    : MySQL
 Target Server Version : 50531
 File Encoding         : 65001

 Date: 06/07/2019 15:44:39
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pre_admin
-- ----------------------------
DROP TABLE IF EXISTS `pre_admin`;
CREATE TABLE `pre_admin`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `username` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户名',
  `password` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '密码',
  `salt` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '密码盐',
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '头像',
  `register_time` int(11) NULL DEFAULT 0 COMMENT '注册时间',
  `last_login_time` int(11) NULL DEFAULT 0 COMMENT '最后登录时间',
  `status` tinyint(255) NULL DEFAULT 1 COMMENT '状态 1启用 0禁用',
  `groupid` int(11) UNSIGNED NULL DEFAULT NULL COMMENT '权限组id',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `groupid`(`groupid`) USING BTREE,
  CONSTRAINT `fk_pre_admin_pre_auth_group_1` FOREIGN KEY (`groupid`) REFERENCES `pre_auth_group` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of pre_admin
-- ----------------------------
INSERT INTO `pre_admin` VALUES (1, 'admin', '0c3b6e97b91a9f961d9a0a10c719956b', 'UXBCRf2VUD', NULL, 0, 1553218498, 1, 1);

-- ----------------------------
-- Table structure for pre_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `pre_auth_group`;
CREATE TABLE `pre_auth_group`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '角色名称',
  `status` tinyint(255) UNSIGNED NULL DEFAULT 1 COMMENT '状态：1启用 0禁用',
  `rules` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '权限规则id列表',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '权限角色表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of pre_auth_group
-- ----------------------------
INSERT INTO `pre_auth_group` VALUES (1, '超级管理员', 1, '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19');

-- ----------------------------
-- Table structure for pre_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `pre_auth_rule`;
CREATE TABLE `pre_auth_rule`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `pid` int(11) NULL DEFAULT 0 COMMENT '父级id',
  `name` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '规则唯一标识	',
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '规则中文名称',
  `status` tinyint(255) NULL DEFAULT 1 COMMENT '状态：1、正常 0禁用',
  `ismenu` tinyint(3) UNSIGNED NULL DEFAULT 1 COMMENT '是否显示该菜单 1显示 0隐藏',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '权限验证规则' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of pre_auth_rule
-- ----------------------------
INSERT INTO `pre_auth_rule` VALUES (1, 0, 'index.php', '后台首页', 1, 1);
INSERT INTO `pre_auth_rule` VALUES (2, 0, 'adminlist.php', '管理员', 1, 1);
INSERT INTO `pre_auth_rule` VALUES (3, 2, 'adminadd.php', '管理员添加', 1, 0);
INSERT INTO `pre_auth_rule` VALUES (4, 2, 'adminedit.php', '管理员编辑', 1, 0);
INSERT INTO `pre_auth_rule` VALUES (5, 2, 'admindelete.php', '管理员删除', 1, 0);
INSERT INTO `pre_auth_rule` VALUES (6, 0, 'grouplist.php', '角色管理', 1, 1);
INSERT INTO `pre_auth_rule` VALUES (7, 6, 'groupadd.php', '角色添加', 1, 0);
INSERT INTO `pre_auth_rule` VALUES (8, 6, 'groupedit.php', '角色编辑', 1, 0);
INSERT INTO `pre_auth_rule` VALUES (9, 6, 'groupdelete.php', '角色删除', 1, 0);
INSERT INTO `pre_auth_rule` VALUES (10, 0, 'rulelist.php', '规则管理', 1, 1);
INSERT INTO `pre_auth_rule` VALUES (11, 10, 'ruleadd.php', '规则添加', 1, 0);
INSERT INTO `pre_auth_rule` VALUES (12, 10, 'ruleedit.php', '规则编辑', 1, 0);
INSERT INTO `pre_auth_rule` VALUES (13, 10, 'ruledelete.php', '规则删除', 1, 0);
INSERT INTO `pre_auth_rule` VALUES (14, 0, 'commentlist.php', '评论管理', 1, 1);
INSERT INTO `pre_auth_rule` VALUES (15, 0, 'configlist.php', '系统配置', 1, 1);

-- ----------------------------
-- Table structure for pre_comment
-- ----------------------------
DROP TABLE IF EXISTS `pre_comment`;
CREATE TABLE `pre_comment`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `postid` int(11) UNSIGNED NULL DEFAULT NULL COMMENT '帖子id',
  `userid` int(11) UNSIGNED NULL DEFAULT NULL COMMENT '用户id',
  `like` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '点赞人列表 1,2,3,4,5',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '评论内容',
  `register_time` int(11) NULL DEFAULT 0 COMMENT '评论时间',
  `parentid` int(11) NULL DEFAULT 0 COMMENT '上级id 无限极评论',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `userid`(`userid`) USING BTREE,
  INDEX `postid`(`postid`) USING BTREE,
  CONSTRAINT `fk_pre_comment` FOREIGN KEY (`userid`) REFERENCES `pre_user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_pre_comment_1` FOREIGN KEY (`postid`) REFERENCES `pre_post` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '评论表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for pre_config
-- ----------------------------
DROP TABLE IF EXISTS `pre_config`;
CREATE TABLE `pre_config`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '配置中文名称',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '配置英文名称',
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '配置值',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '系统配置表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for pre_favorite
-- ----------------------------
DROP TABLE IF EXISTS `pre_favorite`;
CREATE TABLE `pre_favorite`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `userid` int(11) UNSIGNED NULL DEFAULT NULL COMMENT '用户id',
  `postid` int(11) UNSIGNED NULL DEFAULT NULL COMMENT '帖子id',
  `register_time` int(11) NULL DEFAULT 0 COMMENT '收藏时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `postid`(`postid`) USING BTREE,
  INDEX `userid`(`userid`) USING BTREE,
  CONSTRAINT `fk_pre_favorite` FOREIGN KEY (`postid`) REFERENCES `pre_post` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_pre_favorite_1` FOREIGN KEY (`userid`) REFERENCES `pre_user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '收藏表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for pre_gallery
-- ----------------------------
DROP TABLE IF EXISTS `pre_gallery`;
CREATE TABLE `pre_gallery`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `img` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `createtim` int(11) NULL DEFAULT NULL,
  `cateid` int(11) UNSIGNED NOT NULL,
  `type` enum('all','skill','book','party','expersience','music') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'all',
  `userid` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cateid`(`cateid`) USING BTREE,
  INDEX `userid`(`userid`) USING BTREE,
  CONSTRAINT `imgcate` FOREIGN KEY (`cateid`) REFERENCES `pre_imgcate` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of pre_gallery
-- ----------------------------
INSERT INTO `pre_gallery` VALUES (1, 'Vue', 'vue.png', NULL, NULL, 2, 'all', 0);

-- ----------------------------
-- Table structure for pre_imgcate
-- ----------------------------
DROP TABLE IF EXISTS `pre_imgcate`;
CREATE TABLE `pre_imgcate`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `img` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '文章分类表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of pre_imgcate
-- ----------------------------
INSERT INTO `pre_imgcate` VALUES (1, 'all', NULL, NULL);
INSERT INTO `pre_imgcate` VALUES (2, 'skill', '', NULL);
INSERT INTO `pre_imgcate` VALUES (3, 'book', '', NULL);
INSERT INTO `pre_imgcate` VALUES (4, 'party', '', NULL);
INSERT INTO `pre_imgcate` VALUES (5, 'experience', NULL, NULL);
INSERT INTO `pre_imgcate` VALUES (6, 'music', NULL, NULL);

-- ----------------------------
-- Table structure for pre_links
-- ----------------------------
DROP TABLE IF EXISTS `pre_links`;
CREATE TABLE `pre_links`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `createtime` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for pre_post
-- ----------------------------
DROP TABLE IF EXISTS `pre_post`;
CREATE TABLE `pre_post`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '标题',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '正文内容',
  `userid` int(11) UNSIGNED NULL DEFAULT NULL COMMENT '用户id',
  `accept` int(255) NULL DEFAULT 0 COMMENT '采纳用户id',
  `point` int(255) NULL DEFAULT 0 COMMENT '悬赏积分',
  `finish` int(255) NULL DEFAULT 0 COMMENT '是否完成 1完成 0未完成',
  `register_time` int(11) NULL DEFAULT 0 COMMENT '注册时间',
  `state` int(255) NULL DEFAULT 0 COMMENT '0无状态 1置顶 2精帖',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `userid`(`userid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '帖子表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of pre_post
-- ----------------------------
INSERT INTO `pre_post` VALUES (1, '悬赏技术asdasdas', '悬赏技术内容', 3, 0, 10, 0, 1562033598, 0);
INSERT INTO `pre_post` VALUES (2, 'jhgjhgjh', 'jhhgjhgjhgjhg', 3, 0, 10, 0, 1562033815, 0);

-- ----------------------------
-- Table structure for pre_user
-- ----------------------------
DROP TABLE IF EXISTS `pre_user`;
CREATE TABLE `pre_user`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `username` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户名',
  `password` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '密码',
  `salt` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '密码盐',
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '头像',
  `email` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '手机号码',
  `sex` tinyint(255) NULL DEFAULT 1 COMMENT '1男 0女',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '个人签名',
  `register_time` int(11) NULL DEFAULT 0 COMMENT '注册时间',
  `last_login_time` int(11) NULL DEFAULT 0 COMMENT '最后登录时间',
  `status` int(255) NULL DEFAULT 0 COMMENT '用户状态 0：正常； 1禁用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of pre_user
-- ----------------------------
INSERT INTO `pre_user` VALUES (3, 'demo', '530fbf81581a65ac2ece8b3c94f5be5c', 'n624aYhcMaQ4ismRsA75', 'rouken.jpg', '2925712506@qq.com', 0, 'dafsghjgkhl;kl\'asdasdasd', 1561608061, 1562029636, 0);
INSERT INTO `pre_user` VALUES (4, 'demo2', '0833abbc1ab422297c7933d03e0ee532', 'D4AhJZZ5SkjXj3fWkpHA', 'rouken.jpg', '123123@qq.com', 0, NULL, 1561608061, 1561770642, 0);
INSERT INTO `pre_user` VALUES (5, 'rouken', 'ef6654da3fbb00586c9ff8714f68c0b0', '4297f44b13955235245b2497399d7a93', 'rouken.jpg', '1013665172@qq.com', 0, NULL, 1562383722, 0, 0);
INSERT INTO `pre_user` VALUES (6, 'siyi', '49246c89b02dc8b1f198c22f292c4be6', '4297f44b13955235245b2497399d7a93', 'rouken.jpg', '1013665172@qq.com', 0, NULL, 1562384082, 0, 0);
INSERT INTO `pre_user` VALUES (7, 'lihao', '986eff74991ba5ed3c6d32e391322e96', '4297f44b13955235245b2497399d7a93', 'rouken.jpg', '1013665172@qq.com', 0, NULL, 1562384274, 0, 0);

-- ----------------------------
-- Table structure for pre_user_log
-- ----------------------------
DROP TABLE IF EXISTS `pre_user_log`;
CREATE TABLE `pre_user_log`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '描述内容',
  `userid` int(11) UNSIGNED NULL DEFAULT NULL COMMENT '用户id',
  `point` int(255) NULL DEFAULT 0 COMMENT '变动积分',
  `register_time` int(11) NULL DEFAULT 0 COMMENT '注册时间',
  `status` int(255) NULL DEFAULT 1 COMMENT '1、积分悬赏 2、采纳得分 3、签到 4、充值',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `userid`(`userid`) USING BTREE,
  CONSTRAINT `fk_pre_user_log` FOREIGN KEY (`userid`) REFERENCES `pre_user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '积分消费记录表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of pre_user_log
-- ----------------------------
INSERT INTO `pre_user_log` VALUES (1, 'demo发布悬赏积分--悬赏技术asdasdas', 3, 10, 1562033598, 1);
INSERT INTO `pre_user_log` VALUES (2, 'demo发布悬赏积分--jhgjhgjh', 3, 10, 1562033815, 1);

SET FOREIGN_KEY_CHECKS = 1;
