-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema tutorial_3
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema tutorial_3
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `tutorial_3` DEFAULT CHARACTER SET utf8 ;
USE `tutorial_3` ;

-- -----------------------------------------------------
-- Table `tutorial_3`.`customer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tutorial_3`.`customer` (
  `customer_id` INT NOT NULL,
  `customer_name` VARCHAR(100) NOT NULL,
  `customer_address` VARCHAR(100) NOT NULL,
  `customer_city` VARCHAR(45) NOT NULL,
  `customer_state` VARCHAR(45) NOT NULL,
  `customer_postal_code` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`customer_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tutorial_3`.`order`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tutorial_3`.`order` (
  `order_id` INT NOT NULL,
  `order_date` DATETIME NOT NULL,
  `customer_id` INT NOT NULL,
  PRIMARY KEY (`order_id`),
  INDEX `customer_fk_idx` (`customer_id` ASC),
  CONSTRAINT `customer_fk`
    FOREIGN KEY (`customer_id`)
    REFERENCES `tutorial_3`.`customer` (`customer_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tutorial_3`.`product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tutorial_3`.`product` (
  `product_id` INT NOT NULL,
  `product_description` VARCHAR(45) NOT NULL,
  `product_finish` VARCHAR(45) NOT NULL,
  `product_standard_price` DECIMAL(4,2) NOT NULL,
  `product_line_id` INT NOT NULL,
  PRIMARY KEY (`product_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tutorial_3`.`order_line`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tutorial_3`.`order_line` (
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `ordered_quantity` INT NOT NULL,
  PRIMARY KEY (`order_id`, `product_id`),
  INDEX `product_fk_idx` (`product_id` ASC),
  CONSTRAINT `order_fk`
    FOREIGN KEY (`order_id`)
    REFERENCES `tutorial_3`.`order` (`order_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `product_fk`
    FOREIGN KEY (`product_id`)
    REFERENCES `tutorial_3`.`product` (`product_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
