<?php
/**
  * Copyright ETS Software Technology Co., Ltd
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 website only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses.
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future.
 *
 * @author ETS Software Technology Co., Ltd
 * @copyright  ETS Software Technology Co., Ltd
 * @license    Valid for 1 website (or project) for each purchase of license
 */

if (!defined('_PS_VERSION_')) { exit; }

$sql = array();
$sql[] = '
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ets_gdpr_acceptance` (
			  `id_customer` int(11) NOT NULL,
			  `datatime_added` datetime NOT NULL,
			   PRIMARY KEY (`id_customer`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
';
$sql[] = '
			CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ets_gdpr_deletion` (
			  `id_ets_gdpr_deletion` int(11) NOT NULL AUTO_INCREMENT,
			  `id_customer` int(11) NOT NULL,
			  `id_shop` int(11) NOT NULL,
			  `data_to_delete` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `status` varchar(10) CHARACTER SET utf8 NOT NULL,
			  `requested_date_time` datetime NOT NULL,
			  `action_taken_date_time` datetime NOT NULL,
			  PRIMARY KEY (`id_ets_gdpr_deletion`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
';
$sql[] = '
			CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ets_gdpr_login` (
			  `id_ets_gdpr_login` int(11) NOT NULL AUTO_INCREMENT,
			  `id_customer` int(11) NOT NULL,
			  `id_shop` int(11) NOT NULL,
			  `ip` varchar(50) NOT NULL,
			  `device` varchar(255) NOT NULL,
			  `login_date_time` datetime NOT NULL,
			  PRIMARY KEY (`id_ets_gdpr_login`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
';
$sql[] = '
			CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ets_gdpr_modification` (
			  `id_ets_gdpr_modification` int(11) NOT NULL AUTO_INCREMENT,
			  `id_customer` int(11) NOT NULL,
			  `id_shop` int(11) NOT NULL,
			  `modified_by` varchar(255) NOT NULL,
			  `data_modified` varchar(255) NOT NULL,
			  `details` text NOT NULL,
			  `modified_date_time` datetime NOT NULL,
			  PRIMARY KEY (`id_ets_gdpr_modification`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
';
$sql[] = '
			CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ets_gdpr_notice` (
			  `id_ets_gdpr_notice` int(11) NOT NULL AUTO_INCREMENT,
			  `id_shop` int(11) NOT NULL,
			  `display_to` varchar(50) NOT NULL,
			  `enabled` tinyint(1) NOT NULL,
			  `position` int(11) NOT NULL,
			  PRIMARY KEY (`id_ets_gdpr_notice`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
';
$sql[] = '
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ets_gdpr_notice_lang` (
                  `id_ets_gdpr_notice` int(11) NOT NULL,
                  `id_lang` int(11) NOT NULL,
                  `title` varchar(500) NOT NULL,
                  `description` text NOT NULL,
                  PRIMARY KEY (`id_ets_gdpr_notice`,`id_lang`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
';
$sql[] = '
			CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ets_gdpr_log` (
			 `id_ets_gdpr_log` int(11) NOT NULL AUTO_INCREMENT,
			 `id_shop` int(11) NOT NULL,
			 `id_customer` int(11) DEFAULT NULL,
             `ip` varchar(50) DEFAULT NULL,
             `browser` varchar(70) DEFAULT NULL,
             `accepted` tinyint(1) DEFAULT NULL,
             `datetime_added` datetime NOT NULL,
              PRIMARY KEY (`id_ets_gdpr_log`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
