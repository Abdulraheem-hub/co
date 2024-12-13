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

class HTMLTemplateGdpr extends HTMLTemplate
{
    public $order;

    /**
     * Returns the template's HTML content
     *
     * @return string HTML content
     */
    public function getContent()
    {

    }

    /**
     * Compute layout elements size
     *
     * @param $params Array Layout elements
     *
     * @return Array Layout elements columns size
     */
    protected function computeLayout($params)
    {
        $layout = array(
            'reference' => array(
                'width' => 15,
            ),
            'product' => array(
                'width' => 40,
            ),
            'quantity' => array(
                'width' => 8,
            ),
            'tax_code' => array(
                'width' => 8,
            ),
            'unit_price_tax_excl' => array(
                'width' => 0,
            ),
            'total_tax_excl' => array(
                'width' => 0,
            )
        );

        if (isset($params['has_discount']) && $params['has_discount']) {
            $layout['before_discount'] = array('width' => 0);
            $layout['product']['width'] -= 7;
            $layout['reference']['width'] -= 3;
        }

        $total_width = 0;
        $free_columns_count = 0;
        foreach ($layout as $data) {
            if ($data['width'] === 0) {
                ++$free_columns_count;
            }

            $total_width += $data['width'];
        }

        $delta = 100 - $total_width;

        foreach ($layout as $row => $data) {
            if ($data['width'] === 0) {
                $layout[$row]['width'] = $delta / $free_columns_count;
            }
        }

        $layout['_colCount'] = count($layout);

        return $layout;
    }

    /**
     * Returns the template filename when using bulk rendering
     *
     * @return string filename
     */
    public function getBulkFilename()
    {
        return 'myPersonalData.pdf';
    }

    /**
     * Returns the template filename
     *
     * @return string filename
     */
    public function getFilename()
    {
        return 'myPersonalData.pdf';
    }

    /**
     * If the template is not present in the theme directory, it will return the default template
     * in _PS_PDF_DIR_ directory
     *
     * @param $template_name
     *
     * @return string
     */
    protected function getTemplate($template_name)
    {
        $template = false;
        $default_template = rtrim(_PS_GDPR_PDF_DIR_, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $template_name . '.tpl';
        if (file_exists($default_template)) {
            $template = $default_template;
        }

        return $template;
    }
}