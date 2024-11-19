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
function upgrade_module_1_0_2($object)
{
    $res =  $object->_installConfigs(true);
    $res &= Db::getInstance()->execute("
        CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."ets_gdpr_log` (
         `id_ets_gdpr_log` int(11) NOT NULL AUTO_INCREMENT,
         `id_shop` int(11) NOT NULL,
         `id_customer` int(11) DEFAULT NULL,
         `ip` varchar(50) DEFAULT NULL,
         `browser` varchar(70) DEFAULT NULL,
         `accepted` tinyint(1) DEFAULT NULL,
         `datetime_added` datetime NOT NULL,
          PRIMARY KEY (`id_ets_gdpr_log`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");
    $res &= $object->_registerHook();
    return $res;
}