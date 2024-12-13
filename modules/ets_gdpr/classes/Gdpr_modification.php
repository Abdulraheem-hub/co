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


class Gdpr_modification extends ObjectModel
{
    public $id_ets_gdpr_modification;
    public $id_customer;
    public $id_shop;
    public $modified_by;
    public $data_modified;
    public $details;
    public $modified_date_time;

    public static $definition = array(
        'table' => 'ets_gdpr_modification',
        'primary' => 'id_ets_gdpr_modification',
        'fields' => array(
            'id_customer' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'modified_by' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'data_modified' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'details' => array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml'),
            'modified_date_time' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
        ),
    );

    public static function deleteSelectedItem($selection)
    {
        if (!$selection || !Validate::isArrayWithIds($selection)) {
            return false;
        }
        $context = Context::getContext();
        return (bool)Db::getInstance()->execute('
			DELETE  FROM `' . _DB_PREFIX_ . 'ets_gdpr_modification`
			WHERE id_shop = ' . (int)$context->shop->id . ' 
				AND `id_ets_gdpr_modification` IN (' . implode(',', array_map('intval', $selection)) . ')
		');
    }

    public static function deleteDataModification($id_customer)
    {
        if (!$id_customer || !Validate::isUnsignedInt($id_customer)) {
            return false;
        }
        if (Configuration::get('ETS_GDPR_DELETION_TYPE') == 'COMPLETE') {
            Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'ets_gdpr_modification` WHERE id_customer=' . (int)$id_customer);
        } else {
            Db::getInstance()->execute('
	            UPDATE `' . _DB_PREFIX_ . 'ets_gdpr_modification` 
	            SET 
	                id_customer = 0,
		            modified_by = "Undefined",
		            data_modified = "Undefined",
		            details = "Undefined"
	            WHERE id_customer = ' . (int)$id_customer
            );
        }
    }
}