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

class Gdpr_log extends ObjectModel
{
    public $id_shop;
    public $id_customer;
    public $ip;
    public $browser;
    public $accepted;
    public $datetime_added;

    public static $definition = array(
        'table' => 'ets_gdpr_log',
        'primary' => 'id_ets_gdpr_log',
        'fields' => array(
	        'id_shop' => array('type' => self::TYPE_INT,'validate' => 'isUnsignedId'),
            'id_customer' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'ip' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'browser' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
	        'accepted' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'datetime_added' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
        ),
    );

    public static function logVal($ip)
    {
        if (($res = Db::getInstance()->getValue("SELECT datetime_added FROM `"._DB_PREFIX_.pSQL(self::$definition['table'])."` WHERE  ip = '".pSQL($ip)."' ORDER BY datetime_added DESC")))
        {
            if ((time() - strtotime($res)) < 24*3600)
                return false;
        }
        return true;
    }

    public static function writeLogs($context = null)
    {
        if (!$context)
            $context = Context::getContext();
        if (($currentIp = Tools::getRemoteAddr()) && self::logVal($currentIp))
        {
            $gdpr = Ets_gdpr::instance();
            $log = new Gdpr_log();
            $log->id_shop = $context->shop->id;
            $log->id_customer = $context->customer->id;
            $log->ip = $currentIp;
            $log->browser = $gdpr->getDevice();
            $log->accepted = Tools::getValue('accept', false)? 1 : 0;
            $log->datetime_added = date('Y-m-d H:i:s');
            if ($log->validateFields(false))
            {
                $log->save();
            }
            return $log;
        }
    }

    public static function getNbLogs($params)
    {
        if (!(isset($params['chart'])) || !$params['chart'])
            return 0;
        $context = isset($params['context']) && $params['context']? $params['context'] : Context::getContext();
        $groupBy = ' GROUP BY YEAR(gl.datetime_added)';
        if (isset($params['month']) && $params['month'])
            $groupBy .= ', MONTH(gl.datetime_added), DAY(gl.datetime_added)';
        elseif(isset($params['year']) && $params['year'])
            $groupBy .= ', MONTH(gl.datetime_added)';
        $sql = "SELECT ".($params['chart'] != 'pie'? " DATE_FORMAT(gl.datetime_added, '%Y-%m-%d') `date_series`," : '')." COUNT(gl.id_ets_gdpr_log) `total` 
            FROM `"._DB_PREFIX_."ets_gdpr_log` gl
            WHERE gl.id_shop = ".(int)$context->shop->id
            .(isset($params['month']) && pSQL($params['month'])? " AND MONTH(gl.datetime_added) = ".(int)$params['month'] : '')
            .(isset($params['year']) && pSQL($params['year'])? " AND YEAR(gl.datetime_added) = ".(int)$params['year'] : '')
            .(isset($params['accepted'])? " AND gl.accepted = '".pSQL($params['accepted'])."'":'')
            .($params['chart'] != 'pie'? $groupBy." ORDER BY gl.datetime_added ":'');
        return $params['chart'] != 'pie'? Db::getInstance()->executeS($sql):(int)Db::getInstance()->getValue($sql);
    }

    public static function deleteSelectedItem($selection)
    {
        $context = Context::getContext();
        return (bool)Db::getInstance()->execute('
			DELETE  FROM `'._DB_PREFIX_.'ets_gdpr_log`
			WHERE id_shop = '.(int)$context->shop->id.' 
				AND `id_ets_gdpr_log` IN ('.implode(array_map('intval', $selection), ',').')
		');
    }
}