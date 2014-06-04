-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 06 月 04 日 15:43
-- 服务器版本: 5.1.41
-- PHP 版本: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `xuehaitiku`
--

-- --------------------------------------------------------

--
-- 表的结构 `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '试卷分类',
  `name` varchar(20) NOT NULL,
  `subject_id` int(11) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `category`
--


-- --------------------------------------------------------

--
-- 表的结构 `exam_bank`
--

CREATE TABLE IF NOT EXISTS `exam_bank` (
  `exam_bank_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '题库',
  `name` varchar(45) NOT NULL,
  `price` float NOT NULL DEFAULT '0' COMMENT '元为单位',
  `order` smallint(6) NOT NULL DEFAULT '1',
  `icon` varchar(255) DEFAULT NULL COMMENT '题库的图标',
  PRIMARY KEY (`exam_bank_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `exam_bank`
--

INSERT INTO `exam_bank` (`exam_bank_id`, `name`, `price`, `order`, `icon`) VALUES
(1, '公务员', 30, 1, '20140517122846109.png'),
(2, '高考', 12, 1, '20140517122702549.png'),
(3, '中考', 20, 1, '20140517122755754.png'),
(4, '证券考试', 20, 1, '20140517122829908.png');

-- --------------------------------------------------------

--
-- 表的结构 `exam_paper`
--

CREATE TABLE IF NOT EXISTS `exam_paper` (
  `exam_paper_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '试卷',
  `subject_id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `short_name` varchar(45) DEFAULT NULL COMMENT '简称',
  `score` smallint(6) DEFAULT '0' COMMENT '总分',
  `recommendation` tinyint(4) DEFAULT NULL COMMENT '推荐值',
  `category_id` int(11) DEFAULT '0' COMMENT '所属分类，默认不属任何类',
  `time_length` smallint(6) DEFAULT '0' COMMENT '考试时间，以秒为单位',
  `publish_time` timestamp NULL DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状',
  `is_real` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`exam_paper_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `exam_paper`
--

INSERT INTO `exam_paper` (`exam_paper_id`, `subject_id`, `name`, `short_name`, `score`, `recommendation`, `category_id`, `time_length`, `publish_time`, `status`, `is_real`) VALUES
(1, 2, '2013年北京市公务员考试真题', '', 100, 4, 0, 120, '2014-06-02 15:24:07', 2, 1),
(2, 2, '2014年北京市公务员考试真题', '2014年北京市公务员考试真题', 0, 3, 0, 180, '0000-00-00 00:00:00', 0, 1),
(3, 2, '2015年北京市公务员考试真题', '', 0, 1, 0, 120, '0000-00-00 00:00:00', 0, 1),
(4, 2, '2010年北京市公务员考试真题', '', 0, 0, 0, 120, '0000-00-00 00:00:00', 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `exam_paper_category`
--

CREATE TABLE IF NOT EXISTS `exam_paper_category` (
  `exam_paper_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `exam_paper_id` int(11) NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`exam_paper_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `exam_paper_category`
--


-- --------------------------------------------------------

--
-- 表的结构 `exam_paper_instance`
--

CREATE TABLE IF NOT EXISTS `exam_paper_instance` (
  `exam_paper_instance_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '生成的卷子实例，即用户做了的',
  `instance_type` int(2) NOT NULL COMMENT '试卷类型：真题、专项训练、错题训练、收藏本训练等',
  `exam_paper_id` int(11) NOT NULL DEFAULT '0' COMMENT '如果试卷是随机生成的，则0',
  `exam_point_id` int(11) NOT NULL COMMENT '如果试卷不是随机生成的，则为0',
  `user_id` int(11) NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `elapsed_time` smallint(6) NOT NULL COMMENT '已经耗时（秒）',
  `is_completed` tinyint(4) NOT NULL COMMENT '是否已交卷',
  PRIMARY KEY (`exam_paper_instance_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- 转存表中的数据 `exam_paper_instance`
--

INSERT INTO `exam_paper_instance` (`exam_paper_instance_id`, `instance_type`, `exam_paper_id`, `exam_point_id`, `user_id`, `start_time`, `elapsed_time`, `is_completed`) VALUES
(1, 1, 0, 8, 1, '2014-06-04 21:14:34', 14, 1),
(2, 2, 0, 8, 1, '2014-06-04 21:14:50', 4, 0),
(3, 0, 1, 0, 1, '2014-06-04 21:15:04', 4, 1),
(4, 2, 0, 8, 1, '2014-06-04 21:42:43', 0, 0),
(5, 1, 0, 8, 1, '2014-06-04 21:50:14', 0, 1),
(6, 1, 0, 8, 1, '2014-06-04 22:03:59', 0, 0),
(7, 1, 0, 8, 1, '2014-06-04 22:04:11', 0, 1),
(8, 1, 0, 12, 1, '2014-06-04 22:04:24', 0, 1),
(9, 0, 1, 0, 1, '2014-06-04 22:31:20', 0, 1),
(10, 0, 1, 0, 1, '2014-06-04 22:33:04', 0, 0),
(11, 2, 0, 9, 1, '2014-06-04 22:36:28', 0, 0),
(12, 2, 0, 8, 1, '2014-06-04 22:51:07', 0, 0),
(13, 1, 0, 8, 1, '2014-06-04 22:51:18', 0, 0),
(14, 2, 0, 8, 1, '2014-06-04 22:52:17', 0, 1),
(15, 2, 0, 8, 1, '2014-06-04 22:53:58', 0, 1),
(16, 2, 0, 8, 1, '2014-06-04 22:56:13', 0, 0),
(17, 2, 0, 8, 1, '2014-06-04 22:57:17', 0, 0),
(18, 2, 0, 8, 1, '2014-06-04 23:02:21', 0, 0),
(19, 2, 0, 8, 1, '2014-06-04 23:03:07', 0, 1),
(20, 2, 0, 8, 1, '2014-06-04 23:03:12', 0, 0),
(21, 2, 0, 8, 1, '2014-06-04 23:03:55', 0, 0),
(22, 2, 0, 9, 1, '2014-06-04 23:07:20', 0, 0),
(23, 2, 0, 14, 1, '2014-06-04 23:22:53', 0, 0),
(24, 3, 0, 10, 1, '2014-06-04 23:23:58', 4, 1);

-- --------------------------------------------------------

--
-- 表的结构 `exam_paper_question`
--

CREATE TABLE IF NOT EXISTS `exam_paper_question` (
  `exam_paper_question_id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_paper_id` int(11) NOT NULL,
  `question_block_id` int(11) NOT NULL DEFAULT '0',
  `question_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0: 备',
  `sequence` smallint(6) NOT NULL DEFAULT '0' COMMENT '题号',
  PRIMARY KEY (`exam_paper_question_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- 转存表中的数据 `exam_paper_question`
--

INSERT INTO `exam_paper_question` (`exam_paper_question_id`, `exam_paper_id`, `question_block_id`, `question_id`, `status`, `sequence`) VALUES
(1, 1, 1, 10, 0, 1),
(2, 1, 2, 7, 0, 1),
(3, 1, 3, 8, 0, 1),
(4, 1, 3, 9, 0, 2),
(5, 1, 2, 17, 0, 2),
(6, 1, 2, 16, 0, 3),
(7, 1, 2, 15, 0, 4),
(8, 1, 2, 14, 0, 5),
(9, 1, 1, 13, 0, 2),
(10, 1, 1, 12, 0, 3),
(11, 1, 1, 11, 0, 4),
(12, 1, 3, 18, 0, 3),
(13, 1, 3, 19, 0, 4);

-- --------------------------------------------------------

--
-- 表的结构 `exam_point`
--

CREATE TABLE IF NOT EXISTS `exam_point` (
  `exam_point_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '考点树',
  `name` varchar(45) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '如果为0表示顶级考点',
  `subject_id` int(11) NOT NULL,
  `order` smallint(6) NOT NULL DEFAULT '1',
  `visible` tinyint(4) NOT NULL DEFAULT '1' COMMENT '前台是否显示，1显示，0不显示',
  `description` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`exam_point_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- 转存表中的数据 `exam_point`
--

INSERT INTO `exam_point` (`exam_point_id`, `name`, `pid`, `subject_id`, `order`, `visible`, `description`) VALUES
(1, '人文', 0, 1, 1, 1, ''),
(2, '文学', 1, 1, 1, 1, ''),
(3, '地理', 1, 1, 2, 1, ''),
(4, '历史', 1, 1, 3, 1, ''),
(5, '体育', 0, 1, 2, 1, ''),
(6, '球类', 5, 1, 1, 1, ''),
(7, '游泳', 5, 1, 2, 1, ''),
(8, '人文', 0, 2, 3, 1, ''),
(9, '历史', 8, 2, 1, 1, ''),
(10, '地理', 8, 2, 2, 1, ''),
(11, '文学', 8, 2, 3, 1, ''),
(12, '体育', 0, 2, 4, 1, ''),
(13, '篮球', 12, 2, 1, 1, ''),
(14, '足球', 12, 2, 2, 1, ''),
(15, '游泳', 12, 2, 3, 1, ''),
(16, '近代历史', 9, 2, 1, 1, ''),
(17, '近代文学', 2, 1, 1, 1, ''),
(18, '中国地理', 3, 1, 1, 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `material`
--

CREATE TABLE IF NOT EXISTS `material` (
  `material_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '材料',
  `content` text NOT NULL,
  `exam_paper_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  PRIMARY KEY (`material_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `material`
--

INSERT INTO `material` (`material_id`, `content`, `exam_paper_id`, `subject_id`) VALUES
(1, '<p>测试材料题<br/></p>', 0, 1),
(3, '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 退休职工满师傅是回民，家住北城。他每周都要去一次句改建后的牛街，先是转着古老而年轻的清真寺漫步一遭，然后再到牛街清真食品超市采购，“那儿的\r\n牛羊肉都是按穆斯林规矩宰杀出来的。”满师傅赞不绝口，“牛肉炖着吃可以挑‘肋条’、‘腰窝’，酱着吃有‘腱子’。，切好片的‘小肥牛’小包装，红里透白\r\n勾人馋虫；涮羊肉讲究‘三叉’、‘黄瓜条’，那儿的货又多又新鲜。”牛街给满师傅家节假日三代人聚餐带来了方便和欢愉。</p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 有千年历史的北京市\r\n南城牛街地区是北京最大的穆斯林聚居区，目前，这里仅回族居民就有1万多人。改建前，街巷狭窄，市政基础设施落后，低矮破旧的危房连街成片，人均住房面积\r\n只有5.1平方米，1997年牛街地区危改工程起动，这是北京市政府，在全市最先实施的危改面积最大、拆迁户数量多，少数民族比例最高的危改小区。\r\n2004年两期工程胜利完成。</p>', 0, 2),
(4, '<p>2012年全国国道网车流量较大的地区主要集中在北京、天津、上海、江苏、浙江、广东和\r\n山东，上述省市国道网的日平均交通量均超过2万辆。全国国道网日平均行驶量为244883万车公里，北京、天津、河北、山西、上海、浙江、湖北、广东的国\r\n道年平均拥挤度均超过0.6。其中，国家高速公路日平均交通量为22181辆，日平均行驶量为148742万车公里；普通国道日平均交通量为10845\r\n辆，日平均行驶量为111164万车公里。全国高速公路日平均交通量为21305辆，日平均行驶量为204717万车公里。（注：交通拥挤度指公路上某一\r\n路段折算交通量与适应交通量的比值，反映交通的繁忙程度。）</p><p><img src="http://ytk.fbcontent.cn/api/xingce/images/1428cc28c59f5f2.jpg" _src="http://ytk.fbcontent.cn/api/xingce/images/1428cc28c59f5f2.jpg"/></p><p><br/></p>', 0, 2);

-- --------------------------------------------------------

--
-- 表的结构 `paper_recommendation`
--

CREATE TABLE IF NOT EXISTS `paper_recommendation` (
  `paper_recommendation_id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) NOT NULL,
  `exam_paper_id` int(11) NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT '1' COMMENT '顺序',
  `difficuty` float NOT NULL DEFAULT '1',
  PRIMARY KEY (`paper_recommendation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `paper_recommendation`
--

INSERT INTO `paper_recommendation` (`paper_recommendation_id`, `subject_id`, `exam_paper_id`, `sequence`, `difficuty`) VALUES
(1, 2, 3, 1, 0.4),
(2, 2, 1, 2, 0.1);

-- --------------------------------------------------------

--
-- 表的结构 `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `exam_bank_id` int(11) NOT NULL,
  `expiry` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `payment`
--


-- --------------------------------------------------------

--
-- 表的结构 `pay_record`
--

CREATE TABLE IF NOT EXISTS `pay_record` (
  `payment_record_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '支付记录',
  `user_id` int(11) NOT NULL,
  `exam_bank_id` int(11) NOT NULL,
  `money` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`payment_record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `pay_record`
--


-- --------------------------------------------------------

--
-- 表的结构 `question`
--

CREATE TABLE IF NOT EXISTS `question` (
  `question_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '题目',
  `material_id` int(11) NOT NULL DEFAULT '0',
  `answer` varchar(50) NOT NULL COMMENT '答案,以"|"分割',
  `question_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1：单',
  `subject_id` int(11) NOT NULL,
  PRIMARY KEY (`question_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- 转存表中的数据 `question`
--

INSERT INTO `question` (`question_id`, `material_id`, `answer`, `question_type`, `subject_id`) VALUES
(7, 0, '0', 2, 2),
(8, 3, '1', 0, 2),
(9, 3, '1|2', 1, 2),
(10, 0, '2', 0, 2),
(11, 0, '1', 0, 2),
(12, 0, '2|3', 1, 2),
(13, 0, '2', 0, 2),
(14, 0, '0', 2, 2),
(15, 0, '1', 2, 2),
(16, 0, '0', 2, 2),
(17, 0, '0', 2, 2),
(18, 4, '2', 0, 2),
(19, 4, '1', 0, 2);

-- --------------------------------------------------------

--
-- 表的结构 `question_answer_option`
--

CREATE TABLE IF NOT EXISTS `question_answer_option` (
  `question_answer_option_id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `index` tinyint(4) NOT NULL,
  PRIMARY KEY (`question_answer_option_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=64 ;

--
-- 转存表中的数据 `question_answer_option`
--

INSERT INTO `question_answer_option` (`question_answer_option_id`, `question_id`, `description`, `index`) VALUES
(32, 8, '<p>巨变 &nbsp;方向 &nbsp; &nbsp; &nbsp; <br/></p>', 0),
(33, 8, '<p>蜕变 &nbsp;可能</p>', 1),
(34, 8, '<p>转变 &nbsp;选择 &nbsp; &nbsp; &nbsp; &nbsp;</p>', 2),
(35, 8, '<p>激变 &nbsp;途径 &nbsp; &nbsp; &nbsp; &nbsp;</p>', 3),
(36, 9, '<p>本本主义 &nbsp; &nbsp; &nbsp; &nbsp;</p>', 0),
(37, 9, '<p>沾沾自喜 &nbsp; &nbsp; &nbsp; &nbsp;</p>', 1),
(38, 9, '<p>官官相护 &nbsp; &nbsp; &nbsp; &nbsp;</p>', 2),
(39, 9, '<p>陈陈相因 &nbsp; &nbsp; &nbsp; &nbsp;</p>', 3),
(40, 10, '<p>否认绝对真理的存在 &nbsp; &nbsp; &nbsp; &nbsp;</p>', 0),
(41, 10, '<p>认为这个世界无须认识 &nbsp; &nbsp; &nbsp; &nbsp;</p>', 1),
(42, 10, '<p>政治上权威主义的根据 &nbsp; &nbsp; &nbsp; &nbsp;</p>', 2),
(43, 10, '<p>一种绝对化的主张 &nbsp; &nbsp; &nbsp; &nbsp;</p>', 3),
(44, 11, '<p>①③②④ &nbsp; &nbsp; &nbsp; &nbsp;</p>', 0),
(45, 11, '<p>③④①② &nbsp; &nbsp; &nbsp; &nbsp;</p>', 1),
(46, 11, '<p>③①④② &nbsp; &nbsp; &nbsp; &nbsp;</p>', 2),
(47, 11, '<p>①③④② &nbsp; &nbsp; &nbsp; &nbsp;</p>', 3),
(48, 12, '<p>宇航员可使用特定的加热器对食品进行加热 &nbsp; &nbsp; &nbsp; &nbsp;</p>', 0),
(49, 12, '<p>宇航员从太空返回地面后，失重状态消失，质量会有所增加 &nbsp; &nbsp; &nbsp; &nbsp;</p>', 1),
(50, 12, '<p>宇航员应睡在固定的睡袋中，以免被气流推动误碰仪器设备开关 &nbsp; &nbsp; &nbsp; &nbsp;</p>', 2),
(51, 12, '<p>在同一航空器中的宇航员可以直接交谈，无需借助无线电通讯设备 &nbsp; &nbsp; &nbsp; &nbsp;</p>', 3),
(52, 13, '<p>哪里没有法律，哪里就没有自由 &nbsp; &nbsp; &nbsp; &nbsp;</p>', 0),
(53, 13, '<p>法典就是人民自由的圣经 &nbsp; &nbsp; &nbsp; &nbsp;</p>', 1),
(54, 13, '<p>法律是自由的保姆 &nbsp; &nbsp; &nbsp; &nbsp;</p>', 2),
(55, 13, '<p>自由只服从法律 &nbsp; &nbsp; &nbsp; &nbsp;</p>', 3),
(56, 18, '<p>2008—2012年，全国国道交通繁忙程度持续增加 &nbsp; &nbsp; &nbsp; &nbsp;</p>', 0),
(57, 18, '<p>2012年国道网日平均交通量超过2万辆的省份最多有6个 &nbsp; &nbsp; &nbsp; &nbsp;</p>', 1),
(58, 18, '<p>2008—2012年，全国高速公路交通繁忙程度持续增加 &nbsp; &nbsp; &nbsp; &nbsp;</p>', 2),
(59, 18, '<p>2012年国道年平均拥挤度超过0.6的省份至少有8个 &nbsp; &nbsp; &nbsp; &nbsp;</p>', 3),
(60, 19, '<p>1.7倍 &nbsp; &nbsp; &nbsp; &nbsp;</p>', 0),
(61, 19, '<p>1.5倍 &nbsp; &nbsp; &nbsp; &nbsp;</p>', 1),
(62, 19, '<p>2.0倍 &nbsp; &nbsp; &nbsp; &nbsp;</p>', 2),
(63, 19, '<p>1.8倍 &nbsp; &nbsp; &nbsp; &nbsp;</p>', 3);

-- --------------------------------------------------------

--
-- 表的结构 `question_block`
--

CREATE TABLE IF NOT EXISTS `question_block` (
  `question_block_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '模块',
  `name` varchar(40) NOT NULL COMMENT '页面显示，默认等于identity',
  `description` varchar(500) DEFAULT NULL,
  `exam_paper_id` int(11) NOT NULL,
  `time_length` int(11) NOT NULL DEFAULT '0' COMMENT '以秒为单位',
  `question_number` smallint(6) NOT NULL,
  `score` smallint(6) NOT NULL DEFAULT '0',
  `score_rule` tinyint(4) NOT NULL DEFAULT '1' COMMENT '计分方式',
  `sequence` smallint(6) NOT NULL DEFAULT '1',
  PRIMARY KEY (`question_block_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `question_block`
--

INSERT INTO `question_block` (`question_block_id`, `name`, `description`, `exam_paper_id`, `time_length`, `question_number`, `score`, `score_rule`, `sequence`) VALUES
(1, '单选题', '仅有一个答案正确', 1, 30, 4, 60, 1, 1),
(2, '判断题', '从两个选项中选择一个你认为正确的答案', 1, 30, 5, 30, 1, 2),
(3, '材料题', '根据给定材料回答相关题目', 1, 30, 4, 50, 1, 3);

-- --------------------------------------------------------

--
-- 表的结构 `question_exam_point`
--

CREATE TABLE IF NOT EXISTS `question_exam_point` (
  `question_exam_point_id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_point_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  PRIMARY KEY (`question_exam_point_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ;

--
-- 转存表中的数据 `question_exam_point`
--

INSERT INTO `question_exam_point` (`question_exam_point_id`, `exam_point_id`, `question_id`) VALUES
(22, 8, 8),
(23, 9, 8),
(24, 16, 8),
(25, 8, 9),
(26, 9, 9),
(27, 16, 9),
(28, 8, 7),
(29, 9, 7),
(30, 16, 7),
(31, 8, 10),
(32, 9, 10),
(33, 16, 10),
(34, 10, 11),
(35, 11, 11),
(36, 10, 12),
(37, 10, 13),
(38, 11, 14),
(39, 12, 15),
(40, 14, 16),
(41, 10, 17);

-- --------------------------------------------------------

--
-- 表的结构 `question_extra`
--

CREATE TABLE IF NOT EXISTS `question_extra` (
  `question_id` int(11) NOT NULL COMMENT '题目',
  `title` text COMMENT '富文本',
  `analysis` text COMMENT '解析',
  PRIMARY KEY (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `question_extra`
--

INSERT INTO `question_extra` (`question_id`, `title`, `analysis`) VALUES
(7, '<p>经过理论与实践的积累，再生建筑学也逐步成为了一门独立而完整的技术科学</p>', NULL),
(8, '<div class="overflow"><p>生命起源于海洋，因为海洋更适合于生命活动，生物要从水域移动到陆地是一种十分危险和艰难的<span style="text-decoration:underline;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span>，而潮汐区则提供了一种<span style="text-decoration:underline;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span>：一个作为水陆之间“桥梁”的环境，许多人相信，一些生物就是利用这座“桥梁”完成登陆这个进化过程的。</p><p>依次填入划横线部分最恰当的一项是。</p></div>', NULL),
(9, '<p>大批博士涌入公务员队伍，或可改变一下地方的政治生态，使得其他官员一个时期、一定程度上对知识对科技存有一些敬畏，兴起学习之风，避免一些<span style="text-decoration:underline;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span>的官场沉疴，填入横线上的成语，恰当的一项是（ &nbsp;）。</p>', NULL),
(10, '<div class="overflow"><p>所谓科学精神，不过是哲学上的多元主义的另一种说法而已。哲学上的多元主义，就是<span style="text-decoration:underline;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span>，否认有什么事物第一原因和宇宙、人类的什么终极目的。</p><p>填入横线上最恰当的是（ &nbsp;）。</p></div>', NULL),
(11, '<div class="overflow"><p>下列关于党风建设的创新，按时间先后顺序排列正确的是：</p><p>①以马克思列宁主义的理论思想武装起来的中国共产党，在中国人民中产生了新的工作作风，这主要的就是理论和实践相结合的作风，和人民群众紧密地联系在一起的作风以及自我批评的作用</p><p>②工作作风上的问题绝不是小事，如果不坚决纠正不良风气，任其发展下去，就会像一座无形的墙把我们党和人民群众隔开，我们党就会失去根基、失去血脉、失去力量</p><p>③务必使同志们继续地保持谦虚、谨慎、不骄、不躁的作风，务必使同志们继续地保持艰苦奋斗的作风</p><p>④抓精神文明建设、抓党风、社会风气好转，必须狠狠地抓，一天不放松地抓，从具体事件抓起</p></div>', NULL),
(12, '<p>关于宇航员在太空的生活，下列说法不正确的是：</p>', NULL),
(13, '<p>《人民日报》评论指出：“一个人挥舞胳膊的自由止于别人鼻子的地方。”下列可以代替该评论的名言是：</p>', NULL),
(14, '<p>俗语说“绣花要得手绵巧，打铁还须自身硬”，与该俗语哲学道理相同的是：师傅领进门，修行靠个人 &nbsp; &nbsp; &nbsp; &nbsp;</p>', NULL),
(15, '<p>斯诺克台球属于奥运会的比赛项目之一</p>', NULL),
(16, '<p>进入世界杯和欧洲杯足球赛决赛圈的球队数量相同 &nbsp; &nbsp; &nbsp; &nbsp;</p>', NULL),
(17, '<p>一般认为热带雨林是指阴凉、潮湿多雨、高温、结构层次不明显、层外植物丰富的乔木植物群落，热带雨林没有季节之分，常年都是雨季 &nbsp; &nbsp; &nbsp; &nbsp;</p>', NULL),
(18, '<p>能够从上述资料中推出的是：</p>', NULL),
(19, '<p>2012年国家高速公路日平均交通量约是普通国道日平均交通量的：</p>', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `question_favorites`
--

CREATE TABLE IF NOT EXISTS `question_favorites` (
  `question_favorites_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  PRIMARY KEY (`question_favorites_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=45 ;

--
-- 转存表中的数据 `question_favorites`
--

INSERT INTO `question_favorites` (`question_favorites_id`, `user_id`, `question_id`) VALUES
(38, 1, 7),
(44, 1, 14),
(39, 1, 17),
(42, 1, 12),
(34, 2, 9),
(33, 2, 8),
(43, 1, 9),
(40, 1, 16),
(37, 1, 10);

-- --------------------------------------------------------

--
-- 表的结构 `question_instance`
--

CREATE TABLE IF NOT EXISTS `question_instance` (
  `question_instance_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '生成考卷的题目',
  `exam_paper_instance_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `myanswer` varchar(50) DEFAULT NULL COMMENT '用户提交的答案',
  PRIMARY KEY (`question_instance_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=104 ;

--
-- 转存表中的数据 `question_instance`
--

INSERT INTO `question_instance` (`question_instance_id`, `exam_paper_instance_id`, `question_id`, `user_id`, `myanswer`) VALUES
(1, 1, 14, 1, '1'),
(2, 1, 13, 1, '1'),
(3, 1, 12, 1, '1'),
(4, 1, 11, 1, '2'),
(5, 1, 10, 1, '0'),
(6, 1, 7, 1, NULL),
(7, 1, 9, 1, NULL),
(8, 1, 8, 1, NULL),
(9, 2, 14, 1, '1'),
(10, 2, 13, 1, '0'),
(11, 2, 12, 1, NULL),
(12, 3, 10, 1, '1'),
(13, 3, 13, 1, '3'),
(14, 4, 14, 1, NULL),
(15, 4, 13, 1, NULL),
(16, 4, 12, 1, NULL),
(17, 4, 11, 1, NULL),
(18, 4, 10, 1, NULL),
(19, 5, 17, 1, NULL),
(20, 5, 14, 1, NULL),
(21, 5, 13, 1, NULL),
(22, 5, 12, 1, NULL),
(23, 5, 11, 1, NULL),
(24, 5, 10, 1, NULL),
(25, 5, 7, 1, NULL),
(26, 5, 9, 1, NULL),
(27, 5, 8, 1, NULL),
(28, 6, 14, 1, NULL),
(29, 6, 13, 1, NULL),
(30, 6, 11, 1, NULL),
(31, 6, 10, 1, NULL),
(32, 6, 7, 1, NULL),
(33, 6, 9, 1, NULL),
(34, 6, 8, 1, NULL),
(35, 7, 17, 1, NULL),
(36, 7, 14, 1, NULL),
(37, 7, 13, 1, NULL),
(38, 7, 11, 1, NULL),
(39, 7, 10, 1, NULL),
(40, 7, 7, 1, NULL),
(41, 7, 9, 1, NULL),
(42, 7, 8, 1, NULL),
(43, 8, 16, 1, NULL),
(44, 8, 15, 1, NULL),
(45, 11, 10, 1, NULL),
(46, 12, 14, 1, NULL),
(47, 12, 13, 1, NULL),
(48, 12, 12, 1, NULL),
(49, 12, 11, 1, NULL),
(50, 12, 10, 1, NULL),
(51, 13, 17, 1, NULL),
(52, 13, 14, 1, NULL),
(53, 13, 13, 1, NULL),
(54, 13, 12, 1, NULL),
(55, 13, 11, 1, NULL),
(56, 13, 10, 1, NULL),
(57, 13, 7, 1, NULL),
(58, 13, 9, 1, NULL),
(59, 13, 8, 1, NULL),
(60, 14, 14, 1, NULL),
(61, 14, 13, 1, NULL),
(62, 14, 12, 1, NULL),
(63, 14, 11, 1, NULL),
(64, 14, 10, 1, NULL),
(65, 15, 14, 1, NULL),
(66, 15, 13, 1, NULL),
(67, 15, 12, 1, NULL),
(68, 15, 11, 1, NULL),
(69, 15, 10, 1, NULL),
(70, 16, 14, 1, NULL),
(71, 16, 13, 1, NULL),
(72, 16, 12, 1, NULL),
(73, 16, 11, 1, NULL),
(74, 16, 10, 1, NULL),
(75, 17, 14, 1, NULL),
(76, 17, 13, 1, NULL),
(77, 17, 12, 1, NULL),
(78, 17, 11, 1, NULL),
(79, 17, 10, 1, NULL),
(80, 18, 14, 1, NULL),
(81, 18, 13, 1, NULL),
(82, 18, 12, 1, NULL),
(83, 18, 11, 1, NULL),
(84, 18, 10, 1, NULL),
(85, 19, 14, 1, NULL),
(86, 19, 13, 1, NULL),
(87, 19, 12, 1, NULL),
(88, 19, 11, 1, NULL),
(89, 19, 10, 1, NULL),
(90, 20, 14, 1, NULL),
(91, 20, 13, 1, NULL),
(92, 20, 12, 1, NULL),
(93, 20, 11, 1, NULL),
(94, 20, 10, 1, NULL),
(95, 21, 14, 1, NULL),
(96, 21, 13, 1, NULL),
(97, 21, 12, 1, NULL),
(98, 21, 11, 1, NULL),
(99, 21, 10, 1, NULL),
(100, 22, 10, 1, NULL),
(101, 23, 16, 1, NULL),
(102, 24, 17, 1, '1'),
(103, 24, 12, 1, '2');

-- --------------------------------------------------------

--
-- 表的结构 `subject`
--

CREATE TABLE IF NOT EXISTS `subject` (
  `subject_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '学科',
  `exam_bank_id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `do_paper_recommendation` bit(1) NOT NULL DEFAULT b'1' COMMENT '是否推荐真题',
  `exam_point_show_level` smallint(6) NOT NULL DEFAULT '3',
  PRIMARY KEY (`subject_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `subject`
--

INSERT INTO `subject` (`subject_id`, `exam_bank_id`, `name`, `do_paper_recommendation`, `exam_point_show_level`) VALUES
(1, 1, '公务员申论', b'1', 3),
(2, 1, '公务员行测', b'1', 3);

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` char(32) NOT NULL,
  `creation_time` timestamp NULL DEFAULT NULL,
  `is_admin` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`, `creation_time`, `is_admin`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '2014-05-15 10:08:58', b'1'),
(2, 'junwuluo', 'e10adc3949ba59abbe56e057f20f883e', '2014-05-25 13:01:35', b'0');

-- --------------------------------------------------------

--
-- 表的结构 `wrong_question`
--

CREATE TABLE IF NOT EXISTS `wrong_question` (
  `wrong_question_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  PRIMARY KEY (`wrong_question_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `wrong_question`
--

INSERT INTO `wrong_question` (`wrong_question_id`, `user_id`, `question_id`) VALUES
(1, 1, 14),
(2, 1, 13),
(3, 1, 12),
(4, 1, 11),
(5, 1, 10),
(6, 1, 17);

-- --------------------------------------------------------

--
-- 表的结构 `yiicache`
--

CREATE TABLE IF NOT EXISTS `yiicache` (
  `id` char(128) NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `value` longblob,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `yiicache`
--

INSERT INTO `yiicache` (`id`, `expire`, `value`) VALUES
('ffc1043dc0328d51ec58b993e80b5ebc', 0, 0x613a323a7b693a303b613a323a7b693a303b613a333a7b693a303b4f3a383a224355726c52756c65223a31363a7b733a393a2275726c537566666978223b4e3b733a31333a226361736553656e736974697665223b4e3b733a31333a2264656661756c74506172616d73223b613a303a7b7d733a31303a226d6174636856616c7565223b4e3b733a343a2276657262223b4e3b733a31313a2270617273696e674f6e6c79223b623a303b733a353a22726f757465223b733a31373a223c636f6e74726f6c6c65723e2f76696577223b733a31303a227265666572656e636573223b613a313a7b733a31303a22636f6e74726f6c6c6572223b733a31323a223c636f6e74726f6c6c65723e223b7d733a31323a22726f7574655061747465726e223b733a33303a222f5e283f503c636f6e74726f6c6c65723e5c772b295c2f76696577242f75223b733a373a227061747465726e223b733a33393a222f5e283f503c636f6e74726f6c6c65723e5c772b295c2f283f503c69643e5c642b295c2f242f75223b733a383a2274656d706c617465223b733a31373a223c636f6e74726f6c6c65723e2f3c69643e223b733a363a22706172616d73223b613a313a7b733a323a226964223b733a333a225c642b223b7d733a363a22617070656e64223b623a303b733a31313a22686173486f7374496e666f223b623a303b733a31343a220043436f6d706f6e656e74005f65223b4e3b733a31343a220043436f6d706f6e656e74005f6d223b4e3b7d693a313b4f3a383a224355726c52756c65223a31363a7b733a393a2275726c537566666978223b4e3b733a31333a226361736553656e736974697665223b4e3b733a31333a2264656661756c74506172616d73223b613a303a7b7d733a31303a226d6174636856616c7565223b4e3b733a343a2276657262223b4e3b733a31313a2270617273696e674f6e6c79223b623a303b733a353a22726f757465223b733a32313a223c636f6e74726f6c6c65723e2f3c616374696f6e3e223b733a31303a227265666572656e636573223b613a323a7b733a31303a22636f6e74726f6c6c6572223b733a31323a223c636f6e74726f6c6c65723e223b733a363a22616374696f6e223b733a383a223c616374696f6e3e223b7d733a31323a22726f7574655061747465726e223b733a34313a222f5e283f503c636f6e74726f6c6c65723e5c772b295c2f283f503c616374696f6e3e5c772b29242f75223b733a373a227061747465726e223b733a35363a222f5e283f503c636f6e74726f6c6c65723e5c772b295c2f283f503c616374696f6e3e5c772b295c2f283f503c69643e5c642b295c2f242f75223b733a383a2274656d706c617465223b733a32363a223c636f6e74726f6c6c65723e2f3c616374696f6e3e2f3c69643e223b733a363a22706172616d73223b613a313a7b733a323a226964223b733a333a225c642b223b7d733a363a22617070656e64223b623a303b733a31313a22686173486f7374496e666f223b623a303b733a31343a220043436f6d706f6e656e74005f65223b4e3b733a31343a220043436f6d706f6e656e74005f6d223b4e3b7d693a323b4f3a383a224355726c52756c65223a31363a7b733a393a2275726c537566666978223b4e3b733a31333a226361736553656e736974697665223b4e3b733a31333a2264656661756c74506172616d73223b613a303a7b7d733a31303a226d6174636856616c7565223b4e3b733a343a2276657262223b4e3b733a31313a2270617273696e674f6e6c79223b623a303b733a353a22726f757465223b733a32313a223c636f6e74726f6c6c65723e2f3c616374696f6e3e223b733a31303a227265666572656e636573223b613a323a7b733a31303a22636f6e74726f6c6c6572223b733a31323a223c636f6e74726f6c6c65723e223b733a363a22616374696f6e223b733a383a223c616374696f6e3e223b7d733a31323a22726f7574655061747465726e223b733a34313a222f5e283f503c636f6e74726f6c6c65723e5c772b295c2f283f503c616374696f6e3e5c772b29242f75223b733a373a227061747465726e223b733a34333a222f5e283f503c636f6e74726f6c6c65723e5c772b295c2f283f503c616374696f6e3e5c772b295c2f242f75223b733a383a2274656d706c617465223b733a32313a223c636f6e74726f6c6c65723e2f3c616374696f6e3e223b733a363a22706172616d73223b613a303a7b7d733a363a22617070656e64223b623a303b733a31313a22686173486f7374496e666f223b623a303b733a31343a220043436f6d706f6e656e74005f65223b4e3b733a31343a220043436f6d706f6e656e74005f6d223b4e3b7d7d693a313b733a33323a223434393832613234336433633331393564353161616333643333613266363336223b7d693a313b4e3b7d),
('58fae9269af17c07045e8444630865ba', 0, 0x613a323a7b693a303b733a34303a22613a313a7b733a31333a226e61765f74696d657374616d70223b693a313430303330303932363b7d223b693a313b4f3a32303a224346696c654361636865446570656e64656e6379223a363a7b733a383a2266696c654e616d65223b733a35343a22453a5c78616d70705c6874646f63735c78756568616974696b755c70726f7465637465645c72756e74696d655c73746174652e62696e223b733a31383a227265757365446570656e64656e7444617461223b623a303b733a32333a2200434361636865446570656e64656e6379005f68617368223b4e3b733a32333a2200434361636865446570656e64656e6379005f64617461223b693a313430303330303932363b733a31343a220043436f6d706f6e656e74005f65223b4e3b733a31343a220043436f6d706f6e656e74005f6d223b4e3b7d7d),
('cec9feb231258894cfe437147492c61e', 0, 0x613a323a7b693a303b613a343a7b693a303b613a333a7b733a353a226c6162656c223b733a393a22e585ace58aa1e59198223b733a333a2275726c223b733a313a2223223b733a353a226974656d73223b613a323a7b693a303b613a323a7b733a353a226c6162656c223b733a31353a22e585ace58aa1e59198e8a18ce6b58b223b733a333a2275726c223b613a323a7b693a303b733a31393a222f61646d696e2f7375626a6563742f76696577223b733a323a226964223b733a313a2231223b7d7d693a313b613a323a7b733a353a226c6162656c223b733a31353a22e585ace58aa1e59198e8a18ce6b58b223b733a333a2275726c223b613a323a7b693a303b733a31393a222f61646d696e2f7375626a6563742f76696577223b733a323a226964223b733a313a2232223b7d7d7d7d693a313b613a323a7b733a353a226c6162656c223b733a363a22e9ab98e88083223b733a333a2275726c223b733a313a2223223b7d693a323b613a323a7b733a353a226c6162656c223b733a363a22e4b8ade88083223b733a333a2275726c223b733a313a2223223b7d693a333b613a323a7b733a353a226c6162656c223b733a31323a22e8af81e588b8e88083e8af95223b733a333a2275726c223b733a313a2223223b7d7d693a313b4f3a32373a2243476c6f62616c53746174654361636865446570656e64656e6379223a363a7b733a393a2273746174654e616d65223b733a31333a226e61765f74696d657374616d70223b733a31383a227265757365446570656e64656e7444617461223b623a303b733a32333a2200434361636865446570656e64656e6379005f68617368223b4e3b733a32333a2200434361636865446570656e64656e6379005f64617461223b693a313430303330303932363b733a31343a220043436f6d706f6e656e74005f65223b4e3b733a31343a220043436f6d706f6e656e74005f6d223b4e3b7d7d);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
