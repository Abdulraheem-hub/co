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


class Gdpr_deletion extends ObjectModel
{
    public $id_ets_gdpr_deletion;
    public $id_customer;
    public $id_shop;
    public $data_to_delete;
    public $status;
    public $requested_date_time;
    public $action_taken_date_time;

    public static $definition = array(
        'table' => 'ets_gdpr_deletion',
        'primary' => 'id_ets_gdpr_deletion',
        'fields' => array(
            'id_customer' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'data_to_delete' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'status' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'requested_date_time' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'action_taken_date_time' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
        ),
    );

    public static function checkRequested($id_customer)
    {
        if (!$id_customer || !Validate::isUnsignedInt($id_customer)) {
            return false;
        }
        $context = Context::getContext();
        return (int)Db::getInstance()->getValue('
			SELECT `id_ets_gdpr_deletion`
			FROM `' . _DB_PREFIX_ . 'ets_gdpr_deletion`
			WHERE id_shop = ' . (int)$context->shop->id . ' AND `id_customer` = ' . (int)$id_customer . ' AND `status` = "PENDING"
		');
    }

    public static function deleteSelectedItem($selection, $status)
    {
        if (!$selection || !Validate::isArrayWithIds($selection) || !$status || !Validate::isGenericName($status)) {
            return false;
        }
        $context = Context::getContext();
        return (bool)Db::getInstance()->execute('
			DELETE  FROM `' . _DB_PREFIX_ . 'ets_gdpr_deletion`
			WHERE id_shop = ' . (int)$context->shop->id . ' 
				AND `id_ets_gdpr_deletion` IN (' . implode(',', array_map('intval', $selection)) . ') AND `status` = "' . pSQL($status) . '"
		');
    }

    public static function deleteDataReviews($id_customer)
    {
        if (!$id_customer || !Validate::isUnsignedInt($id_customer)) {
            return false;
        }
        if (trim(Configuration::get('ETS_GDPR_DELETION_TYPE')) == 'COMPLETE') {
            Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'product_comment` WHERE id_customer=' . (int)$id_customer);
        } else {
            Db::getInstance()->execute('
		        UPDATE `' . _DB_PREFIX_ . 'product_comment` 
		        SET 
		            id_customer = 0,
			        title = "Undefined",
			        content = "Undefined",
			        customer_name = "Undefined"
		        WHERE id_customer=' . (int)$id_customer
            );
        }
    }

    public static function deleteDataMessage($id_customer)
    {
        if (!$id_customer || !Validate::isUnsignedInt($id_customer)) {
            return false;
        }
        if (trim(Configuration::get('ETS_GDPR_DELETION_TYPE')) == 'COMPLETE') {
            Db::getInstance()->execute('DELETE ct.*, cm.* FROM `' . _DB_PREFIX_ . 'customer_thread` ct, `' . _DB_PREFIX_ . 'customer_message` cm WHERE ct.id_customer_thread=cm.id_customer_thread AND ct.id_customer=' . (int)$id_customer);
        } else {
            Db::getInstance()->execute('
	            UPDATE `' . _DB_PREFIX_ . 'customer_message` 
	            SET ip_address = 0,
		            message="Undefined",
		            file_name="Undefined",
		            user_agent=""
	            WHERE id_customer_thread IN (SELECT id_customer_thread FROM `' . _DB_PREFIX_ . 'customer_thread` WHERE id_customer="' . (int)$id_customer . '");
                UPDATE `' . _DB_PREFIX_ . 'customer_thread` 
	            SET 
	                id_customer = 0,
	                email="Undefined",
		            token="Undefined",
		            status="Undefined"
				WHERE id_customer=' . (int)$id_customer
            );
        }
    }

    public static function deleteDataOrder($id_customer)
    {
        if (!$id_customer || !Validate::isUnsignedInt($id_customer)) {
            return false;
        }
        if (trim(Configuration::get('ETS_GDPR_DELETION_TYPE')) == 'COMPLETE') {
            $orders = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'orders` WHERE id_customer=' . (int)$id_customer);
            if ($orders) {
                foreach ($orders as $order) {
                    $orderObj = new Order($order['id_order']);
                    Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'order_history` WHERE id_order=' . (int)$orderObj->id);
                    Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'order_detail` WHERE id_order=' . (int)$orderObj->id);
                    $orderObj->delete();
                }
            }
        } else {
            Db::getInstance()->execute('
	            UPDATE `' . _DB_PREFIX_ . 'orders`  
	            SET 
	             id_customer = 0,
	             id_address_delivery = 0,
	             id_address_invoice = 0
	            WHERE id_customer=' . (int)$id_customer
            );
        }
    }

    public static function deleteGeneralInformation($id_customer)
    {
        if (!$id_customer || !Validate::isUnsignedInt($id_customer)) {
            return false;
        }
        if (Configuration::get('ETS_GDPR_DELETION_TYPE') == 'COMPLETE') {
            Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'customer` WHERE id_customer = ' . (int)$id_customer);
        } else {
            Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'customer`  
            SET  
                company = "Undefined",
                firstname = "Undefined",
                lastname = "Undefined",
                email = "' . (int)$id_customer . '_undefined@undefined.com",
                birthday = "",
                website = "Undefined",
                note = "Undefined",
                deleted = 1
            WHERE id_customer=' . (int)$id_customer);
        }
    }

    public static function deleteDataAddress($id_customer)
    {
        if (!$id_customer || !Validate::isUnsignedInt($id_customer)) {
            return false;
        }
        Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'address` SET deleted=1 WHERE id_customer=' . (int)$id_customer);
    }

    public function deleteDataSubscription($id_customer)
    {
        if (!$id_customer || !Validate::isUnsignedInt($id_customer)) {
            return false;
        }
        Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'customer` SET optin=0, newsletter=0 WHERE id_customer=' . (int)$id_customer);
    }

    public static function isReviews()
    {
        return (bool)Db::getInstance()->executeS('SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = "' . _DB_NAME_ . '" AND table_name = "' . _DB_PREFIX_ . 'product_comment" LIMIT 1');
    }
}