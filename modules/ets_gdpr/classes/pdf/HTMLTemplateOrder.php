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

class HTMLTemplateOrder extends HTMLTemplateGdpr
{
    public $order;

    public function __construct($order, $smarty)
    {
        if (is_array($order) && count($order) > 0 && $order[0] instanceof Order)
            $order = $order[0];
        $this->order = $order;
        $this->smarty = $smarty;
        $this->date = Tools::displayDate($order->date_add);
        $this->title = self::l('Order');
        $this->shop = new Shop((int)$this->order->id_shop);
        if (version_compare(_PS_VERSION_, '1.7', '>='))
            $this->is17 = true;
    }

    public function getContent()
    {
        $invoiceAddressPatternRules = json_decode(Configuration::get('PS_INVCE_INVOICE_ADDR_RULES'), true);
        $deliveryAddressPatternRules = json_decode(Configuration::get('PS_INVCE_DELIVERY_ADDR_RULES'), true);

        $invoice_address = new Address((int)$this->order->id_address_invoice);

        $formatted_invoice_address = AddressFormat::generateAddress($invoice_address, $invoiceAddressPatternRules, Module::getInstanceByName('ets_gdpr')->displayText('', 'br', ''), ' ');

        $delivery_address = null;
        $formatted_delivery_address = '';
        if (isset($this->order->id_address_delivery) && $this->order->id_address_delivery) {
            $delivery_address = new Address((int)$this->order->id_address_delivery);
            $formatted_delivery_address = AddressFormat::generateAddress($delivery_address, $deliveryAddressPatternRules, Module::getInstanceByName('ets_gdpr')->displayText('', 'br', ''), ' ');
        }
        $customer = new Customer((int)$this->order->id_customer);
        $carrier = new Carrier((int)$this->order->id_carrier);
        $order_details = $this->order->getProducts();
        if ($order_details) {
            foreach ($order_details as &$order_detail) {
                if ($order_detail['image'] != null) {
                    $name = 'product_mini_' . (int)$order_detail['product_id'] . (isset($order_detail['product_attribute_id']) ? '_' . (int)$order_detail['product_attribute_id'] : '') . '.jpg';
                    $path = _PS_PROD_IMG_DIR_ . $order_detail['image']->getExistingImgPath() . '.jpg';

                    $order_detail['image_tag'] = preg_replace(
                        '/\.*' . preg_quote(__PS_BASE_URI__, '/') . '/',
                        _PS_ROOT_DIR_ . DIRECTORY_SEPARATOR,
                        ImageManager::thumbnail($path, $name, 45, 'jpg', false),
                        1
                    );

                    if (file_exists(_PS_TMP_IMG_DIR_ . $name)) {
                        $order_detail['image_size'] = getimagesize(_PS_TMP_IMG_DIR_ . $name);
                    } else {
                        $order_detail['image_size'] = false;
                    }
                }
            }
            unset($order_detail);
        }
        $cart_rules = $this->order->getCartRules($this->order->id);
        $free_shipping = false;
        foreach ($cart_rules as $key => $cart_rule) {
            if ($cart_rule['free_shipping']) {
                $free_shipping = true;
                $cart_rules[$key]['value_tax_excl'] -= $this->order->total_shipping_tax_excl;
                $cart_rules[$key]['value'] -= $this->order->total_shipping_tax_incl;
                if ($cart_rules[$key]['value'] == 0) {
                    unset($cart_rules[$key]);
                }
            }
        }
        $product_taxes = 0;
        $product_discounts_tax_excl = $this->order->total_discounts_tax_excl;
        $product_discounts_tax_incl = $this->order->total_discounts_tax_incl;
        if ($free_shipping) {
            $product_discounts_tax_excl -= $this->order->total_shipping_tax_excl;
            $product_discounts_tax_incl -= $this->order->total_shipping_tax_incl;
        }

        $products_after_discounts_tax_excl = $this->order->total_products - $product_discounts_tax_excl;
        $products_after_discounts_tax_incl = $this->order->total_products_wt - $product_discounts_tax_incl;

        $shipping_tax_excl = $free_shipping ? 0 : $this->order->total_shipping_tax_excl;
        $shipping_tax_incl = $free_shipping ? 0 : $this->order->total_shipping_tax_incl;
        $shipping_taxes = $shipping_tax_incl - $shipping_tax_excl;

        $wrapping_taxes = $this->order->total_wrapping_tax_incl - $this->order->total_wrapping_tax_excl;

        $total_taxes = $this->order->total_paid_tax_incl - $this->order->total_paid_tax_excl;

        $footer = array(
            'products_before_discounts_tax_excl' => $this->order->total_products,
            'product_discounts_tax_excl' => $product_discounts_tax_excl,
            'products_after_discounts_tax_excl' => $products_after_discounts_tax_excl,
            'products_before_discounts_tax_incl' => $this->order->total_products_wt,
            'product_discounts_tax_incl' => $product_discounts_tax_incl,
            'products_after_discounts_tax_incl' => $products_after_discounts_tax_incl,
            'product_taxes' => $product_taxes,
            'shipping_tax_excl' => $shipping_tax_excl,
            'shipping_taxes' => $shipping_taxes,
            'shipping_tax_incl' => $shipping_tax_incl,
            'wrapping_tax_excl' => $this->order->total_wrapping_tax_excl,
            'wrapping_taxes' => $wrapping_taxes,
            'wrapping_tax_incl' => $this->order->total_wrapping_tax_incl,
            'ecotax_taxes' => $total_taxes - $product_taxes - $wrapping_taxes - $shipping_taxes,
            'total_taxes' => $total_taxes,
            'total_paid_tax_excl' => $this->order->total_paid_tax_excl,
            'total_paid_tax_incl' => $this->order->total_paid_tax_incl
        );

        foreach ($footer as $key => $value) {
            $footer[$key] = Tools::ps_round($value, (!defined(_PS_PRICE_COMPUTE_PRECISION_) && _PS_PRICE_COMPUTE_PRECISION_ ? _PS_PRICE_COMPUTE_PRECISION_ : 2), $this->order->round_mode);
        }
        $round_type = null;
        if (!empty($this->order->round_type)) {
            switch ($this->order->round_type) {
                case Order::ROUND_TOTAL:
                    $round_type = 'total';
                    break;
                case Order::ROUND_LINE:
                    $round_type = 'line';
                    break;
                case Order::ROUND_ITEM:
                    $round_type = 'item';
                    break;
                default:
                    $round_type = 'line';
                    break;
            }
        }
        $display_product_images = Configuration::get('PS_PDF_IMG_INVOICE');
        $tax_excluded_display = Group::getPriceDisplayMethod($customer->id_default_group);
        $layout = $this->computeLayout(array('has_discount' => false));
        $data = array(
            'order' => $this->order,
            'order_details' => $order_details,
            'carrier' => $carrier,
            'cart_rules' => $cart_rules,
            'delivery_address' => $formatted_delivery_address,
            'invoice_address' => $formatted_invoice_address,
            'addresses' => array('invoice' => $invoice_address, 'delivery' => $delivery_address),
            'tax_excluded_display' => $tax_excluded_display,
            'display_product_images' => $display_product_images,
            'customer' => $customer,
            'footer' => $footer,
            'ps_price_compute_precision' => _PS_PRICE_COMPUTE_PRECISION_,
            'round_type' => $round_type,
            'layout' => $layout,
        );
        if (version_compare(_PS_VERSION_, '1.6.1', '>=')) {
            $data['payments'] = OrderPayment::getByOrderReference($this->order->reference);
        }
        $this->smarty->assign($data);

        $tpls = array(
            'style_tab' => $this->smarty->fetch($this->getTemplate('order.style-tab')),
            'addresses_tab' => $this->smarty->fetch($this->getTemplate('order.addresses-tab')),
            'summary_tab' => $this->smarty->fetch($this->getTemplate('order.summary-tab')),
            'product_tab' => $this->smarty->fetch($this->getTemplate('order.product-tab')),
            'payment_tab' => $this->smarty->fetch($this->getTemplate('order.payment-tab')),
            'note_tab' => $this->smarty->fetch($this->getTemplate('order.note-tab')),
            'total_tab' => $this->smarty->fetch($this->getTemplate('order.total-tab')),
            'shipping_tab' => $this->smarty->fetch($this->getTemplate('order.shipping-tab')),
        );
        $this->smarty->assign($tpls);

        return $this->smarty->fetch($this->getTemplate('order'));
    }
}