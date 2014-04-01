SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `xuehaitiku` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `xuehaitiku` ;

-- -----------------------------------------------------
-- Table `xuehaitiku`.`exam_bank`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xuehaitiku`.`exam_bank` ;

CREATE  TABLE IF NOT EXISTS `xuehaitiku`.`exam_bank` (
  `exam_bank_id` INT NOT NULL AUTO_INCREMENT COMMENT '题库' ,
  `name` VARCHAR(45) NOT NULL ,
  `price` FLOAT NOT NULL DEFAULT 0 COMMENT '元为单位' ,
  PRIMARY KEY (`exam_bank_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `xuehaitiku`.`exam_point`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xuehaitiku`.`exam_point` ;

CREATE  TABLE IF NOT EXISTS `xuehaitiku`.`exam_point` (
  `exam_point_id` INT NOT NULL AUTO_INCREMENT COMMENT '考点树' ,
  `name` VARCHAR(45) NOT NULL ,
  `pid` INT NOT NULL DEFAULT 0 COMMENT '如果为0表示顶级考点' ,
  PRIMARY KEY (`exam_point_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `xuehaitiku`.`subject`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xuehaitiku`.`subject` ;

CREATE  TABLE IF NOT EXISTS `xuehaitiku`.`subject` (
  `subject_id` INT NOT NULL AUTO_INCREMENT COMMENT '学科' ,
  `exam_bank_id` INT NOT NULL ,
  `exam_point_id` INT NOT NULL COMMENT '顶级考点的ID' ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`subject_id`) ,
  INDEX `fk_subject_tiku1_idx` (`exam_bank_id` ASC) ,
  INDEX `fk_subject_topic1_idx` (`exam_point_id` ASC) ,
  CONSTRAINT `fk_subject_tiku1`
    FOREIGN KEY (`exam_bank_id` )
    REFERENCES `xuehaitiku`.`exam_bank` (`exam_bank_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_subject_topic1`
    FOREIGN KEY (`exam_point_id` )
    REFERENCES `xuehaitiku`.`exam_point` (`exam_point_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `xuehaitiku`.`category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xuehaitiku`.`category` ;

