/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50532
Source Host           : localhost:3306
Source Database       : bootstrapshopcart

Target Server Type    : MYSQL
Target Server Version : 50532
File Encoding         : 65001

Date: 2014-04-20 10:28:08
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `bsc_admin`
-- ----------------------------
DROP TABLE IF EXISTS `bsc_admin`;
CREATE TABLE `bsc_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(255) DEFAULT NULL,
  `pass` varchar(255) DEFAULT NULL,
  `lvl` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bsc_admin
-- ----------------------------
INSERT INTO `bsc_admin` VALUES ('1', 'admin', 'admin', null);

-- ----------------------------
-- Table structure for `bsc_category`
-- ----------------------------
DROP TABLE IF EXISTS `bsc_category`;
CREATE TABLE `bsc_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `ordering` int(11) DEFAULT NULL,
  `visible` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bsc_category
-- ----------------------------
INSERT INTO `bsc_category` VALUES ('1', 'Cloth', null, '1', '1');
INSERT INTO `bsc_category` VALUES ('2', 'Apple', null, '2', '1');
INSERT INTO `bsc_category` VALUES ('3', 'Cellphones', null, '3', '1');
INSERT INTO `bsc_category` VALUES ('4', 'Books', null, '4', '1');
INSERT INTO `bsc_category` VALUES ('5', 'Sports & Outdoors', null, '5', '1');

