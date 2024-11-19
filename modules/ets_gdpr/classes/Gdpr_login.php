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


class Gdpr_login extends ObjectModel
{
    public $id_ets_gdpr_login;
    public $id_customer;
    public $id_shop;
    public $ip;
    public $device;
    public $login_date_time;

    public static $definition = array(
        'table' => 'ets_gdpr_login',
        'primary' => 'id_ets_gdpr_login',
        'fields' => array(
            'id_customer' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'ip' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'device' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'login_date_time' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
        ),
    );

    public static function deleteSelectedItem($selection)
    {
        if (!$selection || !Validate::isArrayWithIds($selection)) {
            return false;
        }
        $context = Context::getContext();
        return (bool)Db::getInstance()->execute('
			DELETE  FROM `' . _DB_PREFIX_ . 'ets_gdpr_login`
			WHERE id_shop = ' . (int)$context->shop->id . ' 
				AND `id_ets_gdpr_login` IN (' . implode(',', array_map('intval', $selection)) . ')
		');
    }


    /**
     * @param $id_customer
     */
    public static function deleteDataLoginLog($id_customer)
    {
        if (!$id_customer || !Validate::isUnsignedInt($id_customer)) {
            return false;
        }
        if (trim(Configuration::get('ETS_GDPR_DELETION_TYPE')) == 'COMPLETE') {
            Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'ets_gdpr_login` WHERE id_customer = ' . (int)$id_customer);
        } else {
            Db::getInstance()->execute('
	            UPDATE `' . _DB_PREFIX_ . 'ets_gdpr_login` 
	            SET 
	                id_customer = 0, 
		            ip = "Undefined",
		            device = "Undefined"
	            WHERE id_customer=' . (int)$id_customer
            );
        }
    }
}