CREATE  TABLE IF NOT EXISTS `xuehaitiku`.`category` (
  `category_id` INT NOT NULL AUTO_INCREMENT COMMENT '试卷分类' ,
  `pid` INT NOT NULL DEFAULT 0 COMMENT '父id' ,
  `name` VARCHAR(20) NOT NULL ,
  PRIMARY KEY (`category_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `xuehaitiku`.`exam_paper`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xuehaitiku`.`exam_paper` ;

CREATE  TABLE IF NOT EXISTS `xuehaitiku`.`exam_paper` (
  `examp_paper_id` INT NOT NULL AUTO_INCREMENT COMMENT '试卷' ,
  `subject_id` INT NOT NULL ,
  `name` VARCHAR(45) NOT NULL ,
  `short_name` VARCHAR(45) NULL COMMENT '简称' ,
  `score` SMALLINT NULL DEFAULT 0 COMMENT '总分' ,
  `recommendation` TINYINT NULL ,
  `category_id` INT NULL DEFAULT 0 COMMENT '所属分类，默认不属任何类' ,
  `time_length` SMALLINT NULL DEFAULT 0 COMMENT '考试时间，以秒为单位' ,
  PRIMARY KEY (`examp_paper_id`) ,
  INDEX `fk_paper_subject1_idx` (`subject_id` ASC) ,
  INDEX `fk_paper_category1_idx` (`category_id` ASC) ,
  CONSTRAINT `fk_paper_subject1`
    FOREIGN KEY (`subject_id` )
    REFERENCES `xuehaitiku`.`subject` (`subject_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_paper_category1`
    FOREIGN KEY (`category_id` )
    REFERENCES `xuehaitiku`.`category` (`category_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `xuehaitiku`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xuehaitiku`.`user` ;

CREATE  TABLE IF NOT EXISTS `xuehaitiku`.`user` (
  `user_id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(30) NOT NULL ,
  `password` CHAR(32) NOT NULL ,
  `creation_time` TIMESTAMP NULL ,
  `is_admin` BIT NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`user_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `xuehaitiku`.`exam_paper_instance`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xuehaitiku`.`exam_paper_instance` ;

CREATE  TABLE IF NOT EXISTS `xuehaitiku`.`exam_paper_instance` (
  `exam_paper_instance_id` INT NOT NULL AUTO_INCREMENT COMMENT '生成的卷子实例，即用户做了的' ,
  `exam_paper_id` INT NOT NULL DEFAULT 0 COMMENT '如果试卷是随机生成的，则0' ,
  `user_id` INT NOT NULL ,
  `start_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `remain_time` SMALLINT NOT NULL COMMENT '剩余时间' ,
  PRIMARY KEY (`exam_paper_instance_id`) ,
  INDEX `fk_paperinstance_paper_idx` (`exam_paper_id` ASC) ,
  INDEX `fk_paperinstance_user1_idx` (`user_id` ASC) ,
  CONSTRAINT `fk_paperinstance_paper`
    FOREIGN KEY (`exam_paper_id` )
    REFERENCES `xuehaitiku`.`exam_paper` (`examp_paper_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_paperinstance_user1`
    FOREIGN KEY (`user_id` )
    REFERENCES `xuehaitiku`.`user` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `xuehaitiku`.`question_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xuehaitiku`.`question_type` ;

CREATE  TABLE IF NOT EXISTS `xuehaitiku`.`question_type` (
  `question_type_id` INT NOT NULL AUTO_INCREMENT COMMENT '模块' ,
  `front_end_name` VARCHAR(40) NOT NULL COMMENT '页面显示，默认等于identity' ,
  `back_end_name` VARCHAR(40) NOT NULL COMMENT '后台显示名称' ,
  `description` VARCHAR(500) NULL ,
  PRIMARY KEY (`question_type_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `xuehaitiku`.`material`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xuehaitiku`.`material` ;

CREATE  TABLE IF NOT EXISTS `xuehaitiku`.`material` (
  `material_id` INT NOT NULL AUTO_INCREMENT COMMENT '材料' ,
  `content` TEXT NOT NULL ,
  PRIMARY KEY (`material_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `xuehaitiku`.`question`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xuehaitiku`.`question` ;

CREATE  TABLE IF NOT EXISTS `xuehaitiku`.`question` (
  `question_id` INT NOT NULL AUTO_INCREMENT COMMENT '题目' ,
  `exam_paper_id` INT NOT NULL COMMENT '如果题目不属于任何一套试卷，则为0' ,
  `question_type_id` INT NOT NULL DEFAULT 0 COMMENT '0表示不属于任何题型' ,
  `material_id` INT NOT NULL DEFAULT 0 ,
  `index` SMALLINT NOT NULL ,
  `is_multiple` BIT NOT NULL DEFAULT 0 COMMENT '类型，0表示单选，1表示多选' ,
  `answer` TINYINT NOT NULL COMMENT '答案，以bit位表示' ,
  PRIMARY KEY (`question_id`) ,
  INDEX `fk_question_paper1_idx` (`exam_paper_id` ASC) ,
  INDEX `fk_question_module1_idx` (`question_type_id` ASC) ,
  INDEX `fk_question_article1_idx` (`material_id` ASC) ,
  CONSTRAINT `fk_question_paper1`
    FOREIGN KEY (`exam_paper_id` )
    REFERENCES `xuehaitiku`.`exam_paper` (`examp_paper_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_question_module1`
    FOREIGN KEY (`question_type_id` )
    REFERENCES `xuehaitiku`.`question_type` (`question_type_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_question_article1`
    FOREIGN KEY (`material_id` )
    REFERENCES `xuehaitiku`.`material` (`material_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `xuehaitiku`.`question_instance`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xuehaitiku`.`question_instance` ;

CREATE  TABLE IF NOT EXISTS `xuehaitiku`.`question_instance` (
  `question_instance_id` INT NOT NULL AUTO_INCREMENT COMMENT '生成考卷的题目' ,
  `exam_paper_instance_id` INT NOT NULL ,
  `question_id` INT NOT NULL ,
  `user_id` INT NOT NULL ,
  `myanswer` TINYINT NULL COMMENT '用户提交的答案' ,
  PRIMARY KEY (`question_instance_id`) ,
  INDEX `fk_questioninstance_paperinstance1_idx` (`exam_paper_instance_id` ASC) ,
  INDEX `fk_questioninstance_question1_idx` (`question_id` ASC) ,
  INDEX `fk_questioninstance_user1_idx` (`user_id` ASC) ,
  CONSTRAINT `fk_questioninstance_paperinstance1`
    FOREIGN KEY (`exam_paper_instance_id` )
    REFERENCES `xuehaitiku`.`exam_paper_instance` (`exam_paper_instance_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_questioninstance_question1`
    FOREIGN KEY (`question_id` )
    REFERENCES `xuehaitiku`.`question` (`question_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_questioninstance_user1`
    FOREIGN KEY (`user_id` )
    REFERENCES `xuehaitiku`.`user` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `xuehaitiku`.`cache`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xuehaitiku`.`cache` ;

CREATE  TABLE IF NOT EXISTS `xuehaitiku`.`cache` (
  `key` VARCHAR(45) NOT NULL ,
  `value` TEXT NULL ,
  PRIMARY KEY (`key`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `xuehaitiku`.`exam_paper_question_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xuehaitiku`.`exam_paper_question_type` ;

CREATE  TABLE IF NOT EXISTS `xuehaitiku`.`exam_paper_question_type` (
  `exam_paper_question_type_id` INT NOT NULL AUTO_INCREMENT ,
  `exam_paper_id` INT NOT NULL ,
  `question_type_id` INT NOT NULL ,
  INDEX `fk_papermodule_paper1_idx` (`exam_paper_question_type_id` ASC) ,
  INDEX `fk_papermodule_module1_idx` (`exam_paper_id` ASC) ,
  PRIMARY KEY (`exam_paper_question_type_id`) ,
  CONSTRAINT `fk_papermodule_paper1`
    FOREIGN KEY (`exam_paper_question_type_id` )
    REFERENCES `xuehaitiku`.`exam_paper` (`examp_paper_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_papermodule_module1`
    FOREIGN KEY (`exam_paper_id` )
    REFERENCES `xuehaitiku`.`question_type` (`question_type_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `xuehaitiku`.`question_exam_point`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xuehaitiku`.`question_exam_point` ;

CREATE  TABLE IF NOT EXISTS `xuehaitiku`.`question_exam_point` (
  `question_exam_point_id` INT NOT NULL AUTO_INCREMENT ,
  `exam_point_id` INT NOT NULL ,
  `question_id` INT NOT NULL ,
  INDEX `fk_questiontopic_topic1_idx` (`exam_point_id` ASC) ,
  INDEX `fk_questiontopic_question1_idx` (`question_id` ASC) ,
  PRIMARY KEY (`question_exam_point_id`) ,
  CONSTRAINT `fk_questiontopic_topic1`
    FOREIGN KEY (`exam_point_id` )
    REFERENCES `xuehaitiku`.`exam_point` (`exam_point_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_questiontopic_question1`
    FOREIGN KEY (`question_id` )
    REFERENCES `xuehaitiku`.`question` (`question_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `xuehaitiku`.`question_extra`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xuehaitiku`.`question_extra` ;

CREATE  TABLE IF NOT EXISTS `xuehaitiku`.`question_extra` (
  `question_id` INT NOT NULL COMMENT '题目' ,
  `title` TEXT NULL COMMENT '富文本' ,
  `analysis` TEXT NULL DEFAULT NULL COMMENT '解析' ,
  INDEX `fk_title_question1_idx` (`question_id` ASC) ,
  PRIMARY KEY (`question_id`) ,
  CONSTRAINT `fk_title_question1`
    FOREIGN KEY (`question_id` )
    REFERENCES `xuehaitiku`.`question` (`question_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `xuehaitiku`.`payment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xuehaitiku`.`payment` ;

CREATE  TABLE IF NOT EXISTS `xuehaitiku`.`payment` (
  `payment_id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL ,
  `exam_bank_id` INT NOT NULL ,
  `expiry` TIMESTAMP NOT NULL ,
  INDEX `fk_pay_user1_idx` (`user_id` ASC) ,
  INDEX `fk_pay_exam1_idx` (`exam_bank_id` ASC) ,
  PRIMARY KEY (`payment_id`) ,
  CONSTRAINT `fk_pay_user1`
    FOREIGN KEY (`user_id` )
    REFERENCES `xuehaitiku`.`user` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pay_exam1`
    FOREIGN KEY (`exam_bank_id` )
    REFERENCES `xuehaitiku`.`exam_bank` (`exam_bank_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `xuehaitiku`.`payrecord`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xuehaitiku`.`payrecord` ;

CREATE  TABLE IF NOT EXISTS `xuehaitiku`.`payrecord` (
  `payment_record_id` INT NOT NULL AUTO_INCREMENT COMMENT '支付记录' ,
  `user_id` INT NOT NULL ,
  `exam_bank_id` INT NOT NULL ,
  `money` FLOAT NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`payment_record_id`) ,
  INDEX `fk_payrecord_user1_idx` (`user_id` ASC) ,
  INDEX `fk_payrecord_exam1_idx` (`exam_bank_id` ASC) ,
  CONSTRAINT `fk_payrecord_user1`
    FOREIGN KEY (`user_id` )
    REFERENCES `xuehaitiku`.`user` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_payrecord_exam1`
    FOREIGN KEY (`exam_bank_id` )
    REFERENCES `xuehaitiku`.`exam_bank` (`exam_bank_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `xuehaitiku`.`question_answer_option`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xuehaitiku`.`question_answer_option` ;

CREATE  TABLE IF NOT EXISTS `xuehaitiku`.`question_answer_option` (
  `question_answer_option_id` INT NOT NULL AUTO_INCREMENT ,
  `question_id` INT NOT NULL ,
  `description` VARCHAR(500) NULL ,
  `is_image` BIT NOT NULL DEFAULT 0 COMMENT '如果is_image为1，description是图片的地址' ,
  `index` TINYINT NOT NULL ,
  PRIMARY KEY (`question_answer_option_id`) ,
  INDEX `fk_question_answer_option_question1_idx` (`question_id` ASC) ,
  CONSTRAINT `fk_question_answer_option_question1`
    FOREIGN KEY (`question_id` )
    REFERENCES `xuehaitiku`.`question` (`question_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `xuehaitiku` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
