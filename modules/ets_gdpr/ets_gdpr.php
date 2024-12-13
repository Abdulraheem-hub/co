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
require_once(dirname(__FILE__) . '/classes/Gdpr_tools.php');
require_once(dirname(__FILE__) . '/classes/Gdpr_translate.php');
require_once(dirname(__FILE__) . '/classes/Gdpr_presenter.php');
require_once(dirname(__FILE__) . '/classes/Gdpr_pagination.php');
require_once(dirname(__FILE__) . '/classes/Gdpr_acceptance.php');
require_once(dirname(__FILE__) . '/classes/Gdpr_deletion.php');
require_once(dirname(__FILE__) . '/classes/Gdpr_login.php');
require_once(dirname(__FILE__) . '/classes/Gdpr_modification.php');
require_once(dirname(__FILE__) . '/classes/Gdpr_notice.php');
require_once(dirname(__FILE__) . '/classes/pdf/Gdpr_PDF.php');
require_once(dirname(__FILE__) . '/classes/Gdpr_browser.php');
require_once(dirname(__FILE__) . '/classes/Gdpr_log.php');
require_once(dirname(__FILE__) . '/classes/Gdpr_defines.php');
if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    require_once(dirname(__FILE__) . '/classes/password.php');
}

class Ets_gdpr extends Module
{
    public static $trans = array();
    public $currentObj = null;
    public $baseAdminPath = null;
    public $is17 = false;
    public $errorMessage = null;
    public $_html = null;
    public $list_id = null;
    public $_filter;
    public $_filterHaving;
    public $_helperlist;
    public $toolbar_btn;
    public $orderUpdated = false;

    /**
     * Ets_gdpr constructor.
     */
    public function __construct()
    {
        $this->name = 'ets_gdpr';
        $this->tab = 'front_office_features';
        $this->version = '1.1.1';
        $this->author = 'PrestaHero';
        $this->need_instance = 0;
        $this->secure_key = Tools::encrypt($this->name);
        $this->bootstrap = true;
        $this->module_key = 'b99338ac1167fc36d4ab9a37b62a4586';
        parent::__construct();
        $this->displayName = $this->l('GDPR');
        $this->description = $this->l('Make your website compliant with EU GDPR (General Data Protection Regular)');
$this->refs = 'https://prestahero.com/';
        $this->ps_versions_compliancy = array('min' => '1.6.0.0', 'max' => _PS_VERSION_);
        $this->translates();
        $this->list_id = Gdpr_notice::$definition['table'];
        if (version_compare(_PS_VERSION_, '1.7', '>='))
            $this->is17 = true;
        if (Tools::getValue('action', false) == 'updatePositions')
            $this->updatePositions();
    }

    public static function instance()
    {
        return self::getInstanceByName(Gdpr_defines::name);
    }

    public static function setOrderCurrency($echo, $tr)
    {
        $order = new Order($tr['id_order']);
        return Tools::displayPrice($echo, (int)$order->id_currency);
    }

    public function displayViewCustomerLink($token = null, $id)
    {
        $deletion = new Gdpr_deletion($id);
        $assign = array(
            'value' => $token,
            'tr' => $deletion,
            'type' => 'viewcustomer',
        );
        $customer = new Customer($deletion->id_customer);
        if (!$customer->id || $customer->deleted)
            $assign += array('badge' => 'badge-danger');
        return $this->callBack($assign);
    }

    public function displayPendingLink($token = null, $id)
    {
        $deletion = new Gdpr_deletion($id);
        $this->smarty->assign(array(
            'actions' => array(
                array(
                    'href' => $this->_helperlist->currentIndex . '&previewdata&' . $this->_helperlist->identifier . '=' . $id . '&token=' . $token,
                    'action' => $this->l('Preview data before deletion'),
                    'icon' => 'eye',
                ),
                array(
                    'href' => $this->_helperlist->currentIndex . '&downloadpdf&' . $this->_helperlist->identifier . '=' . $id . '&token=' . $token,
                    'action' => $this->l('Download PDF'),
                    'icon' => 'file-text',
                ),
                array(
                    'href' => $this->_helperlist->currentIndex . '&approve_request&status=app&' . $this->_helperlist->identifier . '=' . $id . '&token=' . $token,
                    'action' => $this->l('Approve'),
                    'icon' => 'check',
                    'class' => 'gdpr-approve',
                ),
                array(
                    'href' => $this->_helperlist->currentIndex . '&declined_request&status=dec&' . $this->_helperlist->identifier . '=' . $id . '&token=' . $token,
                    'action' => $this->l('Decline'),
                    'icon' => 'remove',
                    'class' => 'gdpr-declined',
                ),
                array(
                    'href' => $this->_helperlist->currentIndex . '&sendemail&' . $this->_helperlist->identifier . '=' . $id . '&token=' . $token,
                    'action' => $this->l('Send an email to customer'),
                    'icon' => 'envelope',
                ),
            ),
            'id_customer' => $deletion->id_customer,
        ));
        return $this->display(__FILE__, 'list_action_deletion.tpl');
    }

    public function displayCustomerField($value, $tr)
    {
        $gdpr = Ets_gdpr::instance();
        return $gdpr->callBack(array(
            'value' => $value,
            'tr' => $tr,
            'type' => 'link_customer'
        ));
    }

    public function displayLocationField($value, $tr)
    {
        return $this->callBack(array(
            'value' => $value,
            'tr' => $tr,
            'type' => 'location'
        ));
    }

    public function displayFieldDataType($value, $tr)
    {
        return $this->callBack(array(
            'value' => $value,
            'tr' => $tr,
            'type' => 'html'
        ));
    }

    public function callBack($params)
    {
        if (empty($params) || empty($params['type']))
            return null;
        if ($params['type'] == 'viewcustomer' || $params['type'] == 'link_customer') {
            $id = isset($params['tr']->id_customer) ? $params['tr']->id_customer : (isset($params['tr']['id_customer']) ? $params['tr']['id_customer'] : 0);
            $this->smarty->assign(array(
                'link' => $id ? $this->context->link->getAdminLink('AdminCustomers', true) . '&viewcustomer&id_customer=' . (int)$id : null,
            ));
        } elseif ($params['type'] == 'img') {
            $this->smarty->assign('img_base_dir', $this->_path);
        }
        $this->smarty->assign($params);
        return $this->display(__FILE__, 'callback.tpl');
    }

    public function updatePositions()
    {
        $way = (int)(Tools::getValue('way'));
        $itemId = (int)(Tools::getValue('id'));
        $positions = Tools::getValue('ets_gdpr_notice');
        $page = (int)Tools::getValue('page');
        $selected_pagination = (int)Tools::getValue('selected_pagination');
        foreach ($positions as $position => $value) {
            $pos = explode('_', $value);
            if (isset($pos[2]) && (int)$pos[2] === $itemId) {
                if ($page > 1) {
                    $position = $position + (($page - 1) * $selected_pagination);
                }
                if ($notice = new Gdpr_notice((int)$itemId)) {
                    if (isset($position) && $notice->updatePosition($way, $position)) {
                        die('ok position ' . (int)$position . ' for notice ' . (int)$pos[2] . '\r\n');
                    }
                }
                break;
            }
        }
    }

    public function displayError($errors)
    {
        if (version_compare(_PS_VERSION_, '1.6', '<')) {
            $this->error = true;
            $this->smarty->assign('errors', $errors);
            return $this->display(__FILE__, 'errors.tpl');
        } else {
            return parent::displayError($errors);
        }
    }

    public function displaySmarty($type, $label = null, $value = null)
    {
        if (!$type)
            return false;
        $assign = array(
            'type' => $type,
            'label' => $label
        );
        if ($value)
            $assign['value'] = $value;
        $this->smarty->assign($assign);
        return $this->display(__FILE__, 'admin-smarty.tpl');
    }

    public function translates()
    {
        self::$trans = array(
            'required_text' => $this->l('is required'),
            'data_saved' => $this->l('Saved'),
            'unknown_error' => $this->l('Unknown error happens'),
            'object_empty' => $this->l('Object is empty'),
            'field_not_valid' => $this->l('Field is not valid'),
            'file_too_large' => $this->l('Upload file size cannot be large than 100MB'),
            'file_existed' => $this->l('File name already exists. Try to rename the file and upload again'),
            'can_not_upload' => $this->l('Cannot upload file'),
            'upload_error_occurred' => $this->l('An error occurred during the image upload process.'),
            'image_deleted' => $this->l('Image deleted'),
            'item_deleted' => $this->l('Item deleted'),
            'cannot_delete' => $this->l('Cannot delete the item due to an unknown technical problem'),
            'invalid_text' => $this->l('is invalid'),
            'content_required_text' => $this->l('Text content is required'),
            'link_required_text' => $this->l('Link is required'),
            'image_required_text' => $this->l('Image is required'),
            'request_deletion' => $this->l('Deletion request was sent successfully! Please wait for approval from admin'),
            'account_data' => $this->l('Entire account data'),
            'general_info' => $this->l('General information'),
            'request_delete' => $this->l('Do you want to delete the selected data?'),
            'data_deleted' => $this->l('The data has been deleted successfully'),
            'required_withdraw' => $this->l('Deletion request was withdrawn successfully'),
            'required_withdraw_error' => $this->l('There were unknown errors occurred while withdrawing deletion request. Please try again later.'),
        );
        if (isset($this->context->controller->_conf) && $this->context->controller->_conf) {
            $this->context->controller->_conf += array(
                101 => $this->l('Email has been sent successfully'),
                102 => $this->l('The deletion request has been approved'),
                103 => $this->l('The deletion request has been declined'),
                104 => $this->l('The deletion requests have been approved'),
                105 => $this->l('The deletion requests have been declined'),
                106 => $this->l('The deletion request has been deleted'),
                107 => $this->l('The deletion requests have been deleted'),
                108 => $this->l('The selected item has been deleted'),
                109 => $this->l('The selected items have been deleted'),
            );
        }
    }

    public function _installMailTemplate()
    {
        $languages = Language::getLanguages(false);
        if ($languages && is_array($languages)) {
            $temp_dir_ltr = dirname(__FILE__) . '/mails/en';
            $temp_dir_rtl = dirname(__FILE__) . '/mails/he';
            if (!@file_exists($temp_dir_ltr) || !@file_exists($temp_dir_rtl))
                return true;
            foreach ($languages as $language) {
                if (isset($language['iso_code']) && ($language['iso_code'] != 'en' || $language['iso_code'] != 'he')) {
                    if (($new_dir = dirname(__FILE__) . '/mails/' . $language['iso_code'])) {
                        $this->recurseCopy(($language['is_rtl'] ? $temp_dir_rtl : $temp_dir_ltr), $new_dir);
                    }
                }
            }
        }
        return true;
    }

