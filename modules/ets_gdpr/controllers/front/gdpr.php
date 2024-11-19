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

class Ets_gdprGdprModuleFrontController extends ModuleFrontController
{
    const ETS_REVIEWS = 'productcomments';
    public $_errors = array();
    protected $template = 'gdpr';
    public $is15 = false;

    public function __construct()
    {
        parent::__construct();
        if (!$this->template)
            $this->template = 'gdpr';
        if (!$this->module->is17) {
            if (isset($this->display_column_right)) $this->display_column_right = false;
            if (isset($this->display_column_left)) $this->display_column_left = false;
        }
        $this->is15 = version_compare(_PS_VERSION_, '1.6', '<') ? true : false;
    }

    public function initContent()
    {
        parent::initContent();

        if (Tools::getValue('ajax', false)) {
            if (Tools::getValue('action', false) == 'Acceptance') {
                if (($accept = Tools::getValue('accept', false))) {
                    $this->context->cookie->gdprVisitor = $accept;
                    $this->context->cookie->write();
                    Gdpr_acceptance::acceptSave($this->context);
                }
                Gdpr_log::writeLogs($this->context);
                die(json_encode(array('accept' => $accept)));
            }
        }
        if (!$this->context->customer->isLogged() || (Tools::isSubmit('control') && !Tools::getValue('curTab', false)) || !Tools::isSubmit('control')) {
            Tools::redirectLink($this->context->link->getPageLink('index', true));
        }
        if (Tools::isSubmit('control') && ($currentTabs = urldecode(Tools::getValue('curTab', false))) && $this->context->customer->isLogged() && isset($this->context->customer->id)) {
            $params = array('control' => Tools::getValue('control'), 'curTab' => $currentTabs);
            if (($page = Tools::getValue('page', false)))
                $params['page'] = $page;
            $mLink = $this->context->link->getModuleLink($this->module->name, 'gdpr', $params, Tools::usingSecureMode() ? true : false);
            $breadcrumb = $this->module->getBreadcrumb();
            $customer_privileges = $this->module->assignJsConfig(array(
                'name' => 'customer_privileges'
            ));
            $general = $this->module->assignJsConfig(array(
                'name' => 'general'
            ));
            $configFrontTabs = Gdpr_defines::getInstance()->getConfigFrontTabs(Tools::substr($currentTabs, 0, 3));
            $assign = array(
                'mLink' => $mLink,
                'privileges' => array_merge($customer_privileges, $general),
                'is15' => $this->is15,
                'path' => $breadcrumb,
                'breadcrumb' => $breadcrumb,
                'breadcrumbLabel' => $this->is15 && isset($configFrontTabs['label']) ? $configFrontTabs['label'] : '',
            );
            if (!$this->permission($currentTabs)) {
                $assign['tpl'] = 'permission';
            } elseif ($currentTabs == 'personal') {
                if (Tools::isSubmit('downloadpdf')) {
                    $this->module->processGeneratePDF($this->context->customer->id, $this->_errors);
                } elseif (($itemId = Tools::getValue('id_customer_message', false))) {
                    $assign['message'] = Gdpr_presenter::getInstance()->getContactMessages(array('itemId' => $itemId));
                    $assign['tpl'] = 'message-details';
                } elseif (($itemId = Tools::getValue('id_product_comment', false))) {
                    $assign['reviews'] = Gdpr_presenter::getInstance()->getReviews(array('itemId' => $itemId));
                    $assign['tpl'] = 'reviews-details';
                } else {
                    if (Tools::isSubmit('subscribe') && ($subscribe = $this->module->subScribe($this->_errors)))
                        $assign['msg_success'] = $subscribe;
                    $dataViews = isset($customer_privileges['ETS_GDPR_DATA_TO_VIEW']) && ($res = $customer_privileges['ETS_GDPR_DATA_TO_VIEW']) ? $res : '';
                    $DVs = $dataViews && $dataViews != 'ALL' ? explode(',', $dataViews) : ($dataViews ? 'ALL' : array());
                    $assign = array_merge($assign, array(
                        'ETS_GDPR_WARNING_PERSONAL_PAGE' => Configuration::get('ETS_GDPR_WARNING_PERSONAL_PAGE', $this->context->language->id),
                        'ets_gdpr_customer' => (is_array($DVs) && in_array('GEN', $DVs) || $DVs == 'ALL') ? $this->context->customer : false,
                        'addresses' => (is_array($DVs) && in_array('ADD', $DVs) || $DVs == 'ALL') ? $this->module->getAddress($this->context->customer->id) : false,
                        'orders' => (is_array($DVs) && in_array('ORD', $DVs) || $DVs == 'ALL') ? $this->module->renderGdprList($this->configParams('order')) : false,
                        'messages' => (is_array($DVs) && in_array('MES', $DVs) || $DVs == 'ALL') ? $this->module->renderGdprList($this->configParams('mes')) : false,
                        'reviews' => (is_array($DVs) && in_array('REV', $DVs) || $DVs == 'ALL') && !$this->module->is17 && Module::isEnabled(self::ETS_REVIEWS) ? $this->module->renderGdprList($this->configParams('reviews')) : false,
                        'subscriptions' => (is_array($DVs) && in_array('SUB', $DVs) || $DVs == 'ALL') ? array('newsletter' => $this->context->customer->newsletter, 'partner' => $this->context->customer->optin) : false,
                        'dataTypes' => (is_array($DVs) && in_array('GEN', $DVs) || $DVs == 'ALL') ? array_merge(array(
                            'GEN' => array(
                                'name' => Ets_gdpr::$trans['general_info'],
                                'id_option' => 'GEN',
                                'tmp' => 'information',
                                'label' => Ets_gdpr::$trans['general_info'],
                            )
                        ), Gdpr_defines::getInstance()->getDataTypes()) : Gdpr_defines::getInstance()->getDataTypes(),
                        'DVs' => $DVs,
                        'tpl' => 'personal'
                    ));
                }
            } elseif ($currentTabs == 'mod_log') {
                if (Tools::isSubmit('download')) {
                    $this->_errors = $this->module->exportCSV(array(
                        'id_customer' => $this->context->customer->id,
                        'name' => 'mlog_fields',
                        'frontend' => true,
                    ));
                } elseif (($itemId = Tools::getValue('id_ets_gdpr_modification', false))) {
                    $assign['mLog'] = Gdpr_presenter::getInstance()->getMLogs(array('itemId' => $itemId));
                    $assign['tpl'] = 'modification-log';
                } else {
                    $mlog_fields = Gdpr_defines::getInstance()->getModifiedLogFields();
                    $mlog_fields['list'] = array_merge($mlog_fields['list'], array(
                        'name' => 'gdpr_modification',
                        'curTab' => 'mod_log',
                        'limit' => 2,
                        'identifier' => 'id_ets_gdpr_modification',
                    ));
                    $this->displayFields($mlog_fields['fields']);
                    $assign['modificationLog'] = $this->module->renderGdprList($mlog_fields['list'] + array('fields_list' => $mlog_fields['fields']));
                    $assign['tpl'] = 'modification-log';
                }
            } elseif ($currentTabs == 'login_log') {
                if (Tools::isSubmit('download')) {
                    $this->_errors = $this->module->exportCSV(array(
                        'id_customer' => $this->context->customer->id,
                        'name' => 'llog_fields',
                        'frontend' => true,
                    ));
                } else {
                    $llog_fields = Gdpr_defines::getInstance()->getLoginLogFields();
                    $llog_fields['list'] = array_merge($llog_fields['list'], array(
                        'name' => 'gdpr_login',
                        'curTab' => 'login_log',
                        'limit' => 2,
                        'identifier' => 'id_ets_gdpr_login',
                    ));
                    $this->displayFields($llog_fields['fields']);
                    $assign['login_log'] = $this->module->renderGdprList($llog_fields['list'] + array('fields_list' => $llog_fields['fields']));
                    $assign['tpl'] = 'login-log';
                }
            } elseif ($currentTabs == 'del_data') {
                if (($ID = Gdpr_deletion::checkRequested($this->context->customer->id))) {
                    $assign['DATA_REQUEST'] = true;
                    $assign['ID'] = $ID;
                    if (Configuration::get('ETS_GDPR_WITHDRAW_REQUEST') && Tools::isSubmit('withdraw')) {
                        $deletion = new Gdpr_deletion($ID);
                        $deletion->status = Gdpr_defines::WITHDRAW;
                        $deletion->action_taken_date_time = date('Y-m-d H:i:s');
                        if (!$deletion->update())
                            $this->_errors = Ets_gdpr::$trans['required_withdraw_error'];
                        if (!$this->_errors) {
                            $params['msg'] = 2;
                            Tools::redirectLink($this->context->link->getModuleLink($this->module->name, 'gdpr', $params), (Tools::usingSecureMode() ? true : false));
                        }
                    }
                } elseif (Tools::isSubmit('submitDelData') && Tools::getValue('dataType', false)) {
                    $this->module->processDeletion($this->_errors);
                    if (!count($this->_errors)) {
                        $params['msg'] = 1;
                        Tools::redirectLink($this->context->link->getModuleLink($this->module->name, 'gdpr', $params), (Tools::usingSecureMode() ? true : false));
                    }
                } elseif (($msg = Tools::getValue('msg', false)) && (($msg == 1 && !Configuration::get('ETS_GDPR_REQUIRE_APPROVAL')) || ($msg == 2 && Configuration::get('ETS_GDPR_WITHDRAW_REQUEST')))) {
                    $assign['msg_success'] = $msg != 1 ? Ets_gdpr::$trans['required_withdraw'] : Ets_gdpr::$trans['data_deleted'];
                }
                $dataDeletes = isset($customer_privileges['ETS_GDPR_CAN_DELETE']) && ($res = $customer_privileges['ETS_GDPR_CAN_DELETE']) ? $res : '';
                $DDs = $dataDeletes && $dataDeletes != 'ALL' ? explode(',', $dataDeletes) : ($dataDeletes ? 'ALL' : array());
                $assign = array_merge($assign, array(
                    'ETS_GDPR_WARNING_DELETION_PAGE' => Configuration::get('ETS_GDPR_WARNING_DELETION_PAGE', $this->context->language->id),
                    'ETS_GDPR_REQUIRE_APPROVAL' => Configuration::get('ETS_GDPR_REQUIRE_APPROVAL'),
                    'confirm' => Ets_gdpr::$trans['request_delete'],
                    'dataTypes' => Gdpr_defines::getInstance()->getDataTypes(),
                    'DDs' => $DDs,
                    'tpl' => 'delete-data',
                    'is17' => $this->module->is17,
                    '_errors' => count($this->_errors) > 0 ? $this->module->displayError($this->_errors) : false,
                    'ETS_GDPR_WITHDRAW_REQUEST' => Configuration::get('ETS_GDPR_WITHDRAW_REQUEST') ? 1 : 0,
                ));
            }
            if (!$this->module->is17) {
                $this->errors = $this->_errors;
            }
            $this->context->smarty->assign($assign);
            if ($this->template) {
                $this->setTemplate(($this->module->is17 ? 'module:' . $this->module->name . '/views/templates/front/' : '') . $this->template . ($this->module->is17 ? '' : '16') . '.tpl');
            }
        }
    }

