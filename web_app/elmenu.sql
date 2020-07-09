/*
 Navicat Premium Data Transfer

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 80017
 Source Host           : localhost:3306
 Source Schema         : elmenu

 Target Server Type    : MySQL
 Target Server Version : 80017
 File Encoding         : 65001

 Date: 09/07/2020 15:51:27
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for menu_category
-- ----------------------------
DROP TABLE IF EXISTS `menu_category`;
CREATE TABLE `menu_category`  (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`category_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of menu_category
-- ----------------------------
INSERT INTO `menu_category` VALUES (1, 'الوجبات الرئيسية');
INSERT INTO `menu_category` VALUES (2, 'التحلية');

-- ----------------------------
-- Table structure for menu_item
-- ----------------------------
DROP TABLE IF EXISTS `menu_item`;
CREATE TABLE `menu_item`  (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `item_details` varchar(8000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `item_price` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `item_photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `category_id` int(11) NULL DEFAULT NULL,
  `isPopular` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'false',
  PRIMARY KEY (`item_id`) USING BTREE,
  INDEX `category_id`(`category_id`) USING BTREE,
  CONSTRAINT `menu_item_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `menu_category` (`category_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of menu_item
-- ----------------------------
INSERT INTO `menu_item` VALUES (1, 'Stripes', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam ligula sapien, dapibus a metus vitae, vehicula porttitor turpis. Vivamus bibendum placerat ligula molestie maximus. Donec maximus mi at sapien convallis, ac condimentum purus varius. Praesent eget dui nec est pellentesque sollicitudin. Vestibulum vitae suscipit elit, id porttitor eros. Fusce eget iaculis turpis. Cras id tempus elit, at auctor ante. Maecenas dapibus auctor neque, sed pharetra nisi ultrices et.', '100.5', 'menu/22821727b0b8a7ac8d61b55d70c463ea.jpg', 1, 'true');
INSERT INTO `menu_item` VALUES (2, 'Harabhara Kabab', 'when time is less than current time and date more than current date api gives message select time greater than current time', '100.00', 'menu/b6f91861f0b6d68993faf1d033a813a9.jpg', 1, 'true');
INSERT INTO `menu_item` VALUES (3, 'Pav Bhaji', 'Pav Bhaji - a spicy curry of mixed vegetables (bhaji) cooked in a special blend of spices and served with soft bread pav shallow fried in butter.', '92.00', 'menu/d7ad9c08c281ac2022e17edf53f8b657.jpg', 2, 'false');
INSERT INTO `menu_item` VALUES (4, 'Medu vada', 'Medu vada (ulundu vadai) is a traditional dish from South Indian cuisine served with coconut chutney and Vegetable Sambar as a popular breakfast in most of the Indian restaurants.', '60.00', 'menu/0d8bf7a446ab39e6a2b24e2cd54dfca1.jpg', 2, 'false');
INSERT INTO `menu_item` VALUES (5, 'Combo Pizza', 'The deliciousness of their food comes from the cheese and crunchy crust that is hard to resist. Plus they have a wide variety of offers like Domino pizza combo, ...', '200.00', 'menu/11be64548307634fc880f6f40c271e84.jpg', 1, 'true');
INSERT INTO `menu_item` VALUES (6, 'Stripes', '--', '100.5', 'menu/22821727b0b8a7ac8d61b55d70c463ea.jpg', 2, 'false');

-- ----------------------------
-- Table structure for offers
-- ----------------------------
DROP TABLE IF EXISTS `offers`;
CREATE TABLE `offers`  (
  `entity_id` int(11) NOT NULL AUTO_INCREMENT,
  `offer_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `offer_discription` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `offer_detalis` varchar(8000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `offer_price` decimal(10, 2) NULL DEFAULT NULL,
  `offer_discount` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `is_discount` int(1) NOT NULL,
  `offer_photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`entity_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of offers
-- ----------------------------
INSERT INTO `offers` VALUES (1, 'test', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam ligula sapien, dapibus a metus vitae, vehicula porttitor turpis. Vivamus bibendum placerat ligula molestie maximus. Donec maximus mi at sapien convallis, ac condimentum purus varius. Praesent eget dui nec est pellentesque sollicitudin. Vestibulum vitae suscipit elit, id porttitor eros. Fusce eget iaculis turpis. Cras id tempus elit, at auctor ante. Maecenas dapibus auctor neque, sed pharetra nisi ultrices et.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam ligula sapien, dapibus a metus vitae, vehicula porttitor turpis. Vivamus bibendum placerat ligula molestie maximus. Donec maximus mi at sapien convallis, ac condimentum purus varius. Praesent eget dui nec est pellentesque sollicitudin. Vestibulum vitae suscipit elit, id porttitor eros. Fusce eget iaculis turpis. Cras id tempus elit, at auctor ante. Maecenas dapibus auctor neque, sed pharetra nisi ultrices et.', 100.00, '50', 1, NULL);

SET FOREIGN_KEY_CHECKS = 1;