    public function recurseCopy($src, $dst)
    {
        if (!@file_exists($src))
            return false;
        $dir = opendir($src);
        if (!@is_dir($dst))
            @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->recurseCopy($src . '/' . $file, $dst . '/' . $file);
                } elseif (!@file_exists($dst . '/' . $file)) {
                    @copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    public function _registerHook()
    {
        $res = $this->registerHook('displayBackOfficeHeader')
            && $this->registerHook('displayGdprHelp')
            && $this->registerHook('displayHeader')
            && $this->registerHook('displayFooter')
            && $this->registerHook('displayCustomerAccountForm')
            && $this->registerHook('displayCustomerAccount')
            && $this->registerHook('displayGdprMenu')
            && $this->registerHook('displayLeftColumn')
            && $this->registerHook('displayGpdrBlock')
            && $this->registerHook('actionAuthentication')
            && $this->registerHook('actionCustomerAccountAdd')
            && $this->registerHook('actionObjectUpdateAfter')
            && $this->registerHook('displayTop')
            && $this->registerHook('actionObjectUpdateBefore')
            && $this->registerHook($this->is17 ? 'displayNav1' : 'displayNav')
            && $this->registerHook($this->is17 ? 'displayProductAdditionalInfo' : 'actionProductOutOfStock');
        if ($this->is17) {
            $res &= $this->registerHook('displayFooterBefore')
                && $this->registerHook('displayCustomerLoginFormAfter')
                && $this->registerHook('displayReassurance');
        }
        return $res;
    }

    public function installTabConfig($TAB, $languages, $upgrade = false)
    {
        if (!$languages)
            $languages = Language::getLanguages(false);
        if ($TAB) {
            $result = isset($TAB['is_conf']) && $TAB['is_conf'] && ($groupTab = $TAB['name'] ? $TAB['name'] : 'deletion_requests') ? Gdpr_defines::getInstance()->get($groupTab) : array();
            if (isset($result['configs']) && ($configs = $result['configs'])) {
                foreach ($configs as $key => $config) {
                    if (isset($config['lang']) && $config['lang']) {
                        $values = array();
                        foreach ($languages as $lang) {
                            $values[$lang['id_lang']] = isset($config['default']) ? $config['default'] : '';
                        }
                        if ($upgrade && !Configuration::hasKey($key) || !$upgrade) {
                            Configuration::updateValue($key, $values, true);
                        }
                    } elseif ($upgrade && !Configuration::hasKey($key) || !$upgrade) {
                        Configuration::updateValue($key, isset($config['default']) ? $config['default'] : '', true);
                    }
                }
            }
        }
    }

    public function _installConfigs($upgrade = false)
    {
        $languages = Language::getLanguages(false);
        $configTabs = Gdpr_defines::getInstance()->getConfigTabs();
        if ($configTabs) {
            foreach ($configTabs as $configTab) {
                if (isset($configTab['subTabs']) && ($subTabs = $configTab['subTabs'])) {
                    foreach ($subTabs as $TAB)
                        $this->installTabConfig($TAB, $languages, $upgrade);
                } else
                    $this->installTabConfig($configTab, $languages, $upgrade);
            }
        }
        if ($upgrade && !Configuration::hasKey('ETS_GDPR_INSTALL_DATE') || !$upgrade) {
            Configuration::updateValue('ETS_GDPR_INSTALL_DATE', date('Y-m-d H:i:s'));
        }
        return true;
    }

    private function _installNotices()
    {
        $shops = Shop::getShops(false);
        $languages = Language::getLanguages(false);
        $titles = array('What are cookies?', 'How we use cookies', 'Controlling Cookies', 'Information We Collect');
        $result = true;
        if ($titles && $languages && $shops) {
            $notice = new Gdpr_notice();
            $notice->display_to = 'ALL';
            $notice->enabled = 1;
            for ($ik = 1; $ik <= 4; $ik++) {
                $titleVal = array();
                $descVal = array();
                foreach ($languages as $lang) {
                    $titleVal[$lang['id_lang']] = $titles[$ik - 1];
                    $descVal[$lang['id_lang']] = $this->displaySmarty('NOTICE_ITEMS', $ik);
                }
                $notice->title = $titleVal;
                $notice->description = $descVal;
                foreach ($shops as $shop) {
                    $notice->id_shop = (int)$shop['id_shop'];
                    $notice->position = (int)$notice->maxVal((int)$shop['id_shop']) + 1;
                    $result &= $notice->validateFields(false) && $notice->add();
                }
            }
        }
        return $result;
    }

    public function install()
    {
        include(dirname(__FILE__) . '/sql/install.php');

        return parent::install()
            && $this->_registerHook()
            && $this->_installConfigs()
            && $this->_installMailTemplate()
            && $this->_installNotices();
    }

    public function uninstall()
    {
        include(dirname(__FILE__) . '/sql/uninstall.php');

        return parent::uninstall() && $this->_uninstallConfigs();
    }

    private function uninstallTabConfig($TAB)
    {
        if ($TAB) {
            $result = isset($TAB['is_conf']) && $TAB['is_conf'] && ($groupTab = $TAB['name'] ? $TAB['name'] : 'deletion_requests') ? Gdpr_defines::getInstance()->get($groupTab) : [];
            if (isset($result['configs']) && ($configs = $result['configs'])) {
                foreach ($configs as $key => $config)
                    Configuration::deleteByName($key);
                unset($config);
            }
        }
    }

    private function _uninstallConfigs()
    {
        $configTabs = Gdpr_defines::getInstance()->getConfigTabs();
        if ($configTabs) {
            foreach ($configTabs as $configTab) {
                if (isset($configTab['subTabs']) && ($subTabs = $configTab['subTabs'])) {
                    foreach ($subTabs as $TAB)
                        $this->uninstallTabConfig($TAB);
                    unset($TAB);
                } else
                    $this->uninstallTabConfig($configTab);
            }
        }
        Configuration::deleteByName('ETS_GDPR_INSTALL_DATE');
        return true;
    }

    public function getFields($obj, $key, $config, $id_lang = false)
    {
        if ($obj) {
            if (!$obj->id)
                return (isset($config['default']) ? $config['default'] : null);
            elseif ($id_lang)
                return !empty($obj->{$key}) && !empty($obj->{$key}[$id_lang]) ? $obj->{$key}[$id_lang] : '';
            else
                return $obj->$key;
        } else {
            if ($id_lang)
                return Configuration::get($key, $id_lang);
            else
                return Configuration::get($key);
        }
    }

    public function renderForm($params)
    {
        if (empty($params['config']))
            return false;
        $configForm = Gdpr_defines::getInstance()->get($params['config']);
        $fields_form = array();
        $fields_form['form'] = $configForm['form'];
        if (!empty($fields_form['form']['buttons'])) {
            $fields_form['form']['buttons']['back']['href'] = AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . (($pTAB = Tools::getValue('pTAB', false)) ? '&pTAB=' . $pTAB : '') . (($sTAB = Tools::getValue('sTAB', false)) ? '&sTAB=' . $sTAB : '');
        }
        $configs = $configForm['configs'];
        if ($configs) {
            foreach ($configs as $key => $config) {
                $confFields = array(
                    'name' => $key,
                    'type' => $config['type'],
                    'label' => $config['label'],
                    'desc' => isset($config['desc']) ? $config['desc'] : false,
                    'required' => isset($config['required']) && $config['required'] ? true : false,
                    'autoload_rte' => isset($config['autoload_rte']) && $config['autoload_rte'] ? true : false,
                    'options' => isset($config['options']) && $config['options'] ? $config['options'] : array(),
                    'multiple' => isset($config['multiple']) && $config['multiple'],
                    'values' => $config['type'] == 'switch' ? array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ) : (isset($config['values']) && $config['values'] ? $config['values'] : false),
                    'lang' => isset($config['lang']) ? $config['lang'] : false,
                    'col' => isset($config['col']) ? $config['col'] : '9',
                    'default' => isset($config['default']) ? $config['default'] : false,
                    'rel' => isset($config['rel']) && $config['rel'] ? 1 : 0,
                    'group' => isset($config['group']) ? $config['group'] : false,
                    'placeholder' => isset($config['placeholder']) && $config['placeholder'] ? $config['placeholder'] : false,
                );
                if (!empty($config['suffix']))
                    $confFields = $confFields + array('suffix' => $config['suffix']);
                if (!empty($config['cols']))
                    $confFields = $confFields + array('cols' => $config['cols']);
                if (!empty($config['rows']))
                    $confFields = $confFields + array('rows' => $config['rows']);
                if (!empty($config['img_dir']))
                    $confFields = $confFields + array('img_dir' => $config['img_dir']);
                if (!empty($config['group']))
                    $confFields = $confFields + array('group' => $config['group']);
                if (!$confFields['multiple']) {
                    unset($confFields['multiple']);
                } elseif ($config['type'] == 'select' && stripos($confFields['name'], '[]') === false) {
                    $confFields['name'] .= '[]';
                }
                $fields_form['form']['input'][] = $confFields;
            }
        }
        $language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper = new HelperForm();
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->table = $this->table;
        $helper->default_form_language = $language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'save' . Tools::ucfirst($fields_form['form']['name']);
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name . $this->getUrlParams();
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->override_folder = '/';
        $helper->show_cancel_button = false;
        $fields = array();
        $languages = Language::getLanguages(false);
        if (Tools::isSubmit('save' . Tools::ucfirst(trim($configForm['form']['name'])))) {
            if ($configs) {
                foreach ($configs as $key => $config) {
                    if (isset($config['lang']) && $config['lang']) {
                        foreach ($languages as $l) {
                            $fields[$key][$l['id_lang']] = Tools::getValue($key . '_' . $l['id_lang'], isset($config['default']) ? $config['default'] : '');
                        }
                    } elseif ($config['type'] == 'select' && isset($config['multiple']) && $config['multiple']) {
                        $fields[$key . ($config['type'] == 'select' ? '[]' : '')] = Tools::getValue($key, array());
                    } elseif ($config['type'] == 'gdpr_group' || $config['type'] == 'gdpr_checkbox') {
                        if (Tools::getIsset($key)) {
                            $fields[$key] = ($getVal = Tools::getValue($key, array())) && ($result = implode(',', $getVal)) != 'ALL' ? $result : 'ALL';
                        } else {
                            $fields[$key] = null;
                        }
                    } else
                        $fields[$key] = Tools::getValue($key, isset($config['default']) ? $config['default'] : '');
                }
            }
        } else {
            if ($configs) {
                $obj = !empty($params['obj']) ? $params['obj'] : false;
                foreach ($configs as $key => $config) {
                    if (isset($config['lang']) && $config['lang']) {
                        foreach ($languages as $l) {
                            $fields[$key][$l['id_lang']] = $this->getFields($obj, $key, $config, (int)$l['id_lang']);
                        }
                    } elseif ($config['type'] == 'select' && isset($config['multiple']) && $config['multiple']) {
                        $fields[$key . ($config['type'] == 'select' ? '[]' : '')] = ($result = $this->getFields($obj, $key, $config)) != '' ? explode(',', $result) : array();
                    } elseif ($config['type'] == 'gdpr_group' || $config['type'] == 'gdpr_checkbox') {
                        $fields[$key] = ($result = $this->getFields($obj, $key, $config)) != '' ? ($result != 'ALL' ? explode(',', $result) : 'ALL') : array();
                    } else
                        $fields[$key] = $this->getFields($obj, $key, $config);
                }
            }
        }
        $helper->tpl_vars = array(
            'table' => isset($fields_form['form']['name']) && $fields_form['form']['name'] ? $fields_form['form']['name'] : null,
            'base_url' => $this->context->shop->getBaseURL(),
            'language' => array(
                'id_lang' => $language->id,
                'iso_code' => $language->iso_code
            ),
            'fields_value' => $fields,
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'is15' => version_compare(_PS_VERSION_, '1.6', '<=') ? true : false,
            'modLink' => $this->baseAdminUrl(),
        );
        $this->_html .= (!empty($params['html']) ? $params['html'] : '') . $helper->generateForm(array($fields_form));
    }

    public function getUrlParams()
    {
        $params = '';
        if ($pTAB = Tools::getValue('pTAB', false))
            $params .= '&pTAB=' . $pTAB;
        if ($sTAB = Tools::getValue('sTAB', false))
            $params .= '&sTAB=' . $sTAB;
        if (Tools::isSubmit('add' . $this->list_id))
            $params .= '&add' . $this->list_id;
        elseif (Tools::isSubmit('update' . $this->list_id))
            $params .= '&update' . $this->list_id;
        if ($itemId = Tools::getValue('id_' . $this->list_id, false))
            $params .= '&id_' . $this->list_id . '=' . $itemId;
        return $params;
    }

    public function renderHelp()
    {
        $this->smarty->assign(array(
            '_path' => $this->_path,
        ));
        $this->_html .= $this->display(__FILE__, 'admin-help.tpl');
    }

    public function displayDataType($params)
    {
        if (!(isset($params['dataTypes'])) || !$params['dataTypes'])
            return false;
        if ($params['dataTypes'] == 'GEN')
            return $this->l('General information');
        if (isset($params['export']) && $params['export']) {
            $export = '';
            $dataModified = $params['dataTypes'] != 'ALL' ? explode(',', $params['dataTypes']) : 'ALL';
            foreach (Gdpr_defines::getInstance()->getDataTypes() as $key => $dataType) {
                if (($dataModified != 'ALL' && in_array($key, $dataModified)) || $dataModified == 'ALL') {
                    $export .= $dataType['name'] . ", ";
                }
            }
            return rtrim($export, ', ');
        }
        $this->smarty->assign(array(
            'data_to_delete' => $params['dataTypes'],
            'dataTypes' => Gdpr_defines::getInstance()->getDataTypes(),
            'isLog' => isset($params['isLog']) && $params['isLog'] ? true : false,
        ));
        return $this->display(__FILE__, 'dataType.tpl');
    }

    public function processResetFilters($params)
    {
        if (empty($params['list_id']))
            return false;
        $prefix = null;
        $filters = $this->context->cookie->getFamily($prefix . $params['list_id'] . 'Filter_');
        if (!empty($filters))
            foreach ($filters as $cookie_key => $filter) {
                if (strncmp($cookie_key, $prefix . $params['list_id'] . 'Filter_', 7 + Tools::strlen($prefix . $params['list_id'])) == 0) {
                    $key = Tools::substr($cookie_key, 7 + Tools::strlen($prefix . $params['list_id']));
                    if (is_array($params['fields']) && array_key_exists($key, $params['fields'])) {
                        $this->context->cookie->$cookie_key = null;
                    }
                    unset($this->context->cookie->$cookie_key, $filter);
                }
            }
        if (isset($this->context->cookie->{'submitFilter' . $params['list_id']})) {
            unset($this->context->cookie->{'submitFilter' . $params['list_id']});
        }
        if (isset($this->context->cookie->{$prefix . $params['list_id'] . 'Orderby'})) {
            unset($this->context->cookie->{$prefix . $params['list_id'] . 'Orderby'});
        }
        if (isset($this->context->cookie->{$prefix . $params['list_id'] . 'Orderway'})) {
            unset($this->context->cookie->{$prefix . $params['list_id'] . 'Orderway'});
        }
        $_POST = array();
    }

    public function processFilter($params)
    {
        if (empty($params) || empty($params['list_id']))
            return false;
        $prefix = null;
        // Filter memorization
        if (!empty($_POST) && isset($params['list_id'])) {
            foreach ($_POST as $key => $value) {
                if ($value === '') {
                    unset($this->context->cookie->{$prefix . $key});
                } elseif (stripos($key, $params['list_id'] . 'Filter_') === 0) {
                    $this->context->cookie->{$prefix . $key} = !is_array($value) ? $value : json_encode($value);
                } elseif (stripos($key, 'submitFilter') === 0) {
                    $this->context->cookie->$key = !is_array($value) ? $value : json_encode($value);
                }
            }
        }
        if (!empty($_GET) && isset($params['list_id'])) {
            foreach ($_GET as $key => $value) {
                if (stripos($key, $params['list_id'] . 'Filter_') === 0) {
                    $this->context->cookie->{$prefix . $key} = !is_array($value) ? $value : json_encode($value);
                } elseif (stripos($key, 'submitFilter') === 0) {
                    $this->context->cookie->$key = !is_array($value) ? $value : json_encode($value);
                }
                if (stripos($key, $params['list_id'] . 'Orderby') === 0 && Validate::isOrderBy($value)) {
                    if ($value === '' || $value == $params['orderBy']) {
                        unset($this->context->cookie->{$prefix . $key});
                    } else {
                        $this->context->cookie->{$prefix . $key} = $value;
                    }
                } elseif (stripos($key, $params['list_id'] . 'Orderway') === 0 && Validate::isOrderWay($value)) {
                    if ($value === '' || $value == $params['orderWay']) {
                        unset($this->context->cookie->{$prefix . $key});
                    } else {
                        $this->context->cookie->{$prefix . $key} = $value;
                    }
                }
            }
        }
        $filters = $this->context->cookie->getFamily($prefix . $params['list_id'] . 'Filter_');
        foreach ($filters as $key => $value) {
            /* Extracting filters from $_POST on key filter_ */
            if ($value != null && !strncmp($key, $prefix . $params['list_id'] . 'Filter_', 7 + Tools::strlen($prefix . $params['list_id']))) {
                $key = Tools::substr($key, 7 + Tools::strlen($prefix . $params['list_id']));
                /* Table alias could be specified using a ! eg. alias!field */
                $tmp_tab = explode('!', $key);
                $filter = count($tmp_tab) > 1 ? $tmp_tab[1] : $tmp_tab[0];
                if ($field = $this->filterToField($key, $filter, $params['fields_list'])) {
                    $type = (array_key_exists('filter_type', $field) ? $field['filter_type'] : (array_key_exists('type', $field) ? $field['type'] : false));
                    if (($type == 'date' || $type == 'datetime') && is_string($value))
                        $value = Tools::json_decode($value);
                    $key = isset($tmp_tab[1]) ? $tmp_tab[0] . '.`' . $tmp_tab[1] . '`' : '`' . $tmp_tab[0] . '`';
                    $sql_filter = '';
                    /* Only for date filtering (from, to) */
                    if (is_array($value)) {
                        if (isset($value[0]) && !empty($value[0])) {
                            if (!Validate::isDate($value[0])) {
                                $this->errors[] = Tools::displayError('The \'From\' date format is invalid (YYYY-MM-DD)');
                            } else {
                                $sql_filter .= ' AND ' . pSQL($key) . ' >= \'' . pSQL(Tools::dateFrom($value[0])) . '\'';
                            }
                        }
                        if (isset($value[1]) && !empty($value[1])) {
                            if (!Validate::isDate($value[1])) {
                                $this->errors[] = Tools::displayError('The \'To\' date format is invalid (YYYY-MM-DD)');
                            } else {
                                $sql_filter .= ' AND ' . pSQL($key) . ' <= \'' . pSQL(Tools::dateTo($value[1])) . '\'';
                            }
                        }
                    } else {
                        $sql_filter .= ' AND ';
                        $check_key = ($key == 'id_' . $params['list_id'] || $key == '`id_' . $params['list_id'] . '`');
                        $alias = $params['alias'];
                        if ($type == 'int' || $type == 'bool') {
                            $sql_filter .= (($check_key || $key == '`active`') ? $alias . '.' : '') . pSQL($key) . ' = ' . (int)($key == '`position`' ? $value - 1 : $value) . ' ';
                        } elseif ($type == 'decimal') {
                            $sql_filter .= ($check_key ? $alias . '.' : '') . pSQL($key) . ' = ' . (float)$value . ' ';
                        } elseif ($type == 'select') {
                            $sql_filter .= ($check_key ? $alias . '.' : '') . pSQL($key) . ' = \'' . pSQL($value) . '\' ';
                        } elseif ($type == 'price') {
                            $value = (float)str_replace(',', '.', $value);
                            $sql_filter .= ($check_key ? $alias . '.' : '') . pSQL($key) . ' = ' . pSQL(trim($value)) . ' ';
                        } else {
                            $sql_filter .= ($check_key ? $alias . '.' : '') . pSQL($key) . ' LIKE \'%' . pSQL(trim($value)) . '%\' ';
                        }
                    }
                    if (isset($field['havingFilter']) && $field['havingFilter'])
                        $this->_filterHaving .= $sql_filter;
                    else
                        $this->_filter .= $sql_filter;
                }
            }
        }
    }

