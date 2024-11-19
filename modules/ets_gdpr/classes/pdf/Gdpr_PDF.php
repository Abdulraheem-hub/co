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
if (!defined('_PS_GDPR_PDF_DIR_')) {
    define('_PS_GDPR_PDF_DIR_', _PS_MODULE_DIR_ . 'ets_gdpr/views/templates/api/pdf/');
}
require_once(dirname(__FILE__) . '/HTMLTemplateGdpr.php');
require_once(dirname(__FILE__) . '/HTMLTemplateOrder.php');
require_once(dirname(__FILE__) . '/HTMLTemplateObject.php');

class Gdpr_PDF extends PDF
{
    public $is17 = false;
    const GDPR_TEMPLATE_ORDER = 'Order';
    const GDPR_TEMPLATE_OBJECT = 'Object';
    public $send_bulk_flag = false;

    public function __construct($objects, $template, $smarty, $orientation = 'P')
    {
        parent::__construct($objects, $template, $smarty, $orientation);
        if (count($this->objects) > 1) {
            $this->send_bulk_flag = true;
        }
    }

    public function render($display = true)
    {
        $render = false;
        $this->pdf_renderer->setFontForLang(Context::getContext()->language->iso_code);
        foreach ($this->objects as $key => $params) {
            if (!empty($params['object']) && !empty($params['template'])) {
                if ($key != 'order') {
                    $this->renderPDFs($params, self::GDPR_TEMPLATE_OBJECT);
                } else {
                    foreach ($params['object'] as $object) {
                        $orderInvoice = $this->getOrderInvoice($object->id);
                        $order = $orderInvoice ? $orderInvoice : $object;
                        $this->renderPDFs($order, $orderInvoice ? self::TEMPLATE_INVOICE : self::GDPR_TEMPLATE_ORDER);
                    }
                }
                $render = true;
            }
        }
        if (empty($this->filename)) {
            $this->filename = 'myPersonalData.pdf';
        }
        if ($render) {
            // clean the output buffer
            if (ob_get_level() && ob_get_length() > 0) {
                ob_clean();
            }
            return $this->pdf_renderer->render($this->filename, $display);
        }
        if (version_compare(_PS_VERSION_, '1.7', '>='))
            $this->is17 = true;
    }

    public function renderPDFs($params, $template)
    {
        $this->pdf_renderer->startPageGroup();
        $tmpObject = $this->getTemplateObject(array(
            'object' => $params,
            'template' => $template
        ));
        if (!$tmpObject)
            return false;
        $tmpObject->assignHookData($params);
        $this->pdf_renderer->createHeader($tmpObject->getHeader());
        $this->pdf_renderer->createFooter($tmpObject->getFooter());
        if ($this->is17)
            $this->pdf_renderer->createPagination($tmpObject->getPagination());
        $content = $tmpObject->getContent();
        $this->pdf_renderer->createContent($content);
        $this->pdf_renderer->AddPage();
        $this->pdf_renderer->writeHTML($content, true, false, true, false, '');
        $this->pdf_renderer->writePage();
        unset($tmpObject);
    }

    public function getOrderInvoice($id_order)
    {
        $id_order_invoice = (int)Db::getInstance()->getValue('
            SELECT oi.id_order_invoice
            FROM `' . _DB_PREFIX_ . 'order_invoice` oi
            LEFT JOIN `' . _DB_PREFIX_ . 'orders` o ON (o.`id_order` = oi.`id_order`)
            WHERE oi.id_order = ' . (int)$id_order . ' 
            ' . Shop::addSqlRestriction(Shop::SHARE_ORDER, 'o') . '
            AND oi.number > 0
            ORDER BY oi.date_add ASC
        ');
        return $id_order_invoice ? new OrderInvoice($id_order_invoice) : false;
    }

    public function getTemplateObject($params)
    {
        $class = false;
        $class_name = 'HTMLTemplate' . (!empty($params['template']) ? $params['template'] : '');
        if (class_exists($class_name) && !empty($params['object'])) {
            $class = new $class_name($params['object'], $this->smarty, $this->send_bulk_flag);
            if (!($class instanceof HTMLTemplate)) {
                throw new PrestaShopException('Invalid class. It should be an instance of HTMLTemplate');
            }
        }
        return $class;
    }
}