-- ----------------------------
-- Table structure for `bsc_coupons`
-- ----------------------------
DROP TABLE IF EXISTS `bsc_coupons`;
CREATE TABLE `bsc_coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `text_promotion` varchar(255) DEFAULT NULL,
  `discount` decimal(11,2) DEFAULT NULL,
  `visible` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bsc_coupons
-- ----------------------------
INSERT INTO `bsc_coupons` VALUES ('1', 'Promo10', 'New promotion demo for you', '10.00', '1');

-- ----------------------------
-- Table structure for `bsc_customers`
-- ----------------------------
DROP TABLE IF EXISTS `bsc_customers`;
CREATE TABLE `bsc_customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datelastorder` datetime DEFAULT NULL,
  `dateregister` datetime DEFAULT NULL,
  `payer_email` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `first_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `address_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `address_country` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `address_country_code` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `address_zip` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `address_state` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `address_city` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `address_street` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bsc_customers
-- ----------------------------
INSERT INTO `bsc_customers` VALUES ('1', '2013-09-16 00:34:44', '2013-09-13 00:16:33', 'buyer@demomail.com', 'Jeremy', 'It is only a demonstration of a customer comment', 'Jeremy Frank', 'UK', 'UK', '12354', 'Any, its a demo', 'Any, its a demo', 'Any, its a demo');
INSERT INTO `bsc_customers` VALUES ('2', '2012-12-17 00:00:00', '2013-08-13 21:58:14', 'buyer@demomail.com', 'Ann', 'It is only a demonstration of a customer comment', 'Ann Shepard', 'Australia', 'AU', '02001', 'Any, its a demo', 'Any, its a demo', 'Any, its a demo');
INSERT INTO `bsc_customers` VALUES ('3', '2013-09-16 00:34:44', '2013-06-15 23:00:59', 'buyer@demomail.com', 'John', 'It is only a demonstration of a customer comment', 'John Snow', 'UK', 'UK', '12354', 'Any, its a demo', 'Any, its a demo', 'Any, its a demo');
INSERT INTO `bsc_customers` VALUES ('4', '2012-12-17 00:00:00', '2013-05-16 00:16:33', 'buyer@demomail.com', 'Jeremy', 'It is only a demonstration of a customer comment', 'Jeremy Frank', 'UK', 'UK', '12354', 'Any, its a demo', 'Any, its a demo', 'Any, its a demo');
INSERT INTO `bsc_customers` VALUES ('5', '2012-12-17 00:00:00', '2013-09-13 21:58:14', 'buyer@demomail.com', 'Ann', 'It is only a demonstration of a customer comment', 'Ann Shepard', 'Australia', 'AU', '12354', 'Any, its a demo', 'Any, its a demo', 'Any, its a demo');
INSERT INTO `bsc_customers` VALUES ('6', '2013-09-16 00:34:44', '2013-10-16 23:00:59', 'buyer@demomail.com', 'Paul', 'It is only a demonstration of a customer comment', 'Paul Brooks', 'United States', 'US', '12354', 'Any, its a demo', 'Any, its a demo', 'Any, its a demo');
INSERT INTO `bsc_customers` VALUES ('7', '2012-12-17 00:00:00', '2013-11-19 00:16:33', 'buyer@demomail.com', 'Paul', 'It is only a demonstration of a customer comment', 'Paul Brooks', 'United States', 'US', '02001', 'Any, its a demo', 'Any, its a demo', 'Any, its a demo');
INSERT INTO `bsc_customers` VALUES ('8', '2013-09-16 00:34:44', '2013-12-14 21:58:14', 'buyer@demomail.com', 'Ian', 'It is only a demonstration of a customer comment', 'Ian Helton', 'Australia', 'AU', '02001', 'Any, its a demo', 'Any, its a demo', 'Any, its a demo');
INSERT INTO `bsc_customers` VALUES ('9', '2012-12-17 00:00:00', '2013-07-17 23:00:59', 'buyer@demomail.com', 'Ian', 'It is only a demonstration of a customer comment', 'Ian Helton', 'Australia', 'AU', '02001', 'Any, its a demo', 'Any, its a demo', 'Any, its a demo');
INSERT INTO `bsc_customers` VALUES ('10', '2013-09-16 00:34:44', '2013-08-13 00:16:33', 'buyer@demomail.com', 'Ian', 'It is only a demonstration of a customer comment', 'Ian Helton', 'Australia', 'AU', '02001', 'Any, its a demo', 'Any, its a demo', 'Any, its a demo');
INSERT INTO `bsc_customers` VALUES ('11', '2013-09-16 00:34:44', '2013-05-13 21:58:14', 'buyer@demomail.com', 'Jeremy', 'It is only a demonstration of a customer comment', 'Jeremy Frank', 'UK', 'UK', '02001', 'Any, its a demo', 'Any, its a demo', 'Any, its a demo');
INSERT INTO `bsc_customers` VALUES ('12', '2013-09-16 00:34:44', '2013-04-15 23:00:59', 'buyer@demomail.com', 'Ian', 'It is only a demonstration of a customer comment', 'Ian Helton', 'UK', 'UK', '02001', 'Any, its a demo', 'Any, its a demo', 'Any, its a demo');
INSERT INTO `bsc_customers` VALUES ('13', '2013-09-16 00:34:44', '2013-03-18 00:16:33', 'buyer@demomail.com', 'Jeremy', 'It is only a demonstration of a customer comment', 'Jeremy Frank', 'UK', 'UK', '12354', 'Any, its a demo', 'Any, its a demo', 'Any, its a demo');
INSERT INTO `bsc_customers` VALUES ('15', '2013-09-16 00:34:44', '2013-05-20 21:58:14', 'buyer@demomail.com', 'John', 'It is only a demonstration of a customer comment', 'John Snow', 'United States', 'US', '95131', 'Any, its a demo', 'Any, its a demo', 'Any, its a demo');
INSERT INTO `bsc_customers` VALUES ('16', '2013-09-15 23:05:50', '2013-02-15 23:00:59', 'buyer@demomail.com', 'Jeremy', 'It is only a demonstration of a customer comment', 'Jeremy Smith', 'United States', 'US', '12354', 'Any, its a demo', 'Any, its a demo', 'Any, its a demo');
INSERT INTO `bsc_customers` VALUES ('17', '2013-09-15 23:05:05', '2013-01-15 23:00:59', 'buyer@demomail.com', 'John', 'It is only a demonstration of a customer comment', 'John Snow', 'United States', 'US', '12354', 'Any, its a demo', 'Any, its a demo', 'Any, its a demo');
INSERT INTO `bsc_customers` VALUES ('31', '2013-09-29 19:45:40', '2013-09-29 19:19:57', 'info@beaenea.com', 'john', 'Frank', 'john Frank', 'USA', 'US', '8500', 'NY', 'New York', '2345 North Main Street');

-- ----------------------------
-- Table structure for `bsc_order_detail`
-- ----------------------------
DROP TABLE IF EXISTS `bsc_order_detail`;
CREATE TABLE `bsc_order_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dateorder` timestamp NULL DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `item_number` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `item_price` decimal(9,2) DEFAULT NULL,
  `mc_gross` varchar(255) DEFAULT NULL,
  `payment_status` varchar(255) DEFAULT NULL,
  `payment_amount` varchar(255) DEFAULT NULL,
  `payment_currency` varchar(255) DEFAULT NULL,
  `payer_email` varchar(255) DEFAULT NULL,
  `payment_type` varchar(255) DEFAULT NULL,
  `custom` varchar(255) DEFAULT NULL,
  `invoice` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `address_name` varchar(255) DEFAULT NULL,
  `address_country` varchar(255) DEFAULT NULL,
  `address_country_code` varchar(255) DEFAULT NULL,
  `address_zip` varchar(255) DEFAULT NULL,
  `address_state` varchar(255) DEFAULT NULL,
  `address_city` varchar(255) DEFAULT NULL,
  `address_street` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bsc_order_detail
-- ----------------------------
INSERT INTO `bsc_order_detail` VALUES ('1', '2012-12-17 00:00:00', 'product 1', '102', '1', null, '234.00', 'Completed', '234', 'USD', '', 'instant', '', '20130916002341', null, null, null, null, null, null, null, null, null);
INSERT INTO `bsc_order_detail` VALUES ('2', '2012-12-17 00:00:00', 'product 4', '103', '1', null, '87.00', 'Completed', '87', 'USD', null, 'instant', '', '20130916002342', null, null, null, null, null, null, null, null, null);
INSERT INTO `bsc_order_detail` VALUES ('3', '2012-12-17 00:00:00', 'product 3', '1', '1', null, '87.00', 'Completed', '87', 'USD', null, 'instant', '', '20130916002343', null, null, null, null, null, null, null, null, null);
INSERT INTO `bsc_order_detail` VALUES ('4', '2012-12-17 00:00:00', 'product 1', '103', '1', null, '87.90', 'Completed', '87,9', 'USD', null, 'instant', '', '20130916002344', null, null, null, null, null, null, null, null, null);
INSERT INTO `bsc_order_detail` VALUES ('5', '2012-12-17 00:00:00', 'product 5', '1', '2', null, '87.50', 'Completed', '87,5', 'USD', null, 'instant', '', '20130916002345', null, null, null, null, null, null, null, null, null);
INSERT INTO `bsc_order_detail` VALUES ('6', '2012-12-17 00:00:00', 'product 1', '1', '3', null, '423.00', 'Completed', '423', 'USD', null, 'instant', '', '20130916002346', null, null, null, null, null, null, null, null, null);
INSERT INTO `bsc_order_detail` VALUES ('7', '2012-12-17 00:00:00', 'product 2', '3', '4', null, '234.00', 'Completed', '234', 'USD', null, 'instant', '', '20130916002347', null, null, null, null, null, null, null, null, null);
INSERT INTO `bsc_order_detail` VALUES ('8', '2012-12-17 00:00:00', 'product 1', '1', '1', null, '3424.00', 'Completed', '3424', 'USD', null, 'instant', '', '20130916002348', null, null, null, null, null, null, null, null, null);
INSERT INTO `bsc_order_detail` VALUES ('9', '2012-12-17 00:00:00', 'My Service 1', '3', '1', null, '234.00', 'Completed', '234', 'USD', null, 'instant', '', '20130916002349', null, null, null, null, null, null, null, null, null);
INSERT INTO `bsc_order_detail` VALUES ('10', '2012-12-17 00:00:00', 'My Service 2', '1', '1', null, '234.00', 'Completed', '234', 'USD', null, 'instant', '', '20130916002350', null, null, null, null, null, null, null, null, null);
INSERT INTO `bsc_order_detail` VALUES ('11', '2012-12-17 00:00:00', 'My Product 3', '103', '1', null, '324.00', 'Completed', '324', 'USD', null, 'instant', '', '20130916002351', null, null, null, null, null, null, null, null, null);
INSERT INTO `bsc_order_detail` VALUES ('12', '2012-12-17 00:00:00', 'Shipping', '1', '1', null, '234.00', 'Completed', '234', 'USD', null, 'instant', '', '20130916002352', null, null, null, null, null, null, null, null, null);
INSERT INTO `bsc_order_detail` VALUES ('16', '2012-12-17 00:00:00', 'My Product 3', '103', '1', null, '234.00', 'Completed', '234', 'USD', null, 'instant', '', '20130916002353', null, null, null, null, null, null, null, null, null);
INSERT INTO `bsc_order_detail` VALUES ('17', '2012-12-17 00:00:00', 'product 1', '1', '1', null, '324.00', 'Completed', '324', 'USD', null, 'instant', '', '20130916002354', null, null, null, null, null, null, null, null, null);
INSERT INTO `bsc_order_detail` VALUES ('18', '2012-12-17 00:00:00', 'product 1', '1', '1', null, '3242.00', 'Completed', '3242', 'USD', null, 'instant', '', '20130916002355', null, null, null, null, null, null, null, null, null);
INSERT INTO `bsc_order_detail` VALUES ('19', '2012-12-17 00:00:00', 'Product 3', '3', '1', null, '324.00', 'Completed', '324', 'USD', null, 'instant', null, '20130916002356', null, null, null, null, null, null, null, null, null);
INSERT INTO `bsc_order_detail` VALUES ('20', '2012-12-17 00:00:00', 'product 1', '1', '1', null, '34.00', 'Completed', '34', 'USD', null, 'instant', null, '20130916002357', null, null, null, null, null, null, null, null, null);
INSERT INTO `bsc_order_detail` VALUES ('21', '2012-12-17 00:00:00', 'demo product 1', '3', '1', null, '234.00', 'Completed', '234', 'USD', null, 'instant', null, '20130916002358', null, null, null, null, null, null, null, null, null);
INSERT INTO `bsc_order_detail` VALUES ('22', '2013-09-16 00:34:44', 'product 1', '1', '1', null, '342.00', 'Completed', '342', 'USD', null, 'instant', null, '20130916002359', null, null, null, null, null, null, null, null, null);
INSERT INTO `bsc_order_detail` VALUES ('23', '2012-12-17 00:00:00', 'product 1', '3', '1', null, '234.00', 'Completed', '234', 'USD', null, 'instant', null, '20130916003008', null, null, null, null, null, null, null, null, null);
INSERT INTO `bsc_order_detail` VALUES ('24', '2012-12-17 00:00:00', 'demo product 1', '4', '1', null, '345', 'Completed', '345', 'USD', null, 'instant', null, '20130916002340', null, null, null, null, null, null, null, null, null);
INSERT INTO `bsc_order_detail` VALUES ('25', '2012-12-17 00:00:00', 'demo product 1', '4', '1', null, '452', 'Completed', '452', 'USD', null, 'instant', null, '20130916002340', null, null, null, null, null, null, null, null, null);
INSERT INTO `bsc_order_detail` VALUES ('26', '2013-09-29 19:19:57', 'product 1', '1', '1', null, '19.9', 'Cash on delivery', '24.9', 'USD', 'info@beaenea.com', 'Cash on Delivery', '', '20130929191847', 'John', 'Harley', 'John Harley', 'USA', 'US', '8500', 'NY', 'New York', '2345 North Main Street');
INSERT INTO `bsc_order_detail` VALUES ('27', '2013-09-29 19:21:31', 'product 1', '1', '1', null, '19.9', 'Cash on delivery', '24.9', 'USD', 'info@beaenea.com', 'Cash on Delivery', '', '20130929191847', 'John', 'Harley', 'John Harley', 'USA', 'US', '8500', 'NY', 'New York', '2345 North Main Street');
INSERT INTO `bsc_order_detail` VALUES ('28', '2013-09-29 19:21:59', 'product 1', '1', '1', null, '19.9', 'Cash on delivery', '24.9', 'USD', 'info@beaenea.com', 'Cash on Delivery', '', '20130929191847', 'John', 'Harley', 'John Harley', 'USA', 'US', '8500', 'NY', 'New York', '2345 North Main Street');
INSERT INTO `bsc_order_detail` VALUES ('29', '2013-09-29 19:23:37', 'product 1', '1', '1', null, '19.9', 'Cash on delivery', '24.9', 'USD', 'info@beaenea.com', 'Cash on Delivery', '', '20130929191847', 'John', 'Harley', 'John Harley', 'USA', 'US', '8500', 'NY', 'New York', '2345 North Main Street');
INSERT INTO `bsc_order_detail` VALUES ('30', '2013-09-29 19:45:40', 'product 1', '1', '2', null, '39.8', 'Cash on delivery', '44.8', 'USD', 'info@beaenea.com', 'Cash on Delivery', 'This is a test', '20130929194524', 'john', 'Frank', 'john Frank', 'USA', 'US', '8500', 'NY', 'New York', '2345 North Main Street');
INSERT INTO `bsc_order_detail` VALUES ('31', '2013-09-29 19:45:40', 'Discount coupon', '', '1', null, '10.00', 'Cash on delivery', '44.8', 'USD', 'info@beaenea.com', 'Cash on Delivery', 'This is a test', '20130929194524', 'john', 'Frank', 'john Frank', 'USA', 'US', '8500', 'NY', 'New York', '2345 North Main Street');
INSERT INTO `bsc_order_detail` VALUES ('32', '2013-09-29 19:45:40', 'Cash on delivery', '', '1', null, '10', 'Cash on delivery', '44.8', 'USD', 'info@beaenea.com', 'Cash on Delivery', 'This is a test', '20130929194524', 'john', 'Frank', 'john Frank', 'USA', 'US', '8500', 'NY', 'New York', '2345 North Main Street');
INSERT INTO `bsc_order_detail` VALUES ('33', '2013-09-29 19:45:40', 'Shipping', '', '1', null, '5', 'Cash on delivery', '44.8', 'USD', 'info@beaenea.com', 'Cash on Delivery', 'This is a test', '20130929194524', 'john', 'Frank', 'john Frank', 'USA', 'US', '8500', 'NY', 'New York', '2345 North Main Street');

-- ----------------------------
-- Table structure for `bsc_order_header`
-- ----------------------------
DROP TABLE IF EXISTS `bsc_order_header`;
CREATE TABLE `bsc_order_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dateorder` timestamp NULL DEFAULT NULL,
  `payment_status` varchar(255) DEFAULT NULL,
  `payment_amount` varchar(255) DEFAULT NULL,
  `payment_currency` varchar(255) DEFAULT NULL,
  `payer_email` varchar(255) DEFAULT NULL,
  `payment_type` varchar(255) DEFAULT NULL,
  `custom` varchar(255) DEFAULT NULL,
  `invoice` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `address_name` varchar(255) DEFAULT NULL,
  `address_country` varchar(255) DEFAULT NULL,
  `address_country_code` varchar(255) DEFAULT NULL,
  `address_zip` varchar(255) DEFAULT NULL,
  `address_state` varchar(255) DEFAULT NULL,
  `address_city` varchar(255) DEFAULT NULL,
  `address_street` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bsc_order_header
-- ----------------------------
INSERT INTO `bsc_order_header` VALUES ('1', '2014-11-12 00:24:33', 'Completed', '183.50', 'USD', 'buyer@demomail.com', 'instant', 'It is only a demonstration of a customer comment', '20130916002341', 'Jeremy', 'Frank', 'Jeremy Frank', 'UK', 'UK', '12354', 'Any, its a demo', 'Any, its a demo', '123, any street');
INSERT INTO `bsc_order_header` VALUES ('2', '2013-11-16 00:20:58', 'Completed', '183.50', 'USD', 'buyer@demomail.com', 'instant', 'It is only a demonstration of a customer comment', '20130916002342', 'Ann', 'Shepard', 'Ann Shepard', 'Australia', 'AU', '02001', 'Any, its a demo', 'Any, its a demo', '123, any street');
INSERT INTO `bsc_order_header` VALUES ('3', '2013-09-15 23:00:59', 'Completed', '87.00', 'USD', 'buyer@demomail.com', 'instant', 'It is only a demonstration of a customer comment', '20130916002343', 'John', 'Snow', 'John Snow', 'UK', 'UK', '12354', 'Any, its a demo', 'Any, its a demo', '123, any street');
INSERT INTO `bsc_order_header` VALUES ('4', '2013-09-16 09:31:02', 'Completed', '125.25', 'USD', 'buyer@demomail.com', 'instant', 'It is only a demonstration of a customer comment', '20130916002344', 'Jeremy', 'Frank', 'Jeremy Frank', 'UK', 'UK', '12354', 'Any, its a demo', 'Any, its a demo', '123, any street');
INSERT INTO `bsc_order_header` VALUES ('5', '2013-09-16 09:31:02', 'Completed', '183.50', 'USD', 'buyer@demomail.com', 'instant', 'It is only a demonstration of a customer comment', '20130916002345', 'Ann', 'Shepard', 'Ann Shepard', 'Australia', 'AU', '12354', 'Any, its a demo', 'Any, its a demo', '123, any street');
INSERT INTO `bsc_order_header` VALUES ('6', '2013-09-16 00:20:58', 'Completed', '87.00', 'USD', 'buyer@demomail.com', 'instant', 'It is only a demonstration of a customer comment', '20130916002346', 'Paul', 'Brooks', 'Paul Brooks', 'United States', 'US', '12354', 'Any, its a demo', 'Any, its a demo', '123, any street');
INSERT INTO `bsc_order_header` VALUES ('7', '2012-12-17 00:00:00', 'Completed', '183.50', 'USD', 'buyer@demomail.com', 'instant', 'It is only a demonstration of a customer comment', '20130916002347', 'Paul', 'Brooks', 'Paul Brooks', 'United States', 'US', '02001', 'Any, its a demo', 'Any, its a demo', '123, any street');
INSERT INTO `bsc_order_header` VALUES ('8', '2013-09-15 23:00:59', 'Completed', '125.25', 'USD', 'buyer@demomail.com', 'instant', 'It is only a demonstration of a customer comment', '20130916002348', 'Ian', 'Helton', 'Ian Helton', 'Australia', 'AU', '02001', 'Any, its a demo', 'Any, its a demo', '123, any street');
INSERT INTO `bsc_order_header` VALUES ('9', '2012-12-17 00:00:00', 'Completed', '15.34', 'USD', 'buyer@demomail.com', 'instant', 'It is only a demonstration of a customer comment', '20130916002349', 'Ian', 'Helton', 'Ian Helton', 'Australia', 'AU', '02001', 'Any, its a demo', 'Any, its a demo', '123, any street');
INSERT INTO `bsc_order_header` VALUES ('10', '2013-09-16 09:31:02', 'Completed', '87.00', 'USD', 'buyer@demomail.com', 'instant', 'It is only a demonstration of a customer comment', '20130916002350', 'Ian', 'Helton', 'Ian Helton', 'Australia', 'AU', '02001', 'Any, its a demo', 'Any, its a demo', '123, any street');
INSERT INTO `bsc_order_header` VALUES ('11', '2013-09-16 19:06:10', 'Completed', '15.34', 'USD', 'buyer@demomail.com', 'instant', 'It is only a demonstration of a customer comment', '20130916002351', 'Jeremy', 'Frank', 'Jeremy Frank', 'UK', 'UK', '02001', 'Any, its a demo', 'Any, its a demo', '123, any street');
INSERT INTO `bsc_order_header` VALUES ('12', '2014-02-20 19:06:10', 'Completed', '87.00', 'USD', 'buyer@demomail.com', 'instant', 'It is only a demonstration of a customer comment', '20130916002352', 'Ian', 'Helton', 'Ian Helton', 'UK', 'UK', '02001', 'Any, its a demo', 'Any, its a demo', '123, any street');
INSERT INTO `bsc_order_header` VALUES ('16', '2013-09-16 19:06:10', 'Completed', '15.34', 'USD', 'buyer@demomail.com', 'instant', 'It is only a demonstration of a customer comment', '20130916002353', 'Jeremy', 'Frank', 'Jeremy Frank', 'UK', 'UK', '12354', 'Any, its a demo', 'Any, its a demo', '123, any street');
INSERT INTO `bsc_order_header` VALUES ('17', '2013-09-15 23:00:59', 'Completed', '15.34', 'USD', 'buyer@demomail.com', 'instant', 'It is only a demonstration of a customer comment', '20130916002354', 'John', 'Snow', 'John Snow', 'United States', 'US', '95131', 'Any, its a demo', 'Any, its a demo', '123, any street');
INSERT INTO `bsc_order_header` VALUES ('18', '2014-05-06 23:05:05', 'Completed', '125.25', 'USD', 'buyer@demomail.com', 'instant', 'It is only a demonstration of a customer comment', '20130916002355', 'Jeremy', 'Smith', 'Jeremy Smith', 'United States', 'US', '12354', 'Any, its a demo', 'Any, its a demo', '123, any street');
INSERT INTO `bsc_order_header` VALUES ('19', '2013-09-15 23:05:50', 'Completed', '15.34', 'USD', 'buyer@demomail.com', 'instant', 'It is only a demonstration of a customer comment', '20130916002356', 'John', 'Snow', 'John Snow', 'United States', 'US', '12354', 'Any, its a demo', 'Any, its a demo', '123, any street');
INSERT INTO `bsc_order_header` VALUES ('20', '2013-09-15 23:49:17', 'Completed', '15.34', 'USD', 'buyer@demomail.com', 'instant', 'It is only a demonstration of a customer comment', '20130916002357', 'Jeremy', 'Smith', 'Jeremy Frank', 'United States', 'US', '12354', 'Any, its a demo', 'Any, its a demo', '123, any street');
INSERT INTO `bsc_order_header` VALUES ('21', '2015-01-22 00:20:58', 'Completed', '183.50', 'USD', 'buyer@demomail.com', 'instant', 'It is only a demonstration of a customer comment', '20130916002358', 'Ian', 'Helton', 'Ian Helton', 'United States', 'US', '12354', 'Any, its a demo', 'Any, its a demo', '123, any street');
INSERT INTO `bsc_order_header` VALUES ('22', '2016-01-30 00:24:33', 'Completed', '183.50', 'USD', 'buyer@demomail.com', 'instant', 'It is only a demonstration of a customer comment', '20130916002359', 'Jeremy', 'Frank', 'Jeremy Frank', 'UK', 'UK', '12354', 'Any, its a demo', 'Any, its a demo', '123, any street');
INSERT INTO `bsc_order_header` VALUES ('23', '2014-05-24 00:31:10', 'Completed', '125.25', 'USD', 'buyer@demomail.com', 'instant', 'It is only a demonstration of a customer comment', '20130916003008', 'Ian', 'Helton', 'Ian Helton', 'United States', 'US', '8500', 'Any, its a demo', 'Any, its a demo', '123, any street');
INSERT INTO `bsc_order_header` VALUES ('24', '2015-03-27 00:34:44', 'Completed', '183.50', 'USD', 'buyer@demomail.com', 'instant', 'It is only a demonstration of a customer comment', '20130916002340', 'Jeremy', 'Frank', 'Jeremy Frank', 'UK', 'UK', '12354', 'Any, its a demo', 'Any, its a demo', '123, any street');
INSERT INTO `bsc_order_header` VALUES ('25', '2013-09-16 00:34:44', 'Completed', '183.50', 'USD', 'buyer@demomail.com', 'instant', 'It is only a demonstration of a customer comment', '20130916002340', 'Jeremy', 'Frank', 'Jeremy Frank', 'UK', 'UK', '12354', 'Any, its a demo', 'Any, its a demo', '123, any street');
INSERT INTO `bsc_order_header` VALUES ('26', '2013-09-29 19:19:57', 'Cash on delivery', '24.9', 'USD', 'info@beaenea.com', 'Cash on Delivery', '', '20130929191847', 'John', 'Harley', 'John Harley', 'USA', 'US', '8500', 'NY', 'New York', '2345 North Main Street');
INSERT INTO `bsc_order_header` VALUES ('27', '2013-09-29 19:21:31', 'Cash on delivery', '24.9', 'USD', 'info@beaenea.com', 'Cash on Delivery', '', '20130929191847', 'John', 'Harley', 'John Harley', 'USA', 'US', '8500', 'NY', 'New York', '2345 North Main Street');
INSERT INTO `bsc_order_header` VALUES ('28', '2013-09-29 19:21:59', 'Cash on delivery', '24.9', 'USD', 'info@beaenea.com', 'Cash on Delivery', '', '20130929191847', 'John', 'Harley', 'John Harley', 'USA', 'US', '8500', 'NY', 'New York', '2345 North Main Street');
INSERT INTO `bsc_order_header` VALUES ('29', '2013-09-29 19:23:36', 'Cash on delivery', '24.9', 'USD', 'info@beaenea.com', 'Cash on Delivery', '', '20130929191847', 'John', 'Harley', 'John Harley', 'USA', 'US', '8500', 'NY', 'New York', '2345 North Main Street');
INSERT INTO `bsc_order_header` VALUES ('30', '2013-09-29 19:45:40', 'Cash on delivery', '44.8', 'USD', 'info@beaenea.com', 'Cash on Delivery', 'This is a test', '20130929194524', 'john', 'Frank', 'john Frank', 'USA', 'US', '8500', 'NY', 'New York', '2345 North Main Street');

-- ----------------------------
-- Table structure for `bsc_products`
-- ----------------------------
DROP TABLE IF EXISTS `bsc_products`;
CREATE TABLE `bsc_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idCategory` int(11) DEFAULT NULL,
  `productCode` varchar(20) DEFAULT NULL COMMENT 'Sku, code ?',
  `img` varchar(255) DEFAULT NULL COMMENT 'Main image',
  `img_detail1` varchar(255) DEFAULT NULL COMMENT 'Detail image',
  `img_detail2` varchar(255) DEFAULT NULL COMMENT 'Detail image',
  `img_detail3` varchar(255) DEFAULT NULL COMMENT 'Detail image',
  `name` varchar(255) DEFAULT NULL COMMENT 'Name of product',
  `description` text COMMENT 'Description',
  `price` decimal(9,2) DEFAULT '0.00',
  `price_offer` decimal(9,2) DEFAULT '0.00',
  `download` varchar(255) DEFAULT NULL COMMENT 'any file for download?',
  `ordering` int(11) DEFAULT NULL COMMENT 'order ?',
  `visible` int(11) DEFAULT NULL COMMENT 'The product its hidden or display? ',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bsc_products
-- ----------------------------
INSERT INTO `bsc_products` VALUES ('1', '1', '01', 'cloth_1.jpg', null, null, null, 'product 1', 'Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis. Vestibulum volutpat, lacus a ultrices sagittis, mi neque euismod dui, eu pulvinar nunc sapien ornare nisl. Phasellus pede arcu, dapibus eu, fermentum et, dapibus sed, urna.àáç', '19.90', '0.00', null, '1', '1');
INSERT INTO `bsc_products` VALUES ('2', '1', '02', 'cloth_2.jpg', null, null, null, 'product 2', 'Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis. Vestibulum volutpat, lacus a ultrices sagittis, mi neque euismod dui, eu pulvinar nunc sapien ornare nisl. Phasellus pede arcu, dapibus eu, fermentum et, dapibus sed, urna.', '19.90', '0.00', null, '2', '1');
INSERT INTO `bsc_products` VALUES ('3', '1', '03', 'cloth_3.jpg', null, null, null, 'Product 3', 'Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis. Vestibulum volutpat, lacus a ultrices sagittis, mi neque euismod dui, eu pulvinar nunc sapien ornare nisl. Phasellus pede arcu, dapibus eu, fermentum et, dapibus sed, urna.', '19.90', '0.00', null, '3', '1');
INSERT INTO `bsc_products` VALUES ('4', '1', '04', 'cloth_4.jpg', null, null, null, 'Product 4', 'Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis. Vestibulum volutpat, lacus a ultrices sagittis, mi neque euismod dui, eu pulvinar nunc sapien ornare nisl. Phasellus pede arcu, dapibus eu, fermentum et, dapibus sed, urna.', '21.90', '0.00', null, '4', '1');
INSERT INTO `bsc_products` VALUES ('5', '1', '05', 'cloth_5.jpg', null, null, null, 'Product 5', 'Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis. Vestibulum volutpat, lacus a ultrices sagittis, mi neque euismod dui, eu pulvinar nunc sapien ornare nisl. Phasellus pede arcu, dapibus eu, fermentum et, dapibus sed, urna.', '19.90', '14.90', null, '5', '1');
INSERT INTO `bsc_products` VALUES ('6', '1', '06', 'cloth_6.jpg', null, null, null, 'product 6', 'Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis. Vestibulum volutpat, lacus a ultrices sagittis, mi neque euismod dui, eu pulvinar nunc sapien ornare nisl. Phasellus pede arcu, dapibus eu, fermentum et, dapibus sed, urna.', '39.90', '0.00', null, '6', '1');
INSERT INTO `bsc_products` VALUES ('7', '1', '07', 'cloth_7.jpg', null, null, null, 'product 7', 'Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis. Vestibulum volutpat, lacus a ultrices sagittis, mi neque euismod dui, eu pulvinar nunc sapien ornare nisl. Phasellus pede arcu, dapibus eu, fermentum et, dapibus sed, urna.', '39.90', '0.00', null, '7', '1');
INSERT INTO `bsc_products` VALUES ('8', '1', '08', 'cloth_8.jpg', null, null, null, 'product 8', 'Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis. Vestibulum volutpat, lacus a ultrices sagittis, mi neque euismod dui, eu pulvinar nunc sapien ornare nisl. Phasellus pede arcu, dapibus eu, fermentum et, dapibus sed, urna.', '34.90', '0.00', null, '8', '1');
INSERT INTO `bsc_products` VALUES ('9', '1', '09', 'cloth_9.jpg', null, null, null, 'product 9', 'Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis. Vestibulum volutpat, lacus a ultrices sagittis, mi neque euismod dui, eu pulvinar nunc sapien ornare nisl. Phasellus pede arcu, dapibus eu, fermentum et, dapibus sed, urna.', '19.90', '0.00', null, '9', '1');
INSERT INTO `bsc_products` VALUES ('10', '1', '10', 'cloth_10.jpg', null, null, null, 'product 10', 'Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis. Vestibulum volutpat, lacus a ultrices sagittis, mi neque euismod dui, eu pulvinar nunc sapien ornare nisl. Phasellus pede arcu, dapibus eu, fermentum et, dapibus sed, urna.', '21.90', '0.00', null, '10', '1');
INSERT INTO `bsc_products` VALUES ('11', '2', null, 'apple_1.png', null, null, null, 'Mac Pro', null, '2400.00', '0.00', null, '1', '1');
INSERT INTO `bsc_products` VALUES ('12', '2', null, 'apple_2.png', null, null, null, 'iMac', null, '1400.00', '0.00', null, '1', '1');
INSERT INTO `bsc_products` VALUES ('13', '2', null, 'apple_4.png', null, null, null, 'Mac Book Pro', null, '1299.00', '0.00', null, '1', '1');
INSERT INTO `bsc_products` VALUES ('14', '2', null, 'apple_3.png', null, null, null, 'iPad Mini', null, '329.00', '0.00', null, '1', '1');
INSERT INTO `bsc_products` VALUES ('15', '2', null, 'apple_5.png', null, null, null, 'iMac Mini', null, '329.00', '0.00', null, '1', '1');
INSERT INTO `bsc_products` VALUES ('16', '3', null, 'cell_1.png', null, null, null, 'Lumina', null, '349.00', '0.00', null, '1', '1');
INSERT INTO `bsc_products` VALUES ('17', '3', null, 'cell_2.png', null, null, null, 'LG', null, '185.00', '0.00', null, '2', '1');
INSERT INTO `bsc_products` VALUES ('18', '3', null, 'cell_3.png', null, null, null, 'S4', null, '495.00', '0.00', null, '3', '1');
INSERT INTO `bsc_products` VALUES ('19', '3', null, 'cell_4.png', null, null, null, 'HTC', null, '495.00', '0.00', null, '4', '1');
INSERT INTO `bsc_products` VALUES ('20', '3', null, 'cell_5.png', null, null, null, 'Sony', null, '175.00', '0.00', null, '5', '1');
INSERT INTO `bsc_products` VALUES ('21', '3', null, 'cell_6.png', null, null, null, 'Mini S', null, '230.00', '0.00', null, '6', '1');
INSERT INTO `bsc_products` VALUES ('22', '3', null, 'cell_7.png', null, null, null, 'iPhone 5 Black', null, '399.00', '0.00', null, '7', '1');
INSERT INTO `bsc_products` VALUES ('23', '3', null, 'cell_8.png', null, null, null, 'iPhone 5 White', null, '399.00', '0.00', null, '8', '1');
INSERT INTO `bsc_products` VALUES ('24', '3', null, 'cell_9.png', null, null, null, 'iPhone 5s', null, '0.00', '0.00', null, '9', '1');
INSERT INTO `bsc_products` VALUES ('25', '3', null, 'cell_10.png', null, null, null, 'LG', null, '165.00', '0.00', null, '10', '1');
INSERT INTO `bsc_products` VALUES ('26', '4', null, 'book_1.png', null, null, null, 'Ilion', null, '17.90', '0.00', null, '1', '1');
INSERT INTO `bsc_products` VALUES ('27', '4', null, 'book_2.png', null, null, null, 'Olimp 1', null, '17.90', '0.00', null, '2', '1');
INSERT INTO `bsc_products` VALUES ('28', '4', null, 'book_3.png', null, null, null, 'Olimp 2', null, '17.90', '0.00', null, '3', '1');
INSERT INTO `bsc_products` VALUES ('29', '4', null, 'book_4.png', null, null, null, 'Clockwork', null, '17.90', '0.00', null, '4', '1');
INSERT INTO `bsc_products` VALUES ('30', '4', null, 'book_5.png', null, null, null, 'Victus', null, '19.90', '0.00', null, '5', '1');
INSERT INTO `bsc_products` VALUES ('31', '4', null, 'book_6.png', null, null, null, 'Hyperion', null, '21.90', '0.00', null, '6', '1');
INSERT INTO `bsc_products` VALUES ('32', '4', null, 'book_7.png', null, null, null, 'Endimion', null, '16.90', '0.00', null, '7', '1');
INSERT INTO `bsc_products` VALUES ('33', '4', null, 'book_8.png', null, null, null, 'SCI London', null, '17.90', '0.00', null, '8', '1');
INSERT INTO `bsc_products` VALUES ('34', '4', null, 'book_9.png', null, null, null, 'The Age of Odin ', null, '17.90', '0.00', null, '9', '1');
INSERT INTO `bsc_products` VALUES ('35', '4', null, 'book_10.png', null, null, null, 'Cold Skin', null, '18.90', '0.00', null, '10', '1');
INSERT INTO `bsc_products` VALUES ('36', '5', null, 'sport_1.png', null, null, null, 'Barcelona', null, '69.00', '0.00', null, '1', '1');
INSERT INTO `bsc_products` VALUES ('37', '5', null, 'sport_2.png', null, null, null, 'Barcelona', null, '69.00', '0.00', null, '2', '1');
INSERT INTO `bsc_products` VALUES ('38', '5', null, 'sport_3.png', null, null, null, 'Barcelona Ball', null, '69.00', '0.00', null, '3', '1');
INSERT INTO `bsc_products` VALUES ('39', '5', null, 'sport_4.png', null, null, null, 'Juventus', null, '69.00', '0.00', null, '4', '1');
INSERT INTO `bsc_products` VALUES ('40', '5', null, 'sport_5.png', null, null, null, 'Juventus', null, '69.00', '0.00', null, '5', '1');
INSERT INTO `bsc_products` VALUES ('41', '5', null, 'sport_6.png', null, null, null, 'Paris Saint-Germain', null, '69.00', '0.00', null, '6', '1');
INSERT INTO `bsc_products` VALUES ('42', '5', null, 'sport_7.png', null, null, null, 'Paris Saint-Germain', null, '69.00', '0.00', null, '7', '1');
INSERT INTO `bsc_products` VALUES ('43', '5', null, 'sport_8.png', null, null, null, 'Chelsea ', null, '69.00', '0.00', null, '8', '1');
INSERT INTO `bsc_products` VALUES ('44', '5', null, 'sport_9.png', null, null, null, 'Chelsea ', null, '69.00', '0.00', null, '9', '1');
INSERT INTO `bsc_products` VALUES ('45', '5', null, 'sport_10.png', null, null, null, 'Chelsea Bag ', null, '69.00', '0.00', null, '10', '1');

-- ----------------------------
-- Table structure for `bsc_sizes`
-- ----------------------------
DROP TABLE IF EXISTS `bsc_sizes`;
CREATE TABLE `bsc_sizes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idProduct` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bsc_sizes
-- ----------------------------
INSERT INTO `bsc_sizes` VALUES ('3', '24', 'Black', '1');
INSERT INTO `bsc_sizes` VALUES ('4', '24', 'White', '2');
INSERT INTO `bsc_sizes` VALUES ('5', '24', 'Gold', '3');
INSERT INTO `bsc_sizes` VALUES ('6', '39', 'S', '1');
INSERT INTO `bsc_sizes` VALUES ('7', '39', 'M', '2');
INSERT INTO `bsc_sizes` VALUES ('8', '39', 'L', '3');
INSERT INTO `bsc_sizes` VALUES ('9', '39', 'XL', '4');
INSERT INTO `bsc_sizes` VALUES ('10', '43', 'S', '1');
INSERT INTO `bsc_sizes` VALUES ('11', '43', 'M', '2');
INSERT INTO `bsc_sizes` VALUES ('12', '43', 'L', '3');
INSERT INTO `bsc_sizes` VALUES ('13', '41', 'S', '1');
INSERT INTO `bsc_sizes` VALUES ('14', '41', 'M', '2');
INSERT INTO `bsc_sizes` VALUES ('15', '41', 'L', '3');
INSERT INTO `bsc_sizes` VALUES ('16', '41', 'XL', '4');
INSERT INTO `bsc_sizes` VALUES ('17', '41', 'XXL', '5');
INSERT INTO `bsc_sizes` VALUES ('18', '2', 'S', '1');
INSERT INTO `bsc_sizes` VALUES ('19', '2', 'M', '2');
INSERT INTO `bsc_sizes` VALUES ('20', '2', 'L', '3');

-- ----------------------------
-- Table structure for `bsc_types`
-- ----------------------------
DROP TABLE IF EXISTS `bsc_types`;
CREATE TABLE `bsc_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idProduct` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` decimal(9,2) DEFAULT NULL,
  `price_offer` decimal(9,2) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bsc_types
