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

class HTMLTemplateObject extends HTMLTemplateGdpr
{
	public $params;

	public function __construct($params, $smarty, $bulk_mode = false)
	{
		$this->params = $params;
		$this->smarty = $smarty;
		$this->title = !empty($this->params['title'])? $this->params['title'] : '';
		unset($bulk_mode);
	}

	public function getContent()
	{
		$data = array(
			'object' => !empty($this->params['object'])? $this->params['object'] : array(),
		);
		$this->smarty->assign($data);

		$tpls = array(
			'style_tab' => $this->smarty->fetch($this->getTemplate('order.style-tab')),
		);
		$this->smarty->assign($tpls);

		if (!empty($this->params['template']))
			return $this->smarty->fetch($this->getTemplate($this->params['template']));
	}
}