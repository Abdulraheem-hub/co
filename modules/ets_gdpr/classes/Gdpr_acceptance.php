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
class Gdpr_acceptance extends ObjectModel
{
	public $id_customer;
	public $datatime_added;

	public static $definition = array(
		'table' => 'ets_gdpr_acceptance',
		'primary' => 'id_customer',
		'fields' => array(
			'id_customer' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'datatime_added' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
		),
	);
	
	public static function getCustomerAcceptance($id_customer)
	{
		return $id_customer? Db::getInstance()->getValue('
			SELECT id_customer FROM `'._DB_PREFIX_.'ets_gdpr_acceptance` WHERE id_customer = '.(int)$id_customer.'
		') : false;
	}

	public static function acceptSave($context = null)
    {
        if (!$context)
            $context = Context::getContext();
        if ($context->customer->id)
        {
            $acceptance = new Gdpr_acceptance($context->customer->id);
            $acceptance->id_customer = (int)$context->customer->id;
            $acceptance->datatime_added = date('Y-m-d H:i:s');
            $acceptance->save();
        }
    }
}