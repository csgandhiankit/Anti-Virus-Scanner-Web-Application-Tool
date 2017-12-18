SET sql_mode = '';
DROP DATABASE if EXISTS `VirusDB`;
CREATE DATABASE `VirusDB`;
USE `VirusDB`;
DROP TABLE 	IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `username` varchar(30) NOT NULL UNIQUE,
  `admin` BOOLEAN Default FALSE NOT NULL,
  `contributor` BOOLEAN Default FALSE NOT NULL,
  `email` varchar(60) NOT NULL UNIQUE,
  `password` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
);


DROP TABLE 	IF EXISTS `malware`;
CREATE TABLE IF NOT EXISTS `malware` (
  `vid` INT NOT NULL AUTO_INCREMENT,
  `signature` varchar(20) UNIQUE NOT NULL,
  PRIMARY KEY (`vid`)
);


DROP TABLE  IF EXISTS `usermalware`;
CREATE TABLE IF NOT EXISTS `usermalware` (
  `vid` int(8) NOT NULL AUTO_INCREMENT,
  `signature` varchar(20) UNIQUE NOT NULL,
  PRIMARY KEY (`vid`)
);