-- ----------------------------
INSERT INTO `bsc_types` VALUES ('3', '24', '16 Gb', '599.00', null, '1');
INSERT INTO `bsc_types` VALUES ('4', '24', '32 Gb', '699.00', null, '2');
INSERT INTO `bsc_types` VALUES ('5', '24', '64 Gb', '799.00', null, '3');
INSERT INTO `bsc_types` VALUES ('6', '39', 'S', '10.00', null, '1');
INSERT INTO `bsc_types` VALUES ('7', '39', 'M', '12.00', null, '2');
INSERT INTO `bsc_types` VALUES ('8', '39', 'L', '14.00', null, '3');
INSERT INTO `bsc_types` VALUES ('9', '39', 'XL', '16.00', null, '4');
INSERT INTO `bsc_types` VALUES ('10', '43', 'S', '8.00', null, '1');
INSERT INTO `bsc_types` VALUES ('11', '43', 'M', '9.00', null, '2');
INSERT INTO `bsc_types` VALUES ('12', '43', 'L', '10.00', null, '3');
INSERT INTO `bsc_types` VALUES ('13', '41', 'S', '12.00', null, '1');
INSERT INTO `bsc_types` VALUES ('14', '41', 'M', '13.00', null, '2');
INSERT INTO `bsc_types` VALUES ('15', '41', 'L', '14.00', null, '3');
INSERT INTO `bsc_types` VALUES ('16', '41', 'XL', '16.00', null, '4');
INSERT INTO `bsc_types` VALUES ('17', '41', 'XXL', '17.00', null, '5');
INSERT INTO `bsc_types` VALUES ('18', '2', 'S', '19.00', null, '1');
INSERT INTO `bsc_types` VALUES ('19', '2', 'M', '21.00', null, '2');
INSERT INTO `bsc_types` VALUES ('20', '2', 'L', '22.00', null, '3');