    public function configParams($list)
    {
        $params = [];
        $define = Gdpr_defines::getInstance()->get($list . '_fields');
        if ($define) {
            $params = $define['list'];
            $params['id_customer'] = $this->context->customer->id;
            $params['fields_list'] = $define['fields'];
        }
        return $params;
    }

    public function permission($curTabs)
    {
        if (!$curTabs)
            return false;
        elseif ($curTabs == 'del_data') {
            if (Configuration::get('ETS_GDPR_ALLOW_DELETE') && Configuration::get('ETS_GDPR_CAN_DELETE') != '')
                return true;
            return false;
        } elseif (!Configuration::get('ETS_GDPR_ALLOW_VIEW'))
            return false;
        elseif (Configuration::get('ETS_GDPR_DATA_TO_VIEW') == 'ALL')
            return true;
        elseif (($dataTypes = explode(',', Configuration::get('ETS_GDPR_DATA_TO_VIEW')))) {
            switch ($curTabs) {
                case 'mod_log':
                    if (in_array('MOD', $dataTypes))
                        return true;
                    break;
                case 'login_log':
                    if (in_array('LOG', $dataTypes) && Configuration::get('ETS_GDPR_ENABLE_LOGIN_LOG'))
                        return true;
                    break;
                case 'personal':
                    $ik = 0;
                    $exitFor = 2;
                    foreach ($dataTypes as $dataType) {
                        if ($exitFor <= 0)
                            break;
                        if ($dataType == 'MOD' || $dataType == 'LOG') {
                            unset($dataTypes[$ik]);
                            $exitFor--;
                        }
                        $ik++;
                    }
                    if (!empty($dataTypes))
                        return true;
                    break;
            }
            return false;
        }
        return false;
    }

    public function displayFields(&$fields_list)
    {
        if ($fields_list) {
            foreach ($fields_list as $key => $field) {
                if (isset($field['frontend']) && !$field['frontend'])
                    unset($fields_list[$key]);
            }
        }
    }
}
