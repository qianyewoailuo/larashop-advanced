-- MySQL dump 10.13  Distrib 5.7.22, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: larashop-advanced
-- ------------------------------------------------------
-- Server version	5.7.22-0ubuntu18.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `admin_menu`
--

LOCK TABLES `admin_menu` WRITE;
/*!40000 ALTER TABLE `admin_menu` DISABLE KEYS */;
INSERT INTO `admin_menu` VALUES (1,0,1,'首页','fa-bar-chart','/',NULL,NULL),(2,0,7,'系统管理','fa-tasks','',NULL,'2019-05-26 09:37:12'),(3,2,8,'管理员','fa-users','auth/users',NULL,'2019-05-26 09:37:12'),(4,2,9,'角色','fa-user','auth/roles',NULL,'2019-05-26 09:37:12'),(5,2,10,'权限','fa-ban','auth/permissions',NULL,'2019-05-26 09:37:12'),(6,2,11,'菜单','fa-bars','auth/menu',NULL,'2019-05-26 09:37:12'),(7,2,12,'操作日志','fa-history','auth/logs',NULL,'2019-05-26 09:37:12'),(8,0,2,'用户管理','fa-users','/users','2019-05-16 22:45:15','2019-05-16 22:45:32'),(9,0,4,'商品管理','fa-cubes','/products','2019-05-16 23:00:03','2019-05-26 09:37:12'),(10,0,5,'订单管理','fa-rmb','/orders','2019-05-19 20:58:19','2019-05-26 09:37:12'),(11,0,6,'优惠券管理','fa-tags','/coupon_codes','2019-05-22 18:53:04','2019-05-26 09:37:12'),(12,0,3,'商品类目管理','fa-list','/categories','2019-05-26 09:36:51','2019-05-26 09:37:12'),(13,9,0,'普通商品','fa-cube','/products','2019-05-27 13:26:04','2019-05-27 13:26:04'),(14,9,0,'众筹商品','fa-flag-checkered','crowdfunding_products','2019-05-27 13:30:36','2019-05-27 13:30:36');
/*!40000 ALTER TABLE `admin_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_permissions`
--

LOCK TABLES `admin_permissions` WRITE;
/*!40000 ALTER TABLE `admin_permissions` DISABLE KEYS */;
INSERT INTO `admin_permissions` VALUES (1,'All permission','*','','*',NULL,NULL),(2,'Dashboard','dashboard','GET','/',NULL,NULL),(3,'Login','auth.login','','/auth/login\r\n/auth/logout',NULL,NULL),(4,'User setting','auth.setting','GET,PUT','/auth/setting',NULL,NULL),(5,'Auth management','auth.management','','/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs',NULL,NULL),(6,'用户管理','users','','/users*','2019-05-16 22:52:22','2019-05-16 22:52:22'),(7,'商品管理','products','','/products*','2019-05-23 20:19:31','2019-05-23 20:19:31'),(8,'订单管理','orders','','/orders*','2019-05-23 20:19:55','2019-05-23 20:19:55'),(9,'优惠券管理','coupon_codes','','/coupon_codes*','2019-05-23 20:20:36','2019-05-23 20:20:36');
/*!40000 ALTER TABLE `admin_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_role_menu`
--

LOCK TABLES `admin_role_menu` WRITE;
/*!40000 ALTER TABLE `admin_role_menu` DISABLE KEYS */;
INSERT INTO `admin_role_menu` VALUES (1,2,NULL,NULL),(1,2,NULL,NULL),(1,2,NULL,NULL),(1,2,NULL,NULL);
/*!40000 ALTER TABLE `admin_role_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_role_permissions`
--

LOCK TABLES `admin_role_permissions` WRITE;
/*!40000 ALTER TABLE `admin_role_permissions` DISABLE KEYS */;
INSERT INTO `admin_role_permissions` VALUES (1,1,NULL,NULL),(1,1,NULL,NULL),(1,1,NULL,NULL),(1,1,NULL,NULL),(2,2,NULL,NULL),(2,3,NULL,NULL),(2,4,NULL,NULL),(2,6,NULL,NULL),(2,7,NULL,NULL),(2,8,NULL,NULL),(2,9,NULL,NULL);
/*!40000 ALTER TABLE `admin_role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_role_users`
--

LOCK TABLES `admin_role_users` WRITE;
/*!40000 ALTER TABLE `admin_role_users` DISABLE KEYS */;
INSERT INTO `admin_role_users` VALUES (1,1,NULL,NULL),(1,1,NULL,NULL),(1,1,NULL,NULL),(1,1,NULL,NULL),(2,2,NULL,NULL);
/*!40000 ALTER TABLE `admin_role_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_roles`
--

LOCK TABLES `admin_roles` WRITE;
/*!40000 ALTER TABLE `admin_roles` DISABLE KEYS */;
INSERT INTO `admin_roles` VALUES (1,'Administrator','administrator','2019-05-16 22:42:09','2019-05-16 22:42:09'),(2,'运营','operator','2019-05-16 22:55:25','2019-05-16 22:55:25');
/*!40000 ALTER TABLE `admin_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_user_permissions`
--

LOCK TABLES `admin_user_permissions` WRITE;
/*!40000 ALTER TABLE `admin_user_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_user_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (1,'admin','$2y$10$x28NVV/6hpyzoC8jNrFg8.myKIijDKQbFJ3oCIvj/L9paznQdLSUC','Administrator',NULL,'IVXYvFpwJXtHoFOzKokF2BnKNwgAnEAD1Y3XctIKtZZkMfHCsTMtrRQ3xrYm','2019-05-16 22:42:09','2019-05-16 22:42:09'),(2,'operator','$2y$10$Ykm72nKMEYapIBg9.IcJv.0N9evNOFJrmRqF/K/LzGYYmtjhmOl6W','运营',NULL,NULL,'2019-05-16 22:57:04','2019-05-16 22:57:04');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-05-27  5:54:24
