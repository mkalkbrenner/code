/* 
 * Copyright (C) 2015 Maxim Lebedev
 *
 * This file is part of "myWebRTC"
 *
 * "myWebRTC" is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * "myWebRTC" is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, see <http://www.gnu.org/licenses/>
 */

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `webcall-db` DEFAULT CHARACTER SET utf8 ;
USE `webcall-db` ;

-- -----------------------------------------------------
-- Table `webcall-db`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `webcall-db`.`users` (
  `id_user` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `surname` VARCHAR(255) NOT NULL,
  `email` TEXT NOT NULL,
  `password` TEXT NOT NULL,
  `company` TEXT NULL DEFAULT NULL,
  `city` VARCHAR(255) NULL DEFAULT NULL,
  `country` VARCHAR(255) NULL DEFAULT NULL,
  `url` TEXT NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE INDEX `id_user_UNIQUE` (`id_user` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `webcall-db`.`calls`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `webcall-db`.`calls` (
  `call_from` INT(11) NOT NULL,
  `call_to` INT(11) NOT NULL,
  `answer` ENUM('0','1','2') NOT NULL DEFAULT '0',
  `key` TEXT NOT NULL,
  PRIMARY KEY (`call_from`, `call_to`),
  INDEX `call_from` (`call_from` ASC),
  CONSTRAINT `users_calls_1`
    FOREIGN KEY (`call_from`)
    REFERENCES `webcall-db`.`users` (`id_user`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `users_calls_2`
    FOREIGN KEY (`call_to`)
    REFERENCES `webcall-db`.`users` (`id_user`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `webcall-db`.`friends`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `webcall-db`.`friends` (
  `friend_one` INT(11) NOT NULL,
  `friend_two` INT(11) NOT NULL,
  `status` ENUM('0','1','2') NOT NULL DEFAULT '0',
  PRIMARY KEY (`friend_one`, `friend_two`),
  INDEX `friend_one` (`friend_one` ASC),
  CONSTRAINT `users_friends_1`
    FOREIGN KEY (`friend_one`)
    REFERENCES `webcall-db`.`users` (`id_user`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `users_friends_2`
    FOREIGN KEY (`friend_two`)
    REFERENCES `webcall-db`.`users` (`id_user`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `webcall-db`.`status`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `webcall-db`.`status` (
  `id_status` INT(11) NOT NULL AUTO_INCREMENT,
  `online` TINYINT(1) NOT NULL DEFAULT '0',
  `offline` TINYINT(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_status`),
  UNIQUE INDEX `id_status_UNIQUE` (`id_status` ASC),
  CONSTRAINT `users_status`
    FOREIGN KEY (`id_status`)
    REFERENCES `webcall-db`.`users` (`id_user`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `webcall-db`.`updates`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `webcall-db`.`updates` (
  `id_update` INT(11) NOT NULL AUTO_INCREMENT,
  `update` VARCHAR(255) NULL DEFAULT 'Hier you can change your status',
  PRIMARY KEY (`id_update`),
  UNIQUE INDEX `id_update_UNIQUE` (`id_update` ASC),
  CONSTRAINT `users_updates`
    FOREIGN KEY (`id_update`)
    REFERENCES `webcall-db`.`users` (`id_user`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