    protected function filterToField($key, $filter, $fields_list)
    {
        if (empty($fields_list))
            return false;
        foreach ($fields_list as $field)
            if (array_key_exists('filter_key', $field) && $field['filter_key'] == $key)
                return $field;
        if (array_key_exists($filter, $fields_list))
            return $fields_list[$filter];
        return false;
    }

    public function initToolbar($params)
    {
        if (!empty($params) && isset($params['list_id']) && $params['list_id'] == $this->list_id) {
            $this->toolbar_btn['new'] = array(
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . (isset($params['pTAB']) && $params['pTAB'] ? '&pTAB=' . $params['pTAB'] : '') . (isset($params['sTAB']) && $params['sTAB'] ? '&sTAB=' . $params['sTAB'] : '') . '&add' . $params['list_id'] . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Add') . ' ' . Tools::strtolower($params['title']),
            );
        }
    }

    public function renderList($params)
    {
        if (!$params)
            return $this->_html;
        $fields_list = isset($params['fields_list']) && $params['fields_list'] ? $params['fields_list'] : false;
        if (!$fields_list)
            return false;
        $this->initToolbar($params);
        $helper = new HelperList();
        $helper->title = isset($params['title']) && $params['title'] ? $params['title'] : '';
        $helper->table = isset($params['list_id']) && $params['list_id'] ? $params['list_id'] : $this->list_id;
        $helper->identifier = 'id_' . $helper->table;
        if (version_compare(_PS_VERSION_, '1.6.1', '>=')) {
            $helper->_pagination = array(20, 50, 100, 300, 1000);
            $helper->_default_pagination = 20;
        }
        $helper->_defaultOrderBy = $params['orderBy'];
        if ($params['orderBy'] == 'position') {
            $helper->position_identifier = 'position';
        }
        $this->processFilter($params);
        //Sort order
        $order_by = urldecode(Tools::getValue($helper->table . 'Orderby'));
        if (!$order_by) {
            if ($this->context->cookie->{$helper->table . 'Orderby'}) {
                $order_by = $this->context->cookie->{$helper->table . 'Orderby'};
            } elseif ($helper->orderBy) {
                $order_by = $helper->orderBy;
            } else {
                $order_by = $helper->_defaultOrderBy;
            }
        }
        $order_way = urldecode(Tools::getValue($helper->table . 'Orderway'));
        if (!$order_way) {
            if ($this->context->cookie->{$helper->table . 'Orderway'}) {
                $order_way = $this->context->cookie->{$helper->table . 'Orderway'};
            } elseif ($helper->orderWay) {
                $order_way = $helper->orderWay;
            } else {
                $order_way = $params['orderWay'];
            }
        }
        if (isset($fields_list[$order_by]) && isset($fields_list[$order_by]['filter_key'])) {
            $order_by = $fields_list[$order_by]['filter_key'];
        }
        //Pagination.
        $limit = Tools::getValue($helper->table . '_pagination');
        if (!$limit) {
            if (isset($this->context->cookie->{$helper->table . '_pagination'}) && $this->context->cookie->{$helper->table . '_pagination'})
                $limit = $this->context->cookie->{$helper->table . '_pagination'};
            else
                $limit = (version_compare(_PS_VERSION_, '1.6.1', '>=') ? $helper->_default_pagination : 20);
        }
        if ($limit) {
            $this->context->cookie->{$helper->table . '_pagination'} = $limit;
        } else {
            unset($this->context->cookie->{$helper->table . '_pagination'});
        }
        $start = 0;
        if ((int)Tools::getValue('submitFilter' . $helper->table)) {
            $start = ((int)Tools::getValue('submitFilter' . $helper->table) - 1) * $limit;
        } elseif (empty($start) && isset($this->context->cookie->{$helper->table . '_start'}) && Tools::isSubmit('export' . $helper->table)) {
            $start = $this->context->cookie->{$helper->table . '_start'};
        }
        if ($start) {
            $this->context->cookie->{$helper->table . '_start'} = $start;
        } elseif (isset($this->context->cookie->{$helper->table . '_start'})) {
            unset($this->context->cookie->{$helper->table . '_start'});
        }
        if (!Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way)
            || !is_numeric($start) || !is_numeric($limit)) {
            $this->_errors = $this->l('get list params is not valid');
        }
        $helper->orderBy = $order_by;
        if (preg_match('/[.!]/', $order_by)) {
            $order_by_split = preg_split('/[.!]/', $order_by);
            $order_by = bqSQL($order_by_split[0]) . '.`' . bqSQL($order_by_split[1]) . '`';
        } elseif ($order_by) {
            $order_by = '`' . bqSQL($order_by) . '`';
        }
        $args = array(
            'filter' => $this->_filter,
            'having' => $this->_filterHaving,
        );
        if (isset($params['id_customer']) && $params['id_customer']) {
            $args += array('id_customer' => (int)$params['id_customer']);
        }
        if (isset($params['status'])) {
            $args['status'] = $params['status'];
        }
        $helper->listTotal = Gdpr_presenter::getInstance()->{$params['nb']}($args + array('nb' => $params['nb']));
        $args += array(
            'start' => $start,
            'limit' => $limit,
            'sort' => $params['alias'] . '.' . $order_by . ' ' . Tools::strtoupper($order_way),
        );
        $list = Gdpr_presenter::getInstance()->{$params['list']}($args);
        $helper->orderWay = Tools::strtoupper($order_way);
        $helper->toolbar_btn = !empty($this->toolbar_btn) ? $this->toolbar_btn : array();
        $helper->shopLinkType = '';
        $helper->row_hover = true;
        $helper->no_link = $params['no_link'];
        $helper->simple_header = false;
        $helper->actions = !(isset($params['id_customer'])) ? $params['actions'] : array();
        $this->_helperlist = $helper;
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name . (!empty($params['pTAB']) ? '&pTAB=' . $params['pTAB'] : '') . (!empty($params['sTAB']) ? '&sTAB=' . $params['sTAB'] : '');
        $helper->bulk_actions = $params['bulk_actions'] ? $params['bulk_actions'] : false;
        if (isset($params['id_customer']) && $params['id_customer']) {
            return $helper->generateList($list, $fields_list);
        }
        $this->_html .= (!empty($params['html']) ? $params['html'] : '') . $helper->generateList($list, $fields_list);
    }

    public function subTabs($pTAB, $TABs, $pLink, $sTAB)
    {
        $configTabs = Gdpr_defines::getInstance()->getConfigTabs();
        if ($TABs && $pTAB == 'deletion_requests') {
            $statusDeletion = Gdpr_defines::getInstance()->getStatusDeletion();
            foreach ($TABs as $key => &$tab) {
                if (isset($statusDeletion[Tools::strtoupper($key)]['id_option']) && ($status = $statusDeletion[Tools::strtoupper($key)]['id_option'])) {
                    $tab['nb'] = Gdpr_presenter::getInstance()->getDeletions(array('nb' => true, 'status' => $status));
                }
            }
        }
        $this->smarty->assign(array(
            'parentLink' => $pLink,
            'TABs' => $TABs,
            'sTAB' => $sTAB,
            'pTAB' => ($subTAB = Tools::substr($pTAB, 0, 3)) && isset($configTabs[$subTAB]) && ($parentTAB = $configTabs[$subTAB]) ? $parentTAB : false,
        ));
        return $this->display(__FILE__, 'subTab.tpl');
    }

    public function getFTABs($subTABs)
    {
        if ($subTABs) {
            foreach ($subTABs as $TAB) {
                if (isset($TAB['name']) && $TAB['name']) {
                    return $TAB['name'];
                }
            }
        }
    }

    public function getContent()
    {
        if (!$this->active)
            return '';
        $configTabs = Gdpr_defines::getInstance()->getConfigTabs();
        $pTAB = Tools::strtolower(trim(Tools::getValue('pTAB', 'deletion_requests')));
        $TAB = isset($configTabs[Tools::substr($pTAB, 0, 3)]) ? $configTabs[Tools::substr($pTAB, 0, 3)] : false;
        $params = array('pTAB' => $pTAB);
        if (isset($TAB['subTabs']) && ($TABs = $TAB['subTabs']) && ($sTAB = Tools::getValue('sTAB', $this->getFTABs($TABs)))) {
            $params['sTAB'] = $sTAB;
            $TAB = isset($TABs[Tools::substr($sTAB, 0, 3)]) && ($TAB = $TABs[Tools::substr($sTAB, 0, 3)]) ? $TAB : false;
        }
        $this->postProcess($pTAB, $TAB, $params, (isset($sTAB) ? $sTAB : false));
        if (isset($sTAB) && $sTAB) {
            $params['html'] = $this->subTabs($pTAB, (isset($TABs) && $TABs ? $TABs : false), $this->baseAdminUrl() . '&pTAB=' . $pTAB, $sTAB);
        }
        if (isset($sTAB) && $sTAB && Tools::isSubmit('previewdata') && ($ID = Tools::getValue('id_ets_gdpr_deletion', false))) {
            $this->renderView($ID, $params);
        } elseif (isset($sTAB) && $sTAB && Tools::isSubmit('detailsets_gdpr_modification') && ($ID = Tools::getValue('id_ets_gdpr_modification', false))) {
            $this->renderViewLog($ID, $params);
        } elseif ($TAB && isset($TAB['render']) && ($REN = $TAB['render'])) {
            $fields = isset($TAB['name']) && $TAB['name'] ? $TAB['name'] . '_fields' : false;
            $list = Gdpr_defines::getInstance()->get($fields);
            $listID = $list && isset($list['list']['list_id']) && ($res = $list['list']['list_id']) ? $res : false;
            if ($REN == 'chart' && $params) {
                $this->renderStats($params);
            } elseif ($REN == 'form' || ($REN != 'form' && $listID && (Tools::isSubmit('add' . $listID) || Tools::isSubmit('update' . $listID)))) {
                $params += array('config' => (isset($sTAB) ? $sTAB : $pTAB), 'list_id' => $listID);
                if ($REN != 'form' && isset($TAB['name']) && $TAB['name'] && ($clsOBJ = 'Gdpr_' . Tools::strtolower($TAB['name'])) && class_exists($clsOBJ)) {
                    $ID = Tools::getValue('id_' . $listID, null);
                    $params['obj'] = new $clsOBJ($ID);
                }
                $this->renderForm($params);
            } elseif ($REN != 'form' && isset($list['fields']) && ($hasFields = $list['fields']) && isset($list['list']) && ($hasList = $list['list'])) {
                $params += $hasList + array('fields_list' => $hasFields);
                if ($params)
                    $this->renderList($params);
                if (isset($sTAB) && $sTAB == 'pending')
                    $this->renderForm(array('list_id' => $listID, 'config' => 'sendEmail'));
            }
        }
        $this->smarty->assign(array(
            'base_admin_url' => $this->baseAdminUrl(),
            'tabActive' => ($pTAB != 'deletion_requests' ? $pTAB : ''),
            'html' => $this->_html,
            'base_dir' => $this->_path,
            'configTabs' => $configTabs,
        ));
        if (count($this->_errors))
            $this->context->controller->errors = $this->_errors;
        return $this->display(__FILE__, 'admin-form.tpl');
    }

    public function renderStats($params)
    {
        $this->postChart();
        $this->smarty->assign(array(
            'action' => AdminController::$currentIndex . '&configure=' . $this->name . (isset($params['pTAB']) && $params['pTAB'] ? '&pTAB=' . $params['pTAB'] : '') . (isset($params['sTAB']) && $params['sTAB'] ? '&sTAB=' . $params['sTAB'] : '') . '&token=' . Tools::getAdminTokenLite('AdminModules'),
        ));
        $this->_html .= (!empty($params['html']) ? $params['html'] : '') . $this->display(__FILE__, 'admin-stats.tpl');
    }

    public function postChart()
    {
        $years = $this->dateYears();
        $months = Tools::dateMonths();
        $selectedYear = (int)date('Y');
        $selectedMonth = (int)date('m');
        if (Tools::isSubmit('submitFilterChart')) {
            $selectedYear = Tools::getValue('years', 0) ? (int)Tools::getValue('years') : 0;
            $selectedMonth = Tools::getValue('months', 0) ? (int)Tools::getValue('months') : 0;
        }
        $val = array(
            'month' => $selectedMonth,
            'year' => $selectedYear,
            'context' => $this->context
        );
        $this->smarty->assign(array(
            'chart' => $this->getCharts(array_merge($val, array('chart' => 'line'))),
            'years' => $years,
            'sl_year' => $selectedYear,
            'months' => $months,
            'sl_month' => $selectedMonth,
        ));
    }

    public function getCharts($params)
    {
        if (!(isset($params['chart'])) || !$params['chart']) {
            return false;
        }
        $totalLogs = Gdpr_log::getNbLogs($params);
        $data = array();
        $typeCharts = Gdpr_defines::getInstance()->getTypeCharts();
        if ($typeCharts) {
            foreach ($typeCharts as $type) {
                $value = Gdpr_log::getNbLogs(array_merge($params, array('accepted' => $type['val'])));
                $data[] = $this->bindChart($params, $type['label'], ($params['chart'] != 'pie' ? $value : ($totalLogs > 0 ? $value * 100 / $totalLogs : 0)));
            }
        }
        if ($params['chart'] != 'pie')
            $data[] = $this->bindChart($params, $this->l('Total'), $totalLogs);
        if ($params['chart'] == 'pie' && $data) {
            $countValue = 0;
            foreach ($data as $item)
                if ($item['value'] <= 0)
                    $countValue++;
            if ($countValue == count($data))
                $data = array();
        }
        return $data;
    }

    public function bindChart($params, $label, $value)
    {
        if (isset($params['chart']) && $params['chart']) {
            $sKey = $params['chart'] != 'pie' ? 'key' : 'label';
            $valueKey = $params['chart'] != 'pie' ? 'values' : 'value';
            if ($params['chart'] != 'pie') {
                $each = array();
                $format = 'Y';
                if ($params['month'] && $params['year']) {
                    $days = function_exists('cal_days_in_month') ? call_user_func_array('cal_days_in_month', array(CAL_GREGORIAN, (int)$params['month'], (int)$params['year'])) : (int)date('t', mktime(0, 0, 0, (int)$params['month'], 1, (int)$params['year']));
                    for ($day = 1; $day <= $days; $day++)
                        $each[] = $day;
                    $format = 'd';
                } elseif ($params['year']) {
                    for ($month = 1; $month <= 12; $month++)
                        $each[] = $month;
                    $format = 'm';
                } else {
                    $each = $this->dateYears();
                    if (count($each) == 1) {
                        $tmp = array();
                        for ($year = $each[0] - 2; $year <= $each[0] + 2; $year++)
                            $tmp[] = $year;
                        $each = $tmp;
                    }
                }
                $values = array();
                foreach ($each as $index) {
                    $res = array($index, 0);
                    if (!empty($value)) {
                        foreach ($value as $data) {
                            if (date($format, strtotime($data['date_series'])) == $index) {
                                $res = array($index, (int)$data['total']);
                                break;
                            }
                        }
                    }
                    $values[] = $res;
                }
            }
            $series = array(
                $sKey => $label,
                $valueKey => isset($values) ? $values : $value,
            );
            if ($params['chart'] != 'pie' && $label != $this->l('Total'))
                $series = array_merge($series, array('disabled' => true));
            return $series;
        }
        return array();
    }

    public function dateYears()
    {
        $installDate = Configuration::get('ETS_GDPR_INSTALL_DATE');
        $tab = array();
        for ($i = date('Y'); $i >= date('Y', strtotime($installDate)); $i--) {
            $tab[] = $i;
        }
        return $tab;
    }

    public function adminUrl($pTAB, $sTAB = false, $conf = 4)
    {
        return AdminController::$currentIndex . '&configure=' . $this->name . '&conf=' . ($conf ? $conf : 4) . '&pTAB=' . $pTAB . ($sTAB ? '&sTAB=' . $sTAB : '') . '&token=' . Tools::getAdminTokenLite('AdminModules');
    }

    private function postProcess($pTAB, $TAB, $params, $sTAB = false)
    {
        if (Tools::isSubmit('saveSend')) {
            if (!Tools::getValue('id_customer', false))
                $this->_errors[] = $this->l('Customer does not exist.');
            if (!$this->_errors) {
                $customer = new Customer((int)Tools::getValue('id_customer'));
                if (!($subject = Tools::getValue('subject', false)))
                    $this->_errors[] = $this->l('Email subject is required.');
                elseif (!Validate::isMailSubject($subject))
                    $this->_errors[] = $this->l('Email subject is invalid');
                if (!($content = Tools::getValue('content', false)))
                    $this->_errors[] = $this->l('Email content is required.');
                if (!$this->_errors && !$this->sendMail($customer, $subject, $content))
                    $this->_errors[] = $this->l('There was unknown errors happen while sending email. Please check you email configuration then try again.');
            }
            die(json_encode(array(
                'errors' => $this->_errors ? $this->displayError($this->_errors) : false,
            )));
        } elseif (Tools::isSubmit('approve_request')) {
            if (!($Id = Tools::getValue('id_ets_gdpr_deletion', false)))
                $this->_errors[] = $this->l('Item ID does not exist.');
            if (!$this->_errors && ($gdpr_deletion = new Gdpr_deletion($Id)) && !$gdpr_deletion->id)
                $this->_errors[] = $this->l('The object cannot initialize.');
            if (!$this->_errors) {
                $dataType = $gdpr_deletion->data_to_delete != '' ? explode(',', $gdpr_deletion->data_to_delete) : '';
                $this->submitDeleteData($gdpr_deletion->id_customer, $dataType, $this->_errors, $gdpr_deletion->data_to_delete, Gdpr_defines::APPROVED);
            }
            if (Tools::getValue('ajax', false)) {
                die(json_encode(array(
                        'errors' => $this->_errors ? strip_tags($this->displayError($this->_errors)) : false,
                        'success' => !$this->_errors ? $this->l('The deletion request has been approved.') : false,
                    ) + $this->getNbDeletions()
                ));
            } elseif (!$this->_errors) {
                Tools::redirectAdmin($this->adminUrl($pTAB, $sTAB, 102));
            }
        } elseif (Tools::isSubmit('declined_request')) {
            if (!($Id = Tools::getValue('id_ets_gdpr_deletion', false)))
                $this->_errors[] = $this->l('Item ID does not exist.');
            if (!$this->_errors && ($gdpr_deletion = new Gdpr_deletion($Id)) && !$gdpr_deletion->id)
                $this->_errors[] = $this->l('The object cannot initialize.');
            if (!$this->_errors) {
                $customer = new Customer($gdpr_deletion->id_customer);
                $this->saveDeletion($customer, $this->_errors, $Id, $gdpr_deletion->data_to_delete, Gdpr_defines::DECLINED);
            }
            if (Tools::getValue('ajax', false)) {
                die(json_encode(array(
                        'errors' => $this->_errors ? strip_tags($this->displayError($this->_errors)) : false,
                        'success' => !$this->_errors ? $this->l('The deletion request has been declined.') : false,
                    ) + $this->getNbDeletions()
                ));
            } elseif (!$this->_errors) {
                Tools::redirectAdmin($this->adminUrl($pTAB, $sTAB, 103));
            }
        } elseif (isset($TAB) && isset($TAB['name']) && $TAB && ($REN = $TAB['render'])) {
            $fields = isset($TAB['name']) && $TAB['name'] ? $TAB['name'] . '_fields' : false;
            $list = Gdpr_defines::getInstance()->get($fields);
            $list_id = $list && isset($list['list']['list_id']) && ($res = $list['list']['list_id']) ? $res : false;
            if (($REN == 'form' || ($REN != 'form' && $list_id)) && Tools::isSubmit('save' . Tools::ucfirst($TAB['name']))) {
                if ($list_id)
                    $params['list_id'] = $list_id;
                if ($REN != 'form' && !empty($TAB['name']) && ($clsOBJ = 'Gdpr_' . Tools::strtolower($TAB['name'])) && class_exists($clsOBJ)) {
                    $ID = Tools::getValue('id_' . $list_id, null);
                    $params['obj'] = new $clsOBJ($ID);
                }
                $this->processSave($params);
            } elseif ($REN != 'form' && !empty($list['fields']) && Tools::isSubmit('submitReset' . $list_id)) {
                $this->processResetFilters(array('list_id' => $list_id, 'fields' => $list['fields']));
            } elseif ($REN != 'form' && !empty($list['fields']) && Tools::getValue('export', false)) {
                $sKey = Tools::strtoupper(Tools::substr(($sTAB ? $sTAB : 'pending'), 0, 3));
                $statusDeletion = Gdpr_defines::getInstance()->getStatusDeletion();
                if (isset($statusDeletion[$sKey]['id_option']) && ($status = $statusDeletion[$sKey]['id_option'])) {
                    $this->_errors += $this->exportCSV(array(
                        'name' => ($sTAB ? $sTAB : 'pending') . '_fields',
                        'status' => $status,
                    ));
                }
                return false;
            } elseif (Tools::getIsset('delete' . $list_id)) {
                $this->deleteObj();
            } elseif ($TAB['name'] == 'notice' && (Tools::isSubmit('status' . $list_id) || Tools::isSubmit('submitBulkenableSelection' . $list_id) || Tools::isSubmit('submitBulkdisableSelection' . $list_id))) {
                if (($selection = Tools::getValue($list_id . 'Box', array(Tools::getValue('id_' . $list_id)))) && !empty($selection)) {
                    $active = Tools::isSubmit('submitBulkenableSelection' . $list_id) ? 1 : (Tools::isSubmit('submitBulkdisableSelection' . $list_id) ? 0 : null);
                    if ($this->processStatus($selection, $active)) {
                        Tools::redirectAdmin($this->adminUrl($pTAB, $sTAB));
                    }
                    $this->_errors[] = $this->l('An error occurred change status this selection.');
                } else {
                    $this->_errors[] = $this->l('You must select at least one element to change status.');
                }
            } elseif (Tools::isSubmit('submitBulkdelete' . $list_id)) {
                if (($selection = Tools::getValue($list_id . 'Box')) && !empty($selection)) {
                    if ($this->deleteSelection($selection)) {
                        Gdpr_notice::cleanPositions();
                        Tools::redirectAdmin($this->adminUrl($pTAB, $sTAB, 2));
                    }
                    $this->_errors[] = $this->l('An error occurred while deleting this selection.');
                } else {
                    $this->_errors[] = $this->l('You must select at least one element to delete.');
                }
            } elseif (Tools::isSubmit('downloadpdf') && ($Id = Tools::getValue('id_ets_gdpr_deletion', false))) {
                $gdpr_deletion = new Gdpr_deletion($Id);
                $this->processGeneratePDF($gdpr_deletion->id_customer, $this->_errors);
            } elseif (Tools::isSubmit('submitBulkdeleteApprovedets_gdpr_deletion')) {
                if (($selection = Tools::getValue('ets_gdpr_deletionBox', false))) {
                    $conf = count($selection) > 1 ? 107 : 106;
                    if (Gdpr_deletion::deleteSelectedItem($selection, Gdpr_defines::APPROVED))
                        Tools::redirectAdmin();
                } else
                    $this->_errors[] = $this->l('Please select the item(s) that you want to delete.');
            } elseif (Tools::isSubmit('submitBulkdeleteDeclinedets_gdpr_deletion')) {
                if (($selection = Tools::getValue('ets_gdpr_deletionBox', false))) {
                    $conf = count($selection) > 1 ? 107 : 106;
                    if (Gdpr_deletion::deleteSelectedItem($selection, Gdpr_defines::DECLINED))
                        Tools::redirectAdmin($this->adminUrl($pTAB, $sTAB, $conf));
                } else
                    $this->_errors[] = $this->l('Please select the item(s) that you want to delete.');
            } elseif (Tools::isSubmit('submitBulkdeleteWithDrawets_gdpr_deletion')) {
                if (($selection = Tools::getValue('ets_gdpr_deletionBox', false))) {
                    $conf = count($selection) > 1 ? 190 : 108;
                    if (Gdpr_deletion::deleteSelectedItem($selection, Gdpr_defines::WITHDRAW))
                        Tools::redirectAdmin($this->adminUrl($pTAB, $sTAB, $conf));
                } else
                    $this->_errors[] = $this->l('Please select the item(s) that you want to delete.');
            } elseif (Tools::isSubmit('submitBulkapproveSelectionets_gdpr_deletion')) {
                if (($selection = Tools::getValue('ets_gdpr_deletionBox', false))) {
                    $ik = 0;
                    foreach ($selection as $Id) {
                        $gdpr_deletion = new Gdpr_deletion($Id);
                        $dataType = $gdpr_deletion->data_to_delete != '' ? explode(',', $gdpr_deletion->data_to_delete) : '';
                        if ($this->submitDeleteData($gdpr_deletion->id_customer, $dataType, $this->_errors, $Id, Gdpr_defines::APPROVED))
                            $ik++;
                    }
                    $conf = ($ik > 1 ? 104 : 102);
                    if ($conf)
                        Tools::redirectAdmin($this->adminUrl($pTAB, $sTAB, $conf));
                } else
                    $this->_errors[] = $this->l('Please select the item(s) that you want to approve.');
            } elseif (Tools::isSubmit('submitBulkdeclineSelectionets_gdpr_deletion')) {
                if (($selection = Tools::getValue('ets_gdpr_deletionBox', false))) {
                    $ik = 0;
                    foreach ($selection as $Id) {
                        $gdpr_deletion = new Gdpr_deletion($Id);
                        $customer = new Customer($gdpr_deletion->id_customer);
                        if ($this->saveDeletion($customer, $this->_errors, $Id, $gdpr_deletion->data_to_delete, Gdpr_defines::DECLINED))
                            $ik++;
                    }
                    $conf = ($ik > 1 ? 105 : 103);
                    if ($conf)
                        Tools::redirectAdmin($this->adminUrl($pTAB, $sTAB, $conf));
                } else
                    $this->_errors[] = $this->l('Please select the item(s) that you want to decline.');
            } elseif (Tools::isSubmit('submitBulkdeleteMLogets_gdpr_modification')) {
                if (($selection = Tools::getValue('ets_gdpr_modificationBox', false))) {
                    $conf = count($selection) > 1 ? 109 : 108;
                    if (Gdpr_modification::deleteSelectedItem($selection))
                        Tools::redirectAdmin($this->adminUrl($pTAB, $sTAB, $conf));
                } else
                    $this->_errors[] = $this->l('Please select the item(s) that you want to delete.');
            } elseif (Tools::isSubmit('submitBulkdeleteLLogets_gdpr_login')) {
                if (($selection = Tools::getValue('ets_gdpr_loginBox', false))) {
                    $conf = count($selection) > 1 ? 109 : 108;
                    if (Gdpr_login::deleteSelectedItem($selection))
                        Tools::redirectAdmin($this->adminUrl($pTAB, $sTAB, $conf));
                } else
                    $this->_errors[] = $this->l('Please select the item(s) that you want to delete.');
            } elseif (Tools::isSubmit('submitBulkdeleteLogets_gdpr_log')) {
                if (($selection = Tools::getValue('ets_gdpr_logBox', false))) {
                    $conf = count($selection) > 1 ? 109 : 108;
                    if (Gdpr_log::deleteSelectedItem($selection))
                        Tools::redirectAdmin($this->adminUrl($pTAB, $sTAB, $conf));
                } else
                    $this->_errors[] = $this->l('Please select the item(s) that you want to delete.');
            }
        }
    }

    public function getNbDeletions()
    {
        return array(
            'nb_pending' => Gdpr_presenter::getInstance()->getDeletions(array('nb' => true, 'status' => Gdpr_defines::PENDING)),
            'nb_approved' => Gdpr_presenter::getInstance()->getDeletions(array('nb' => true, 'status' => Gdpr_defines::APPROVED)),
            'nb_declined' => Gdpr_presenter::getInstance()->getDeletions(array('nb' => true, 'status' => Gdpr_defines::DECLINED)),
            'nb_withdraw' => Gdpr_presenter::getInstance()->getDeletions(array('nb' => true, 'status' => Gdpr_defines::WITHDRAW))
        );
    }

    public function deleteSelection($selection)
    {
        if ($selection)
            foreach ($selection as $value) {
                $obj = new Gdpr_notice($value);
                if (!$obj->delete()) {
                    return false;
                }
            }
        return true;
    }

    public function processStatus($selection, $active = null)
    {
        if ($selection)
            foreach ($selection as $value) {
                $obj = new Gdpr_notice($value);
                $obj->enabled = $active === null ? ($obj->enabled != 0 ? 0 : 1) : ($active ? 1 : 0);
                if (!$obj->update()) {
                    return false;
                }
            }
        return true;
    }

    public function deleteObj()
    {
        $object = new Gdpr_notice(Tools::getValue('id_' . $this->list_id, null));
        if (!$object->delete())
            $this->_errors[] = Ets_gdpr::$tran['cannot_delete'];
        elseif (isset($object->position)) {
            Db::getInstance()->execute("
                UPDATE `" . _DB_PREFIX_ . pSQL($this->list_id) . "`
                SET position=position-1 
                WHERE position>" . (int)$object->position . " AND id_shop = " . (int)$this->context->shop->id . "
            ");
        }
        if (!$this->_errors)
            return Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true) . '&conf=1&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name . $this->getUrlParams());
    }

    public function setFields($obj, $key, $values, $html = false)
    {
        if ($obj) {
            $obj->$key = $values;
        } else {
            Configuration::updateValue($key, $values, $html);
        }
    }

    private function processSave($params)
    {
        if (!(isset($params['pTAB'])) || !$params['pTAB'])
            return false;
        $languages = Language::getLanguages(false);
        $id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
        $configTabs = Gdpr_defines::getInstance()->get($params[(isset($params['sTAB']) ? 's' : 'p') . 'TAB']);
        $configs = $configTabs['configs'];
        $obj = isset($params['obj']) ? $params['obj'] : false;
        if ($configs) {
            foreach ($configs as $key => $config) {
                if (isset($config['lang']) && $config['lang']) {
                    if (isset($config['required']) && $config['required'] && $config['type'] != 'switch' && $this->required($key, $config['type'], $id_lang_default)) {
                        $this->_errors[] = $config['label'] . ' ' . $this->l('is required');
                    }
                } else {
                    if (isset($config['required']) && $config['required'] && $config['type'] != 'switch' && $this->required($key, $config['type'])) {
                        $this->_errors[] = $config['label'] . ' ' . ($key == 'ETS_GDPR_NOTIFY_WHEN' ? $this->l('must select at least on') : ' ' . $this->l('is required'));
                    } elseif (!is_array(Tools::getValue($key)) && isset($config['validate']) && method_exists('Validate', $config['validate'])) {
                        $validate = $config['validate'];
                        if (trim(Tools::getValue($key)) && !Validate::$validate(trim(Tools::getValue($key))))
                            $this->_errors[] = $config['label'] . ' ' . $this->l('is invalid');
                        unset($validate);
                    } elseif (!is_array(Tools::getValue($key)) && !Validate::isCleanHtml(trim(Tools::getValue($key)))) {
                        $this->_errors[] = $config['label'] . ' ' . $this->l('is invalid');
                    }
                }
            }
        }
        if (!$this->_errors) {
            if ($configs) {
                foreach ($configs as $key => $config) {
                    if (isset($config['lang']) && $config['lang']) {
                        $values = array();
                        foreach ($languages as $lang) {
                            if ($config['type'] == 'switch')
                                $values[$lang['id_lang']] = (int)trim(Tools::getValue($key . '_' . $lang['id_lang'])) ? 1 : 0;
                            else
                                $values[$lang['id_lang']] = trim(Tools::getValue($key . '_' . $lang['id_lang'])) ? trim(Tools::getValue($key . '_' . $lang['id_lang'])) : trim(Tools::getValue($key . '_' . $id_lang_default));
                        }
                        $this->setFields($obj, $key, $values, true);
                    } else {
                        if ($config['type'] == 'switch') {
                            $this->setFields($obj, $key, (int)trim(Tools::getValue($key)) ? 1 : 0, true);
                        } elseif ($config['type'] == 'select' && isset($config['multiple']) && $config['multiple']) {
                            $this->setFields($obj, $key, implode(',', Tools::getValue($key, array())));
                        } elseif ($config['type'] == 'gdpr_group' || $config['type'] == 'gdpr_checkbox') {
                            if (Tools::getIsset($key)) {
                                $val = ($getVal = Tools::getValue($key, array())) && ($result = implode(',', $getVal)) != 'ALL' ? $result : 'ALL';
                                $this->setFields($obj, $key, $val);
                            } else {
                                $this->setFields($obj, $key, null);
                            }
                        } elseif ($key != 'position')
                            $this->setFields($obj, $key, trim(Tools::getValue($key)), true);
                    }
                }
            }
        }
        $configLink = $this->context->link->getAdminLink('AdminModules', true) . '&conf=4&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name . (isset($params['pTAB']) && $params['pTAB'] ? '&pTAB=' . $params['pTAB'] : '') . (isset($params['sTAB']) && $params['sTAB'] ? '&sTAB=' . $params['sTAB'] : '');
        if (!count($this->_errors) && isset($obj->id_shop)) {
            $shops = Shop::getShops(false);
            $result = true;
            if (Shop::CONTEXT_ALL == Shop::getContext() && count($shops) > 1 && !$obj->id) {
                foreach ($shops as $shop) {
                    $obj->id_shop = (int)$shop['id_shop'];
                    $obj->position = (int)$obj->maxVal($shop['id_shop']) + 1;
                    $result &= $obj->add();
                }
            } else {
                if (!$obj->id)
                    $obj->position = (int)$obj->maxVal() + 1;
                $result &= ($obj->id && $obj->update() || !$obj->id && $obj->add());
            }
            if ($result) {
                Tools::redirectAdmin($configLink . (isset($params['list_id']) && $params['list_id'] && Tools::isSubmit('saveAndStayNotice') ? '&id_' . $params['list_id'] . '=' . $obj->id . '&update' . $params['list_id'] : ''));
            }
        }
        if (!$this->_errors) {
            Tools::redirectAdmin($configLink);
        }
    }

    public function required($key, $type, $id_lang = 0)
    {
        if (!$key)
            return false;
        if ($id_lang) {
            $key .= '_' . $id_lang;
        }
        switch ($key) {
            case 'ETS_GDPR_POLICY_PAGE_URL' . ($id_lang ? '_' . $id_lang : ''):
                if (!Tools::getValue('ETS_GDPR_POLICY_BY_POPUP', false) && !trim(Tools::getValue($key, '')))
                    return true;
                break;
            case 'ETS_GDPR_POLICY_BTN_TITLE' . ($id_lang ? '_' . $id_lang : ''):
                if (Tools::getValue('ETS_GDPR_POLICY_ENABLED', false) && !trim(Tools::getValue($key, '')))
                    return true;
                break;
            case 'ETS_GDPR_CAN_DELETE':
                if (Tools::getValue('ETS_GDPR_ALLOW_DELETE', false) && !Tools::getValue($key, array()))
                    return true;
                break;
            default:
                if ($type == 'gdpr_checkbox') {
                    if (!Tools::getValue($key, false))
                        return true;
                } elseif (!trim(Tools::getValue($key, ''))) {
                    return true;
                }
                break;
        }
        return false;
    }

    public function hookDisplayGdprHelp()
    {
        return $this->display(__FILE__, 'admin-help.tpl');
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (trim(Tools::getValue('controller')) == 'AdminModules' && trim(Tools::getValue('configure')) == $this->name) {
            $this->context->controller->addJquery();
            $this->context->controller->addJqueryUI('ui.sortable');
            $this->context->controller->addCss(array(
                $this->_path . 'views/css/common.css',
                $this->_path . 'views/css/admin.css',
                $this->_path . 'views/css/font-awesome.min.css',
                $this->_path . 'views/css/admin' . (version_compare(_PS_VERSION_, '1.6', '>=') ? '16' : '15') . '.css',
            ), 'all');
            if (version_compare(_PS_VERSION_, '1.6', '<')) {
                $this->context->controller->addCss($this->_path . 'views/css/nv.d3' . ($this->context->language->is_rtl ? '_rtl' : '') . '.css');
                $this->context->controller->addJS(array(
                    $this->_path . 'views/js/d3.v3.min.js',
                    $this->_path . 'views/js/nv.d3.min.js'
                ));
            } else {
                $admin_webpath = str_ireplace(_PS_CORE_DIR_, '', _PS_ADMIN_DIR_);
                $admin_webpath = preg_replace('/^' . preg_quote(DIRECTORY_SEPARATOR, '/') . '/', '', $admin_webpath);
                $this->context->controller->addCSS(__PS_BASE_URI__ . $admin_webpath . '/themes/' . $this->context->employee->bo_theme . '/css/vendor/nv.d3.css');
                $this->context->controller->addJS(array(
                    _PS_JS_DIR_ . 'vendor/d3.v3.min.js',
                    __PS_BASE_URI__ . $admin_webpath . '/themes/' . $this->context->employee->bo_theme . '/js/vendor/nv.d3.min.js'
                ));
            }
            $this->smarty->assign(array(
                'postLink' => $this->baseAdminUrl(),
                'successLabel' => $this->l('Email has been sent successfully'),
                'errorLabel' => $this->l('There was unknown errors happen while sending email. Please check you email configuration then try again.'),
            ));
            return $this->display(__FILE__, 'header.tpl');
        }
    }

    public function baseAdminUrl()
    {
        return $this->context->link->getAdminLink('AdminModules', true) . '&configure=' . $this->name;
    }

    public function hookDisplayHeader()
    {
        $this->context->controller->addCSS(array(
            $this->_path . 'views/css/common.css',
            $this->_path . 'views/css/front.css',
            $this->_path . 'views/css/fix' . ($this->is17 ? '17' : (version_compare(_PS_VERSION_, '1.6', '>=') ? '16' : '15')) . '.css',
            $this->_path . 'views/css/font-awesome.min.css',
        ), 'all');
        if (!$this->is17) {
            $this->smarty->assign('base_dir', $this->_path);
        } else {
            $this->context->controller->addJS($this->_path . 'views/js/front.js');
        }
        $this->smarty->assign(array(
            'link_accept' => $this->context->link->getModuleLink($this->name, 'gdpr', array(), true),
            'declineUrl' => Configuration::get('ETS_GDPR_REDIRECT_URL', $this->context->language->id),
            'productLink' => $this->context->link->getPageLink('product', Tools::usingSecureMode() ? true : false),
        ));
        return $this->display(__FILE__, 'header.tpl');
    }

    public function assignJsConfig($params)
    {
        if (!(isset($params['name'])) || !$params['name'])
            return array();
        $confObj = Gdpr_defines::getInstance()->get($params['name']);
        $configs = array();
        $isJs = isset($params['isJs']) && $params['isJs'] ? true : false;
        if (!empty($confObj['configs'])) {
            foreach ($confObj['configs'] as $key => $val) {
                $resVal = Configuration::get($key, (isset($val['lang']) && $val['lang'] ? $this->context->language->id : null));
                $configs[$key] = $isJs ? array(
                    'value' => $resVal,
                    'type' => isset($val['jsType']) && $val['jsType'] ? $val['jsType'] : 'string',
                ) : $resVal;
            }
        }
        if ($isJs)
            $this->smarty->assign(array('ETS_GDPR_CONFIG' => $configs));
        return $configs;
    }

    public function gdprValid($hook)
    {
        $id_group = (int)Group::getCurrent()->id;
        if (!$id_group)
            return false;
        if (!($accessGroups = Configuration::get('ETS_GDPR_GROUP_TO_SEE')) || ($accessGroups != 'ALL' && !in_array($id_group, explode(',', $accessGroups))))
            return false;
        if (!Configuration::get('ETS_GDPR_NOTIFY_WHEN'))
            return false;
        elseif (Configuration::get('ETS_GDPR_NOTIFY_WHEN') != 'ALL') {
            $positions = explode(',', Configuration::get('ETS_GDPR_NOTIFY_WHEN'));
            if (($position = $hook) && in_array($hook, array('footer', 'top'))) {
                $position = 'ONSITE';
            }
            if (!in_array(Tools::strtoupper($position), $positions)) {
                return false;
            }
        }
        if (Configuration::get('ETS_GDPR_NOTICES_SHOW_AGAIN') && $id_group == (int)Configuration::get('PS_CUSTOMER_GROUP') && (!(isset($this->context->cookie->gdprVisitor)) || !$this->context->cookie->gdprVisitor) && ($id_customer = Gdpr_acceptance::getCustomerAcceptance($this->context->customer->id))) {
            $this->context->cookie->gdprVisitor = $id_customer;
            $this->context->cookie->write();
        }
        if (isset($this->context->cookie->gdprVisitor) && $this->context->cookie->gdprVisitor && in_array($hook, array('footer', 'top')))
            return false;
        return true;
    }

    public function hookDisplayNav1()
    {
        if ($this->is17)
            return $this->displayPolicy();
    }

    public function hookDisplayNav()
    {
        if (!$this->is17)
            return $this->displayPolicy();
    }

    public function displayPolicy()
    {
        if (Configuration::get('ETS_GDPR_POLICY_ENABLED') && ((Configuration::get('ETS_GDPR_POLICY_BY_POPUP') && Gdpr_presenter::getInstance()->getNotices(array('nb' => 1, 'isFront' => 1))) || (!Configuration::get('ETS_GDPR_POLICY_BY_POPUP') && Configuration::get('ETS_GDPR_POLICY_PAGE_URL', $this->context->language->id)))) {
            $this->smarty->assign(array(
                'policy' => $this->assignJsConfig(array('name' => 'policy')),
            ));
            return $this->display(__FILE__, 'nav.tpl');
        }
    }

    public function displayFrontGpdrs($params)
    {
        if (!$params)
            return false;
        if (isset($params['hook']) && $this->gdprValid($params['hook'])) {
            $design = $this->assignJsConfig(array('name' => 'design'));
            $this->smarty->assign(array(
                'hook' => $params['hook'] ? $params['hook'] : '',
                'noticeHtml' => $this->htmlNotices($design),
                'design' => $design,
                'message' => array(
                    'ETS_GDPR_MES_HOOK' => Configuration::get('ETS_GDPR_MES_' . Tools::strtoupper($params['hook']), $this->context->language->id),
                    'ETS_GDPR_MES_VIEW_MORE' => Configuration::get('ETS_GDPR_MES_' . Tools::strtoupper($params['hook']) . '_MORE'),
                ),
                'is17' => $this->is17,
                'page' => Tools::getValue('controller', false),
            ));
            return $this->display(__FILE__, 'gdpr.tpl');
        }
    }

    public function htmlNotices($design)
    {
        if (!$design)
            return false;
        $this->smarty->assign(array(
            'design' => $design,
            'notices' => Gdpr_presenter::getInstance()->getNotices(array('isFront' => true)),
        ));
        return $this->display(__FILE__, 'notice.tpl');
    }

    public function hookDisplayCustomerLoginFormAfter()
    {
        return $this->displayFrontGpdrs(array(
            'hook' => 'login'
        ));
    }

    public function hookDisplayFooterBefore()
    {
        if (Module::isEnabled('ps_emailsubscription'))
            return $this->displayFrontGpdrs(array(
                'hook' => 'subscribe'
            ));
    }

    public function hookDisplayReassurance()
    {
        return $this->displayFrontGpdrs(array('hook' => Configuration::get('ETS_GDPR_WELCOME_TEMPLATE') != 'top' ? 'footer' : 'top')) . $this->htmlNotices($this->assignJsConfig(array('name' => 'design')));
    }

    public function hookDisplayProductAdditionalInfo()
    {
        if (Module::isEnabled('ps_emailalerts'))
            return $this->displayFrontGpdrs(array(
                'hook' => 'receive'
            ));
    }

    public function hookActionProductOutOfStock()
    {
        if (Module::isEnabled('mailalerts'))
            return $this->displayFrontGpdrs(array(
                'hook' => 'receive'
            ));
    }

    public function hookDisplayFooter()
    {
        $html = '';
        if (Configuration::get('ETS_GDPR_WELCOME_TEMPLATE') != 'top') {
            $html .= $this->displayFrontGpdrs(array(
                'hook' => 'footer'
            ));
        }
        if (!$this->is17 && Module::isEnabled('blocknewsletter')) {
            $html .= $this->displayFrontGpdrs(array('hook' => 'subscribe'));
        }
        if (($page = Tools::getValue('controller', false)) == 'contact') {
            $html .= $this->displayFrontGpdrs(array('hook' => 'contact'));
        } elseif (!$this->is17 && $page == 'authentication') {
            $html .= $this->displayFrontGpdrs(array('hook' => 'login'));
        }
        if (($page != 'order' && $this->is17) || !$this->is17) {
            $html .= $this->htmlNotices($this->assignJsConfig(array('name' => 'design')));
        }
        return $html;
    }

    public function hookDisplayTop()
    {
        if (Configuration::get('ETS_GDPR_WELCOME_TEMPLATE') == 'top')
            return $this->displayFrontGpdrs(array(
                'hook' => 'top'
            ));
    }

    public function hookDisplayCustomerAccountForm()
    {
        if (($page = Tools::getValue('controller', false)) && in_array($page, array('order', 'authentication')))
            return $this->displayFrontGpdrs(array(
                'hook' => 'register'
            ));
    }

    public function hookActionCustomerAccountAdd()
    {
        if (!(isset($this->context->cookie->gdprVisitor)) || !$this->context->cookie->gdprVisitor) {
            $this->context->cookie->gdprVisitor = 1;
            $this->context->cookie->write();
            Gdpr_acceptance::acceptSave($this->context);
        }
    }

    public function hookDisplayLeftColumn($params)
    {
        return $this->hookDisplayGdprMenu($params);
    }

    public function getMenus($firstItem = false)
    {
        $menus = Gdpr_defines::getInstance()->getConfigFrontTabs();
        if (!Configuration::get('ETS_GDPR_ALLOW_VIEW')) {
            unset($menus);
            if (Configuration::get('ETS_GDPR_CAN_DELETE'))
                $menus['del'] = Gdpr_defines::getInstance()->getConfigFrontTabs('del');
        } elseif (Configuration::get('ETS_GDPR_DATA_TO_VIEW') != 'ALL') {
            $dataViews = explode(',', Configuration::get('ETS_GDPR_DATA_TO_VIEW'));
            if ($dataViews) {
                $isPer = false;
                $dataTypes = array_merge(array(
                    'GEN' => array(
                        'name' => $this->l('General information'),
                        'id_option' => 'GEN',
                        'tmp' => 'information',
                        'label' => $this->l('General information'),
                    )
                ), Gdpr_defines::getInstance()->getDataTypes());
                foreach ($dataTypes as $type => $dataType) {
                    if ($dataType && $type != 'MOD' && $type != 'LOG' && in_array($type, $dataViews)) {
                        $isPer = true;
                        break;
                    }
                }
                if (!$isPer)
                    unset($menus['per']);
            }
            if (!in_array('MOD', $dataViews)) {
                unset($menus['mod']);
            }
            if (!in_array('LOG', $dataViews)) {
                unset($menus['log']);
            }
        }
        if (isset($menus['log']) && !Configuration::get('ETS_GDPR_ENABLE_LOGIN_LOG')) {
            unset($menus['log']);
        }
        if (isset($menus['del']) && (!Configuration::get('ETS_GDPR_CAN_DELETE') || !Configuration::get('ETS_GDPR_ALLOW_DELETE'))) {
            unset($menus['del']);
        }
        if ($firstItem)
            return !empty($menus) ? reset($menus) : false;
        return $menus;
    }

    public function hookDisplayCustomerAccount()
    {
        $curTab = $this->getMenus(true);
        $this->smarty->assign(array(
            'link' => ($curTab ? $this->context->link->getModuleLink($this->name, 'gdpr', array('control' => 'myaccount', 'curTab' => $curTab['name']), true) : false),
            'is17' => $this->is17,
        ));
        return $this->display(__FILE__, 'block.tpl');
    }

    public function hookDisplayGdprMenu()
    {
        if (Tools::getValue('controller', false) == 'gdpr') {
            $menus = $this->getMenus();
            $currentTab = Tools::getValue('curTab', 'myaccount');
            $this->smarty->assign(array(
                'link' => $this->context->link->getModuleLink($this->name, 'gdpr', array('control' => Tools::getValue('control', 'personal')), true),
                'menus' => $menus,
                'currentTab' => $currentTab
            ));
            return $this->display(__FILE__, 'menu.tpl');
        }
    }

    public function hookDisplayGpdrBlock($params)
    {
        if (!empty($params) && isset($params['template']) && $params['template']) {
            $this->smarty->assign($params);
            return $this->display(__FILE__, $params['template'] . '.tpl');
        }
    }

    public function getBreadcrumb()
    {
        $breadcrumb = $this->getBreadcrumbLinks();
        $breadcrumb['count'] = count($breadcrumb['links']);
        if ($this->is17)
            return $breadcrumb;
        else
            return $this->displayBreadcrumb($breadcrumb);
    }

    public function getBreadcrumbLinks()
    {
        $breadcrumb = array();
        if ($this->is17)
            $breadcrumb['links'][] = array(
                'title' => $this->l('Home'),
                'url' => $this->context->link->getPageLink('index', true),
            );
        $breadcrumb['links'][] = array(
            'title' => $this->l('My account'),
            'url' => $this->context->link->getPageLink('my-account', true),
        );
        if (($curTab = Tools::getValue('curTab', false))) {
            $configFrontTabs = Gdpr_defines::getInstance()->getConfigFrontTabs(Tools::substr($curTab, 0, 3));
            $breadcrumb['links'][] = array(
                'title' => isset($configFrontTabs['label']) ? $configFrontTabs['label'] : '',
                'url' => $this->context->link->getModuleLink($this->name, 'gdpr', array('control' => 'myaccount', 'curTab' => $curTab), true),
            );
        }
        return $breadcrumb;
    }

    public function displayBreadcrumb($breadcrumb)
    {
        $this->smarty->assign('breadcrumb', $breadcrumb);
        return $this->display(__FILE__, 'breadcrumb.tpl');
    }

    public function genericList($listData)
    {
        if (isset($listData['fields_list']) && $listData['fields_list']) {
            foreach ($listData['fields_list'] as $key => &$val) {
                $val['active'] = trim(Tools::getValue($key));
            }
        }
        $this->context->smarty->assign($listData);
        return $this->display(__FILE__, 'list_helper.tpl');
    }

    public function getUrlExtra($field_list)
    {
        $params = '';
        if (trim(Tools::getValue('sort')) && isset($field_list[trim(Tools::getValue('sort'))])) {
            $params .= '&sort=' . trim(Tools::getValue('sort')) . '&sort_type=' . (trim(Tools::getValue('sort_type')) == 'asc' ? 'asc' : 'desc');
        }
        if ($field_list) {
            foreach ($field_list as $key => $val) {
                if (Tools::getValue($key) != '') {
                    $params .= '&' . $key . '=' . urlencode(Tools::getValue($key));
                }
            }
            unset($val);
        }
        return $params;
    }

    public function renderGdprList($params)
    {
        if (!(isset($params)) || !$params)
            return false;
        $fields_list = $params['fields_list'];
        $sort = isset($params['orderBy']) && $params['orderBy'] ? $params['alias'] . '.' . $params['orderBy'] . ' ' . (isset($params['orderWay']) ? $params['orderWay'] : 'ASC') : '';
        $page = (int)Tools::getValue('page') && (int)Tools::getValue('page') > 0 ? (int)Tools::getValue('page') : 1;
        $nbList = (int)Gdpr_presenter::getInstance()->get($params['nb'], array(
            'nb' => true,
            'frontend' => true,
            'id_customer' => $this->context->customer->id,
        ));
        $pagination = new Gdpr_pagination();
        $pagination->total = $nbList;
        $pagination->url = $this->context->link->getModuleLink($this->name, 'gdpr', array('control' => 'myaccount', 'curTab' => Tools::getValue('curTab', 'personal'), 'page' => '_page_'), true) . $this->getUrlExtra($fields_list);
        $pagination->limit = 20;
        $nbPages = ceil($nbList / $pagination->limit);
        if ($page > $nbPages)
            $page = $nbPages;
        $pagination->page = $page;
        $start = $pagination->limit * ($page - 1);
        if ($start < 0)
            $start = 0;
        $lists = Gdpr_presenter::getInstance()->get($params['list'], array(
            'sort' => $sort,
            'start' => $start,
            'limit' => $pagination->limit,
            'frontend' => true,
            'id_customer' => $this->context->customer->id,
        ));
        $pagination->text = $this->l('Showing {start} to {end} of {total} ({pages} Pages)');
        $pagination->style_links = $this->l('links');
        $pagination->style_results = $this->l('results');
        $paramLinks = array('control' => 'myaccount', 'curTab' => Tools::getValue('curTab', 'personal'));
        if (($numPage = Tools::getValue('page', false))) {
            $paramLinks += array('page' => $numPage);
        }
        $useSSL = Tools::usingSecureMode();
        $listData = array(
            'name' => $params['list_id'],
            'actions' => !empty($params['actions']) ? $params['actions'] : false,
            'currentIndex' => $this->context->link->getModuleLink($this->name, 'gdpr', $paramLinks, true),
            'identifier' => 'id_' . $params['list_id'],
            'show_toolbar' => false,
            'show_action' => isset($params['actions']) && $params['actions'],
            'title' => $params['title'],
            'fields_list' => $fields_list,
            'field_values' => $lists,
            'pagination' => $pagination->render(),
            'nb_pages' => $nbPages,
            'show_reset' => false,
            'totalRecords' => $nbList,
            'btnLink' => $this->context->link->getModuleLink($this->name, 'gdpr', $paramLinks, $useSSL),
            'contactLink' => $this->context->link->getPageLink('contact', $useSSL),
        );
        return $this->genericList($listData);
    }

    public function checkHash($passwd, $hash, $staticSalt = _COOKIE_KEY_)
    {
        $hashMethods = array(
            'bcrypt' => array(
                'option' => array(),
                'hash' => function ($passwd) {
                    return password_hash($passwd, PASSWORD_BCRYPT);
                },
                'verify' => function ($passwd, $hash) {
                    return password_verify($passwd, $hash);
                },
            ),
            'md5' => array(
                'option' => array(),
                'hash' => function ($passwd, $staticSalt) {
                    return md5($staticSalt . $passwd);
                },
                'verify' => function ($passwd, $hash, $staticSalt) {
                    return md5($staticSalt . $passwd) === $hash;
                },
            ),
        );
        foreach ($hashMethods as $closure) {
            if ($closure['verify']($passwd, $hash, $staticSalt)) {
                return true;
            }
        }
        return false;
    }

    public function processDeletion(&$errors)
    {
        if (!$this->context->customer->isLogged())
            Tools::redirectLink($this->context->link->getPageLink('index', true));
        if (!Tools::getValue('dataType', false))
            $errors[] = $this->l('Please select the items that you want to delete.');
        $password = Tools::getValue('password');
        if (trim($password) == '')
            $errors[] = $this->l('Password is required.');
        if (!count($errors)) {
            if (!$this->checkHash($password, $this->context->customer->passwd))
                $errors[] = $this->l('Incorrect password.');
            elseif (Gdpr_deletion::checkRequested($this->context->customer->id))
                $errors[] = $this->l('Your request has been submitted. Please wait for admin feedback.');
            if (!count($errors)) {
                if (Configuration::get('ETS_GDPR_REQUIRE_APPROVAL')) {
                    $dataType = in_array('ALL', Tools::getValue('dataType')) ? 'ALL' : implode(',', Tools::getValue('dataType'));
                    $deletionId = $this->saveDeletion($this->context->customer, $errors, false, $dataType, Gdpr_defines::PENDING, true);
                    if (Configuration::get('ETS_GDPR_EMAIL_WHEN_REQUIRE') && $deletionId && ($deletion = Gdpr_presenter::getInstance()->getDeletions(array('itemId' => $deletionId, 'status' => Gdpr_defines::PENDING)))) {
                        $content = $this->displaySmarty('EMAIL_REQUEST');
                        $content = str_replace(array('[itemId]', '[full_name]', '[email]', '[data_modified]'), array(
                            $deletionId,
                            $deletion['customer_name'],
                            $deletion['email'],
                            $deletion['data_to_delete']
                        ), $content);
                        if (!Mail::Send(
                            $this->context->language->id,
                            'login',
                            $this->l('New data deletion request') . ' #' . $deletionId,
                            array(
                                '{content}' => $content,
                                '{firstname}' => '',
                                '{lastname}' => Configuration::get('PS_SHOP_NAME', null, null, $this->context->shop->id),
                            ),
                            Configuration::get('PS_SHOP_EMAIL', null, null, $this->context->shop->id),
                            Configuration::get('PS_SHOP_NAME', null, null, $this->context->shop->id),
                            $deletion['email'],
                            $deletion['customer_name'],
                            null,
                            null,
                            $this->getLocalPath() . 'mails/',
                            false,
                            $this->context->shop->id
                        )) {
                            $errors[] = $this->l('There were unknown errors happen while sending email. Please check your email then try again.');
                        }
                    }
                    unset($deletionId);
                } else
                    $this->submitDeleteData($this->context->customer->id, Tools::getValue('dataType'), $errors, false, Gdpr_defines::AUTO);
            }
        }
    }

    public function submitDeleteData($id_customer, $dataType, &$errors, $Id = false, $status = false)
    {
        if (!$dataType)
            return false;
        $customer = new Customer($id_customer);
        if (in_array('ALL', $dataType)) {
            Gdpr_login::deleteDataLoginLog($id_customer);
            Gdpr_modification::deleteDataModification($id_customer);
            Gdpr_deletion::deleteDataMessage($id_customer);
            Gdpr_deletion::deleteDataOrder($id_customer);
            Gdpr_deletion::deleteDataAddress($id_customer);
            if (!$this->is17)
                Gdpr_deletion::deleteDataReviews($id_customer);
            Gdpr_deletion::deleteGeneralInformation($id_customer);
        } else {
            if (in_array('LOG', $dataType))
                Gdpr_login::deleteDataLoginLog($id_customer);
            if (in_array('MOD', $dataType))
                Gdpr_modification::deleteDataModification($id_customer);
            if (in_array('SUB', $dataType))
                Gdpr_deletion::deleteDataSubscription($id_customer);
            if (in_array('MES', $dataType))
                Gdpr_deletion::deleteDataMessage($id_customer);
            if (in_array('ORD', $dataType))
                Gdpr_deletion::deleteDataOrder($id_customer);
            if (in_array('ADD', $dataType))
                Gdpr_deletion::deleteDataAddress($id_customer);
            if (in_array('REV', $dataType) && !$this->is17)
                Gdpr_deletion::deleteDataReviews($id_customer);
        }
        $this->saveDeletion($customer, $errors, $Id, (in_array('ALL', $dataType) ? 'ALL' : implode(',', $dataType)), $status);
        if (Gdpr_tools::getControllerType() != 'admin' && in_array('ALL', $dataType))
            Tools::redirectLink($this->context->link->getPageLink('authentication', true));
        return true;
    }

    public function saveDeletion($customer, &$errors, $Id = false, $dataType = array(), $status = false, $returnId = false)
    {
        $controller_type = Gdpr_tools::getControllerType();
        if (!Validate::isLoadedObject($customer)) {
            if ($controller_type != 'admin')
                $customer = $this->context->customer;
            else
                return false;
        }
        $id_lang = $customer->id_lang ? $customer->id_lang : $this->context->language->id;
        $deletion = ($id = Tools::getValue('id_ets_gdpr_deletion', $Id)) ? new Gdpr_deletion($id) : new Gdpr_deletion();
        $data_to_delete = $dataType ? $dataType : (($dataType = Tools::getValue('dataType', array())) && in_array('ALL', $dataType) ? 'ALL' : ($dataType ? implode(',', $dataType) : ''));
        if (!$deletion->id) {
            $deletion->id_shop = (int)$customer->id_shop;
            $deletion->id_customer = (int)$customer->id;
            $deletion->requested_date_time = date('Y-m-d H:i:s');
            $deletion->data_to_delete = $data_to_delete;
            if ($controller_type != 'admin')
                $deletion->action_taken_date_time = date('Y-m-d H:i:s');
        } else
            $deletion->action_taken_date_time = date('Y-m-d H:i:s');
        $statusDeletion = Gdpr_defines::getInstance()->getStatusDeletion();
        $deletion->status = $status ? $status : (!$deletion->id ? Gdpr_defines::PENDING : $statusDeletion[Tools::strtoupper(Tools::getValue('status', 'PEN'))]['id_option']);
        if (!$deletion->save()) {
            $errors[] = $controller_type != 'admin' ? $this->l('An error occurred, please try again later.') : $this->l('Error update deletion status.');
        } elseif (!$errors && $deletion->status != Gdpr_defines::PENDING) {
            if (Configuration::get('ETS_GDPR_EMAIL_WHEN_DECLINE') && $deletion->status == Gdpr_defines::DECLINED) {
                $subject = Configuration::get('ETS_GDPR_REFUSAL_SUBJECT', $id_lang);
                $content = $this->replaceCode(Configuration::get('ETS_GDPR_REFUSAL_EMAIL', $id_lang), $customer, $deletion->data_to_delete);
                $this->sendMail($customer, $subject, $content);
            }
            if (Configuration::get('ETS_GDPR_EMAIL_WHEN_DELETE') && ($deletion->status == Gdpr_defines::APPROVED || $deletion->status == Gdpr_defines::AUTO)) {
                $this->sendEmailDelete($id_lang, $customer, $deletion->data_to_delete);
            }
        }
        return $returnId && !$errors ? $deletion->id : (!$errors ? true : false);
    }

    public function sendEmailDelete($id_lang, $customer, $data_to_delete)
    {
        $subject = Configuration::get('ETS_GDPR_DELETION_SUBJECT', $id_lang);
        $content = $this->replaceCode(Configuration::get('ETS_GDPR_DELETION_EMAIL', $id_lang), $customer, $data_to_delete);
        $this->sendMail($customer, $subject, $content);
    }

    public function replaceCode($content, $customer, $dataType)
    {
        return str_replace(
            array('[customer]', '[data_deleted]', '[shop_name]'),
            array(
                $customer->firstname . ' ' . $customer->lastname,
                $this->displayDataType(array('dataTypes' => $dataType)),
                $this->displaySmarty('SHOP_NAME'),
            ),
            $content
        );
    }

    public function subScribe(&$errors)
    {
        if (($customer_fields = Tools::getValue('subscribe')) && Tools::getIsset($customer_fields) && isset($this->context->customer->$customer_fields)) {
            $this->context->customer->$customer_fields = Tools::getValue($customer_fields, 0);
            $msg = $this->l('Partner offers');
            if ($customer_fields != 'optin') {
                $this->context->customer->newsletter_date_add = date('Y-m-d H:i:s');
                $this->context->customer->ip_registration_newsletter = Tools::getRemoteAddr();
                $msg = $this->l('Newsletter');
            }
            if ($this->context->customer->update() && isset($this->context->customer->$customer_fields)) {
                if ($this->context->customer->$customer_fields)
                    $msg .= ' ' . $this->l('Subscribed.');
                else
                    $msg .= ' ' . $this->l('Unsubscribed.');
                return $msg;
            } else {
                $errors[] = $this->l('An error occurred, please try again later.');
            }
        }
        return false;
    }

    public function renderView($id, $params)
    {
        if (!(isset($params['pTAB'])) || !(isset($params['sTAB'])))
            return false;
        $deletion = new Gdpr_deletion($id);
        $customer = new Customer($deletion->id_customer);
        if (!$deletion->data_to_delete)
            return false;
        $assign = array(
            'backLink' => AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . ($params['pTAB'] ? '&pTAB=' . $params['pTAB'] : '') . ($params['sTAB'] ? '&sTAB=' . $params['sTAB'] : ''),
            'controller_type' => Gdpr_tools::getControllerType(),
            'identity' => $id,
        );
        $deletions = explode(',', $deletion->data_to_delete);
        if ($deletion->data_to_delete == 'ALL')
            $assign += array('customer' => $customer);
        if ($this->isViews($deletion, $deletions, 'ADD'))
            $assign += array('addresses' => $this->getAddress($customer->id));
        if ($this->isViews($deletion, $deletions, 'ORD')) {
            $order_fields = Gdpr_defines::getInstance()->getOrderFields();
            $assign += array(
                'orders' => $this->renderList(
                    $order_fields['list'] + array(
                        'id_customer' => $customer->id,
                        'pTAB' => $params['pTAB'],
                        'sTAB' => $params['sTAB'],
                        'fields_list' => $order_fields['fields']
                    ))
            );
        }
        if (!$this->is17 && $this->isViews($deletion, $deletions, 'REV') && (Module::isEnabled('productcomments') || Gdpr_deletion::isReviews())) {
            $reviews_fields = Gdpr_defines::getInstance()->getReviewsFields();
            $assign += array(
                'reviews' => $this->renderList(
                    $reviews_fields['list'] + array(
                        'id_customer' => $customer->id,
                        'pTAB' => $params['pTAB'],
                        'sTAB' => $params['sTAB'],
                        'fields_list' => $reviews_fields['fields']
                    ))
            );
        }
        if ($this->isViews($deletion, $deletions, 'SUB')) {
            $assign += array(
                'subscriptions' => array(
                    'newsletter' => $customer->newsletter,
                    'partner' => $customer->optin,
                ),
            );
        }
        if ($this->isViews($deletion, $deletions, 'MES')) {
            $mes_fields = Gdpr_defines::getInstance()->getContactMessageFields();
            $assign += array(
                'messages' => $this->renderList(
                    $mes_fields['list'] + array(
                        'id_customer' => $customer->id,
                        'pTAB' => $params['pTAB'],
                        'sTAB' => $params['sTAB'],
                        'fields_list' => $mes_fields['fields']
                    )),
            );
        }
        if ($this->isViews($deletion, $deletions, 'MOD')) {
            $mlog_fields = Gdpr_defines::getInstance()->getModifiedLogFields();
            $assign += array(
                'mod_log' => $this->renderList(
                    $mlog_fields['list'] + array(
                        'id_customer' => $customer->id,
                        'pTAB' => $params['pTAB'],
                        'sTAB' => $params['sTAB'],
                        'fields_list' => $mlog_fields['fields']
                    )),
            );
        }
        if ($this->isViews($deletion, $deletions, 'LOG')) {
            $llog_fields = Gdpr_defines::getInstance()->getLoginLogFields();
            $assign += array(
                'login_log' => $this->renderList(
                    $llog_fields['list'] + array(
                        'id_customer' => $customer->id,
                        'pTAB' => $params['pTAB'],
                        'sTAB' => $params['sTAB'],
                        'fields_list' => $llog_fields['fields']
                    )),
            );
        }
        $this->smarty->assign($assign);
        return $this->_html .= (isset($params['html']) ? $params['html'] : '') . $this->display(__FILE__, 'renderView.tpl');
    }

    public function isViews($deletion, $deletions, $type)
    {
        if ($deletion->data_to_delete == 'ALL' || ($deletion->data_to_delete != 'ALL' && in_array($type, $deletions)))
            return true;
        return false;
    }

    public function renderViewLog($id, $params)
    {
        if (!(isset($params['pTAB'])) || !(isset($params['sTAB'])))
            return false;
        $mlog = Gdpr_presenter::getInstance()->getMLogs(array('itemId' => $id));
        $this->smarty->assign(array(
            'mLog' => $mlog,
            'mLink' => AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . ($params['pTAB'] ? '&pTAB=' . $params['pTAB'] : '') . ($params['sTAB'] ? '&sTAB=' . $params['sTAB'] : ''),
            'admin' => Gdpr_tools::getControllerType() != 'admin' ? false : true,
        ));
        return $this->_html .= (isset($params['html']) ? $params['html'] : '') . $this->display(__FILE__, 'modification-log.tpl');
    }

    public function hookActionAuthentication()
    {
        if (Configuration::get('ETS_GDPR_ENABLE_LOGIN_LOG') && $this->context->customer) {
            $log = new Gdpr_login();
            $log->id_customer = (int)$this->context->customer->id;
            $log->id_shop = (int)$this->context->shop->id;
            $log->ip = Tools::getRemoteAddr();
            $log->device = $this->getDevice();
            $log->login_date_time = date('Y-m-d H:i:s');
            if ($log->save() && Configuration::get('ETS_GDPR_EMAIL_WHEN_LOGGED_IN')) {
                $content = $this->displaySmarty('EMAIL_LOGIN');
                if ($content) {
                    $content = str_replace(array('[device]', '[ip]'), array($this->getDevice(), $log->ip), $content);
                    $this->sendMail(
                        $this->context->customer,
                        $this->l('Account login'),
                        $content,
                        'login'
                    );
                }
            }
        }
    }

    public function dataUpdated($params)
    {
        if (empty($params['id_customer']) || !Configuration::get('ETS_GDPR_ENABLE_MOD_LOG'))
            return false;
        $context = Context::getContext();
        $controllerType = Gdpr_tools::getControllerType();
        $id_lang = $context->language->id;
        $customer = new Customer((int)$params['id_customer']);
        $details = $params['details'];
        $modified_by = '';
        if ($controllerType == 'admin') {
            $employee = $this->context->employee;
            $profile = Profile::getProfile($employee->id_profile, $id_lang);
            $modified_by = $employee->firstname . ' ' . $employee->lastname . ' (' . $this->l('Role') . ': ' . $profile['name'] . ')';
        } else
            $modified_by = $customer->firstname . ' ' . $customer->lastname . ' (' . $this->l('Role: Customer') . ')';
        $mLog = new Gdpr_modification();
        $mLog->id_shop = $context->shop->id;
        $mLog->id_customer = $customer->id;
        if ($modified_by)
            $mLog->modified_by = $modified_by;
        if ($details)
            $mLog->details = $details;
        if ($params['data_type'])
            $mLog->data_modified = $params['data_type'];
        $mLog->modified_date_time = date('Y-m-d H:i:s');
        if ($mLog->save())
            $this->sendEmailToCustomer($customer, $mLog->id);
    }

    public function hookActionObjectUpdateBefore($params)
    {
        if (Validate::isLoadedObject($params['object']) && ($class = get_class($params['object'])) && in_array($class, array('Customer', 'Order', 'CustomerAddress', 'ProductComment'))) {
            $this->currentObj = new $class($params['object']->id);
        }
    }

    public function getAddressModify($details, $newObj)
    {
        if (empty($details))
            return false;
        $customer = new Customer((int)$newObj->id_customer);
        $dataChanged = array();
        if (isset($details['id_country'])) {
            $country = new Country((int)$newObj->id_country, $customer->id_lang);
            $dataChanged['id_country'] = $this->l('Country') . ': ' . $country->name;
        }
        if (isset($details['id_state'])) {
            $state = new State((int)$newObj->id_state, $customer->id_lang);
            $dataChanged['id_state'] = $this->l('State') . ': ' . $state->name;
        }
        $dataChanged['alias'] = $this->l('Address name') . ': ' . $newObj->alias;
        if (isset($dataChanged['firstname']))
            $dataChanged['firstname'] = array('label' => $this->l('First Name'), 'firstname' => $newObj->firstname);
        if (isset($dataChanged['lastname']))
            $dataChanged['lastname'] = array('label' => $this->l('Last Name'), 'lastname' => $newObj->lastname);
        if (isset($dataChanged['company']))
            $dataChanged['company'] = array('label' => $this->l('Company'), 'company' => $newObj->company);
        if (isset($dataChanged['address1']))
            $dataChanged['address1'] = array('label' => $this->l('Address'), 'address1' => $newObj->address1);
        if (isset($dataChanged['address2']))
            $dataChanged['address2'] = array('label' => $this->l('Address (2)'), 'address2' => $newObj->address2);
        if (isset($dataChanged['city']))
            $dataChanged['city'] = array('label' => $this->l('City'), 'city' => $newObj->city);
        if (isset($dataChanged['postcode']))
            $dataChanged['postcode'] = array('label' => $this->l('Zip/Postal Code'), 'postcode' => $newObj->postcode);
        if (isset($dataChanged['phone']))
            $dataChanged['phone'] = array('label' => $this->l('Phone number'), 'phone' => $newObj->phone);
        if (isset($dataChanged['phone_mobile']))
            $dataChanged['phone_mobile'] = array('label' => $this->l('Mobile phone number'), 'phone_mobile' => $newObj->phone_mobile);
        return $dataChanged;
    }

    public function getCustomerModify($details, &$data_type)
    {
        if (empty($details))
            return false;
        $dataChanged = array();
        if (isset($details['newsletter']))
            $dataChanged['newsletter'] = array('label' => $this->l('Newsletter'), 'newsletter' => $details['newsletter'] ? $this->l('subscribed') : $this->l('not subscribed'));
        if (isset($details['optin']))
            $dataChanged['optin'] = array('label' => $this->l('Partner offers'), 'optin' => $details['optin'] ? $this->l('subscribed') : $this->l('not subscribed'));
        if (isset($details['newsletter']) || isset($details['optin']) || isset($details['newsletter_date_add']) || isset($details['ip_registration_newsletter']))
            $data_type[] = 'SUB';
        if (isset($details['firstname']))
            $dataChanged['firstname'] = array('label' => $this->l('First Name'), 'firstname' => $details['firstname']);
        if (isset($details['lastname']))
            $dataChanged['lastname'] = array('label' => $this->l('Last Name'), 'lastname' => $details['lastname']);
        if (isset($details['birthday']))
            $dataChanged['birthday'] = array('label' => $this->l('Birthday'), 'birthday' => $details['birthday']);
        if (isset($details['id_gender'])) {
            if ($details['id_gender'] == 1)
                $dataChanged['id_gender'] = array('label' => $this->l('Social title'), 'id_gender' => 'Mr.');
            elseif ($details['id_gender'] == 2)
                $dataChanged['id_gender'] = array('label' => $this->l('Social title'), 'id_gender' => 'Mrs.');
            else
                $dataChanged['id_gender'] = array('label' => $this->l('Social title'), 'id_gender' => 'Unknown');
        }
        if (isset($details['passwd']))
            $dataChanged['passwd'] = array('label' => $this->l('Password'), 'passwd' => 'Changed');
        if (isset($details['email']))
            $dataChanged['email'] = array('label' => $this->l('Email address'), 'email' => $details['email']);
        if (isset($details['website']))
            $dataChanged['website'] = array('label' => $this->l('Website'), 'website' => $details['website']);
        if ($dataChanged)
            $data_type[] = 'GEN';
        return $dataChanged;
    }

    public function hookActionObjectUpdateAfter($params)
    {
        if (Configuration::get('ETS_GDPR_ENABLE_MOD_LOG') && Validate::isLoadedObject($params['object']) && ($class = get_class($params['object'])) && in_array($class, array('Customer', 'Order', 'CustomerAddress', 'ProductComment'))) {
            if (!$this->currentObj)
                return false;
            $controller_type = Gdpr_tools::getControllerType();
            $_params = array();
            switch ($class) {
                case 'Order':
                    if ($controller_type == 'admin' && $this->currentObj->date_upd != $params['object']->date_upd) {
                        $_params = array(
                            'id_customer' => $params['object']->id_customer,
                            'details' => $params['object']->id,
                            'data_type' => 'ORD',
                        );
                    }
                    break;
                case 'Customer':
                    $details = $this->modifyFields(Customer::$definition['fields'], $this->currentObj, $params['object']);
                    $data_type = array();
                    $details = $this->getCustomerModify($details, $data_type);
                    $_params = $details ? array(
                        'id_customer' => $params['object']->id,
                        'details' => $this->displayModify($details),
                        'data_type' => count($data_type) > 1 ? 'GEN' : $data_type[0],
                    ) : false;
                    break;
                case 'CustomerAddress':
                    $details = $this->modifyFields(CustomerAddress::$definition['fields'], $this->currentObj, $params['object']);
                    $details = $this->getAddressModify($details, $params['object']);
                    $_params = $details ? array(
                        'id_customer' => $params['object']->id_customer,
                        'details' => $this->displayModify($details),
                        'data_type' => $details ? 'ADD' : '',
                    ) : false;
                    break;
            }
            if ($_params && ($class != 'Order' || ($class == 'Order' && !$this->orderUpdated && ($this->orderUpdated = true))))
                $this->dataUpdated($_params);
        }
    }

    public function modifyFields($fields, $oldObj, $newObj)
    {
        $details = array();
        if (isset($fields) && $fields)
            foreach ($fields as $key => $field) {
                if ($key == 'date_upd')
                    continue;
                switch ($field['type']) {
                    case ObjectModel::TYPE_INT:
                    case ObjectModel::TYPE_BOOL:
                        if ($oldObj->$key != $newObj->$key)
                            $details[$key] = $newObj->$key;
                    case ObjectModel::TYPE_FLOAT:
                        if (Tools::ps_round($oldObj->$key, _PS_PRICE_DISPLAY_PRECISION_) != Tools::ps_round($newObj->$key, _PS_PRICE_DISPLAY_PRECISION_))
                            $details[$key] = Tools::ps_round($newObj->$key, _PS_PRICE_DISPLAY_PRECISION_);
                        break;
                    case ObjectModel::TYPE_DATE:
                        $isVal = false;
                        if (!$newObj->$key && ($newObj->$key = '0000-00-00') && $newObj->$key != $oldObj->$key)
                            $isVal = true;
                        elseif (!$isVal && !$newObj->$key && ($newObj->$key = '0000-00-00 00:00:00') && $newObj->$key != $oldObj->$key)
                            $isVal = true;
                        elseif ($newObj->$key != $oldObj->$key)
                            $isVal = true;
                        if ($isVal)
                            $details[$key] = $newObj->$key;
                        break;
                    default:
                        if (trim($oldObj->$key) != trim($newObj->$key))
                            $details[$key] = trim($newObj->$key);
                        break;
                }
            }
        return $details;
    }

    public function displayModify($details)
    {
        $this->smarty->assign(array(
            'details' => $details,
        ));
        return $this->display(__FILE__, 'data-modify.tpl');
    }

    public function sendEmailToCustomer($customer, $id)
    {
        $accountModified = Configuration::get('ETS_GDPR_EMAIL_WHEN_MODIFIED') != '' ? explode(',', Configuration::get('ETS_GDPR_EMAIL_WHEN_MODIFIED')) : false;
        if (!$accountModified)
            return false;
        $controller_type = Gdpr_tools::getControllerType();
        if (($controller_type == 'admin' && in_array('admin', $accountModified)) || ($controller_type != 'admin' && in_array('customer', $accountModified))) {
            $mLog = Gdpr_presenter::getInstance()->getMLogs(array('itemId' => $id));
            if (!$mLog)
                return false;
            $text_content = $this->displaySmarty('EMAIL_MODIFIED', $controller_type, $mLog);
            if ($text_content)
                $this->sendMail($customer, $this->l('Data modified'), $text_content);
        }
    }

    public function sendMail($customer, $subject, $content, $template = 'default')
    {
        if (!is_object($customer) || !$content)
            return false;
        return Mail::Send(
            $this->context->language->id,
            $template,
            $subject,
            array(
                '{firstname}' => $customer->firstname,
                '{lastname}' => $customer->lastname,
                '{content}' => $content,
            ),
            $customer->email,
            $customer->firstname . ' ' . $customer->lastname,
            null,
            null,
            null,
            null,
            $this->getLocalPath() . 'mails/'
        );
    }

    public function getDevice()
    {
        return ($userAgent = new Gdpr_browser()) ? $userAgent->getBrowser() . ' ' . $userAgent->getVersion() . ' ' . $userAgent->getPlatform() : $this->l('Unknown');
    }

    public function exportCSV($params)
    {
        ob_get_clean();
        ob_start();
        $errors = array();
        if (!(isset($params['name'])) || !Gdpr_defines::getInstance()->get($params['name'])) {
            $errors[] = $this->l('Unknown error occurred. Please contact admin.');
        }
        $list = $params['name'];
        $define = Gdpr_defines::getInstance()->get($list);
        $config = isset($define['list']) && $define['list'] ? $define['list'] : [];
        $fields = isset($define['export_fields']) ? $define['export_fields'] : (isset($define['fields']) && $define['fields'] ? $define['fields'] : false);
        if (!$config || !$fields) {
            $errors[] = $this->l('Error configuring. Please contact admin.');
        }
        if (!$errors) {
            $data = Gdpr_presenter::getInstance()->get($config['list'], $params + array(
                    'export' => true,
                    'sort' => isset($config['orderBy']) && $config['orderBy'] ? $config['alias'] . '.' . $config['orderBy'] . ' ' . (isset($config['orderWay']) ? $config['orderWay'] : 'ASC') : '',
                ));
            $header = array();
            foreach ($fields as $field)
                $header[] = $field['title'];
            $csv = join("\t", $header) . "\r\n";
            if ($data) {
                foreach ($data as $row) {
                    if ($row) {
                        $cols = array();
                        foreach ($fields as $key => $field) {
                            if (isset($field['strip_tags']) && $field['strip_tags']) {
                                $cols[] = preg_replace('/(\s+){2,}/', ';', trim(strip_tags($row[$key])));
                            } elseif ($field['type'] == 'date' || $field['type'] == 'datetime')
                                $cols[] = Tools::displayDate($row[$key], $this->context->language->id, ($field['type'] != 'date' ? 1 : 0));
                            else
                                $cols[] = $row[$key];
                        }
                        if ($cols)
                            $csv .= join("\t", $cols) . "\r\n";
                    }
                }
            }
            $csv = chr(255) . chr(254) . mb_convert_encoding($csv, "UTF-16LE", "UTF-8");
            header("Content-type: application/x-msdownload");
            header("Content-disposition: csv; filename=" . date("Y-m-d") . "_" . (isset($config['list_id']) && $config['list_id'] ? $config['list_id'] : 'file') . ".csv; size=" . Tools::strlen($csv));
            echo $csv;
            exit();
        }
        return $errors;
    }

    public function getAddress($id_customer)
    {
        $customer = new Customer($id_customer);
        $addresses = $customer->getAddresses($customer->id_lang);
        foreach ($addresses as &$a) {
            $a['formatted'] = AddressFormat::generateAddress(new Address($a['id_address']), array(), Module::getInstanceByName('ets_gdpr')->displayText('', 'br', ''));
        }
        return $addresses;
    }

    public function displayRatings($params)
    {
        if (!(isset($params['grade'])))
            return '--';
        $this->smarty->assign(array(
            'grade' => $params['grade'],
            'img_dir' => Tools::getShopDomainSsl(true) . $this->_path . 'views/img/',
            'export' => isset($params['export']) && $params['export'] ? true : false,
            'is_admin' => Gdpr_tools::getControllerType() != 'admin' ? false : true,
        ));
        return $this->display(__FILE__, 'ratings.tpl');
    }

    public function isPDFs($deletions, $type)
    {
        if (!$deletions)
            return false;
        elseif ($deletions == 'ALL')
            return true;
        elseif (in_array($type, $deletions))
            return true;
        return false;
    }

    public function processGeneratePDF($id_customer, &$errors)
    {
        $data_to_pdf = false;
        if (($id = Tools::getValue('id_ets_gdpr_deletion', false)) && ($deletion = new Gdpr_deletion($id)))
            $data_to_pdf = $deletion->data_to_delete;
        elseif ($data_to_view = Tools::getValue('data_to_view', false))
            $data_to_pdf = $data_to_view;
        $deletions = $data_to_pdf ? ($data_to_pdf != 'ALL' ? ($data_to_pdf ? explode(',', $data_to_pdf) : false) : 'ALL') : false;
        $collection = array();
        if ($this->isPDFs($deletions, 'GEN')) {
            $collection['customer'] = array(
                'object' => new Customer($id_customer),
                'title' => $this->l('General information'),
                'template' => 'customer'
            );
        }
        if ($this->isPDFs($deletions, 'ADD')) {
            $collection['address'] = array(
                'object' => $this->getAddress($id_customer),
                'title' => $this->l('Address'),
                'template' => 'address'
            );
        }
        if ($this->isPDFs($deletions, 'ORD')) {
            $collection['order'] = array(
                'object' => Gdpr_presenter::getInstance()->getOrders(array('export' => true, 'id_customer' => $id_customer)),
                'title' => $this->l('Orders'),
                'template' => 'order'
            );
        }
        if (!$this->is17 && $this->isPDFs($deletions, 'REV') && (Module::isEnabled('productcomments') || Gdpr_deletion::isReviews())) {
            $collection['review'] = array(
                'object' => Gdpr_presenter::getInstance()->getReviews(array('id_customer' => $id_customer, 'export' => true)),
                'title' => $this->l('Product reviews'),
                'template' => 'reviews'
            );
        }
        if ($this->isPDFs($deletions, 'MES')) {
            $collection['message'] = array(
                'object' => Gdpr_presenter::getInstance()->getContactMessages(array('id_customer' => (int)$id_customer)),
                'title' => $this->l('Contact messages'),
                'template' => 'message'
            );
        }
        if ($this->isPDFs($deletions, 'SUB')) {
            $customer = new Customer($id_customer);
            $collection['subscribed'] = array(
                'object' => array('newsletter' => $customer->newsletter, 'optin' => $customer->optin),
                'title' => $this->l('Subscriptions'),
                'template' => 'subscriber'
            );
        }
        if (Gdpr_tools::getControllerType() == 'admin') {
            if ($this->isPDFs($deletions, 'MOD'))
                $collection['modified'] = array(
                    'object' => Gdpr_presenter::getInstance()->getMLogs(array('id_customer' => (int)$id_customer, 'export' => true)),
                    'title' => $this->l('Data modification log'),
                    'template' => 'modified-log'
                );
            if ($this->isPDFs($deletions, 'LOG'))
                $collection['login'] = array(
                    'object' => Gdpr_presenter::getInstance()->getMLogs(array('id_customer' => (int)$id_customer, 'export' => true)),
                    'title' => $this->l('Login log'),
                    'template' => 'login-log'
                );
        }
        if (!count($collection)) {
            $errors[] = $this->l('No data was found.');
        } else
            $this->generatePDF($collection, Gdpr_PDF::GDPR_TEMPLATE_ORDER);
    }

    public function generatePDF($object, $template)
    {
        $pdf = new Gdpr_PDF($object, $template, Context::getContext()->smarty);
        $pdf->render();
    }

    public function displayText($content = null, $tag, $class = null, $id = null, $href = null, $blank = false, $src = null, $alt = null, $name = null, $value = null, $type = null, $data_id_product = null, $rel = null, $attr_datas = null)
    {
        $this->smarty->assign(
            array(
                'content' => $content,
                'tag' => $tag,
                'class' => $class,
                'id' => $id,
                'href' => $href,
                'blank' => $blank,
                'src' => $src,
                'alt' => $alt,
                'name' => $name,
                'value' => $value,
                'type' => $type,
                'data_id_product' => $data_id_product,
                'attr_datas' => $attr_datas,
                'rel' => $rel,
            )
        );
        return $this->display(__FILE__, 'html.tpl');
    }
}