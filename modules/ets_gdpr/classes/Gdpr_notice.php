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

class Gdpr_notice extends ObjectModel
{
    public $id_ets_gdpr_notice;
    public $id_shop;
    public $title;
    public $description;
    public $display_to;
    public $enabled;
    public $position;

    public static $definition = array(
        'table' => 'ets_gdpr_notice',
        'primary' => 'id_ets_gdpr_notice',
        'multilang' => true,
        'fields' => array(
	        'id_shop' => array('type' => self::TYPE_INT,'validate' => 'isUnsignedId'),
            'display_to' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
	        'enabled' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
	        'position' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
	        // Lang fields
	        'title' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString'),
	        'description' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString'),
        ),
    );

	public function __construct($id = null, $id_lang = null, $id_shop = null,  Context $context = null)
	{
		parent::__construct($id, $id_lang, $id_shop);
		unset($context);
	}

	public function maxVal($id_shop = null)
	{
		$context = Context::getContext();
		if(Db::getInstance()->getValue("SELECT COUNT(`id_ets_gdpr_notice`) FROM `" . _DB_PREFIX_ . pSQL(self::$definition['table'])."` WHERE id_shop=".($id_shop? (int)$id_shop:(int)$context->shop->id)) > 0)
		{
			return ($max = Db::getInstance()->getValue("SELECT MAX(`position`) FROM `" . _DB_PREFIX_ . pSQL(self::$definition['table'])."` WHERE id_shop=".($id_shop? (int)$id_shop:(int)$context->shop->id))) ? (int)$max : 0;
		}
		else
			return -1;
	}

	public function updatePosition($way, $position)
	{
		if (!$res = Db::getInstance()->executeS('
            SELECT `id_ets_gdpr_notice`, `position`
			FROM `'._DB_PREFIX_.'ets_gdpr_notice`
			WHERE `id_shop` = '.(int)Context::getContext()->shop->id.'
			ORDER BY `position` ASC'
		)) {
			return false;
		}
		foreach ($res as $notice) {
			if ((int)$notice['id_ets_gdpr_notice'] == (int)$this->id) {
				$moved_notice = $notice;
				break;
			}
		}
		if (!isset($moved_notice) || !isset($position)) {
			return false;
		}
		$this->position = (int)$position;
		return (
			Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'ets_gdpr_notice`
			SET `position`= `position` '.($way ? '- 1' : '+ 1').'
			WHERE `position`'.($way ?
					'> '.(int)$moved_notice['position'].' AND `position` <= '.(int)$position
					: '< '.(int)$moved_notice['position'].' AND `position` >= '.(int)$position).' 
					AND `id_shop` = '.(int)Context::getContext()->shop->id)
		        && Db::getInstance()->execute('
		        UPDATE `'._DB_PREFIX_.'ets_gdpr_notice` 
		        SET `position`= '.(int)$position.' 
		        WHERE id_ets_gdpr_notice='.(int)$this->id)
		);
	}

	public static function cleanPositions()
	{
		Db::getInstance()->execute('SET @i = -1', false);
		$return = Db::getInstance()->execute('
				UPDATE `'._DB_PREFIX_.'ets_gdpr_notice`
				SET `position` = @i:=@i+1
				WHERE id_shop = '.(int)Context::getContext()->shop->id.'
				ORDER BY `position`'
		);

		return $return;
	}
}