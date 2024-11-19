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

class Gdpr_defines extends Gdpr_translate
{
    const name = 'ets_gdpr';
    const PENDING = 'PENDING';
    const APPROVED = 'APPROVED';
    const DECLINED = 'DECLINED';
    const WITHDRAW = 'WITHDRAW';
    const AUTO = 'AUTO';

    public function get($prop)
    {
        if (!$prop || !Validate::isConfigName($prop)) {
            return false;
        }
        switch ($prop) {
            case 'groups':
                return $this->getGroups();
            case 'dataTypes':
                return $this->getDataTypes();
            case 'statusDeletion':
                return $this->getStatusDeletion();
            case 'general':
                return $this->getGeneral();
            case 'notice':
                return $this->getNotice();
            case 'sendEmail':
                return $this->getSendEmail();
            case 'customer_privileges':
                return $this->getCustomerPrivileges();
            case 'design':
                return $this->getDesign();
            case 'not_email':
                return $this->getNotEmail();
            case 'policy':
                return $this->getPolicy();
            case 'message':
                return $this->getMessage();
            case 'configTabs':
                return $this->getConfigTabs();
            case 'configFrontTabs':
                return $this->getConfigFrontTabs();
            case 'typeCharts':
                return $this->getTypeCharts();
            case 'notice_fields':
                return $this->getNoticeFields();
            case 'pending_fields':
                return $this->getPendingFields();
            case 'approved_fields':
                return $this->getApprovedFields();
            case 'declined_fields':
                return $this->getDeclinedFields();
            case 'withdraw_fields':
                return $this->getWithdrawFields();
            case 'mlog_fields':
                return $this->getModifiedLogFields();
            case 'llog_fields':
                return $this->getLoginLogFields();
            case 'mes_fields':
                return $this->getContactMessageFields();
            case 'order_fields':
                return $this->getOrderFields();
            case 'reviews_fields':
                return $this->getReviewsFields();
            case 'log_fields':
                return $this->getLogFields();
            default:
                return false;
        }
    }

    private $groups = array();

    public function getGroups()
    {
        if (!$this->groups) {
            $this->groups = Group::getGroups($this->context->language->id, $this->context->shop->id);
        }
        return $this->groups;
    }

    private $dataTypes = array();

    public function getDataTypes()
    {
        if (!$this->dataTypes) {
            $this->dataTypes = array(
                'ALL' => array(
                    'name' => $this->l('Entire account data'),
                    'id_option' => 'ALL',
                ),
                'ADD' => array(
                    'name' => $this->l('Addresses'),
                    'id_option' => 'ADD',
                    'tmp' => 'address',
                    'label' => $this->l('My address'),
                ),
                'ORD' => array(
                    'name' => $this->l('Orders'),
                    'id_option' => 'ORD',
                    'tmp' => 'orders',
                    'label' => $this->l('My orders'),
                ),
                'MES' => array(
                    'name' => $this->l('Contact messages'),
                    'id_option' => 'MES',
                    'tmp' => 'messages',
                    'label' => $this->l('Contact messages'),
                ),
                'REV' => array(
                    'name' => $this->l('Product reviews'),
                    'id_option' => 'REV',
                    'tmp' => 'reviews',
                    'label' => $this->l('Product reviews'),
                ),
                'SUB' => array(
                    'name' => $this->l('Subscription statuses'),
                    'id_option' => 'SUB',
                    'tmp' => 'subscriptions',
                    'label' => $this->l('My subscriptions'),
                ),
                'MOD' => array(
                    'name' => $this->l('Data modification log'),
                    'id_option' => 'MOD',
                ),
                'LOG' => array(
                    'name' => $this->l('Login log'),
                    'id_option' => 'LOG',
                ),
            );
            if ($this->module->is17)
                unset($this->dataTypes['REV']);
        }
        return $this->dataTypes;
    }

    private $statusDeletion = array();

    public function getStatusDeletion()
    {
        if (!$this->statusDeletion) {
            $this->statusDeletion = array(
                'PEN' => array(
                    'name' => $this->l('Pending'),
                    'id_option' => Gdpr_defines::PENDING,
                ),
                'APP' => array(
                    'name' => $this->l('Approved'),
                    'id_option' => Gdpr_defines::APPROVED,
                ),
                'DEC' => array(
                    'name' => $this->l('Declined'),
                    'id_option' => Gdpr_defines::DECLINED,
                ),
                'WIT' => array(
                    'name' => $this->l('Withdraw'),
                    'id_option' => Gdpr_defines::WITHDRAW,
                ),
                'AUT' => array(
                    'name' => $this->l('Customer delete'),
                    'id_option' => Gdpr_defines::AUTO,
                ),
            );
        }
        return $this->statusDeletion;
    }

    private $general = array();

    public function getGeneral()
    {
        if (!$this->general) {
            $this->general = array(
                'form' => array(
                    'legend' => array(
                        'title' => $this->l('General settings'),
                        'icon' => 'icon-AdminAdmin'
                    ),
                    'input' => array(),
                    'submit' => array(
                        'title' => $this->l('Save'),
                    ),
                    'name' => 'general',
                ),
                'configs' => array(
                    'ETS_GDPR_NOTIFY_WHEN' => array(
                        'label' => $this->l('Request GDPR acceptance when'),
                        'type' => 'gdpr_checkbox',
                        'values' => array(
                            'ALL' => array(
                                'id_option' => 'ALL',
                                'name' => $this->l('Select all'),
                            ),
                            'ONSITE' => array(
                                'id_option' => 'ONSITE',
                                'name' => $this->l('Customer lands on website (display a GDPR notification popup)'),
                            ),
                            'REGISTER' => array(
                                'id_option' => 'REGISTER',
                                'name' => $this->l('Customer registers new account (on customer registration page)'),
                            ),
                            'LOGIN' => array(
                                'id_option' => 'LOGIN',
                                'name' => $this->l('Log into account (on account login page)'),
                            ),
                            'SUBSCRIBE' => array(
                                'id_option' => 'SUBSCRIBE',
                                'name' => $this->l('Customer subscribes to newsletter (on newsletter subscription form)'),
                            ),
                            'CONTACT' => array(
                                'id_option' => 'CONTACT',
                                'name' => $this->l('Customer sends a contact message (on contact page)'),
                            ),
                            'RECEIVE' => array(
                                'id_option' => 'RECEIVE',
                                'name' => $this->l('Register to receive in-stock notification (on product details page)'),
                            ),
                        ),
                        'required' => true,
                        'default' => 'ONSITE,REGISTER',
                    ),
                    'ETS_GDPR_GROUP_TO_SEE' => array(
                        'label' => $this->l('Customer groups to see GDPR acceptance request'),
                        'type' => 'gdpr_group',
                        'values' => $this->getGroups(),
                        'col' => 4,
                        'default' => 'ALL',
                    ),
                    'ETS_GDPR_NOTICES_SHOW_AGAIN' => array(
                        'label' => $this->l('Do not display GDPR notices if a registered customer has accepted the notices in the past'),
                        'type' => 'switch',
                        'default' => 1,
                    ),
                    'ETS_GDPR_DELETION_TYPE' => array(
                        'label' => $this->l('What to do when customer requests a deletion of their data'),
                        'type' => 'gdpr_radio',
                        'values' => array(
                            array(
                                'id_option' => 'COMPLETE',
                                'name' => $this->l('Delete the data completely')
                            ),
                            array(
                                'id_option' => 'UNDEFINED',
                                'name' => $this->l('Update the data with "Undefined" value')
                            ),
                        ),
                        'default' => 'COMPLETE',
                    ),
                    'ETS_GDPR_REQUIRE_APPROVAL' => array(
                        'label' => $this->l('Ask customer to send an approval request to admin for any deletion of data'),
                        'type' => 'switch',
                        'default' => 1,
                    ),
                    'ETS_GDPR_WITHDRAW_REQUEST' => array(
                        'label' => $this->l('Allow customer to withdraw deletion request'),
                        'type' => 'switch',
                        'default' => 1,
                    ),
                    'ETS_GDPR_ENABLE_LOGIN_LOG' => array(
                        'label' => $this->l('Save login logs'),
                        'type' => 'switch',
                        'default' => 0,
                        'desc' => $this->l('Save a log with customer IP, web browser type, login time, etc. everytime customer account is logged in.')
                    ),
                    'ETS_GDPR_ENABLE_MOD_LOG' => array(
                        'label' => $this->l('Save data modification logs'),
                        'type' => 'switch',
                        'default' => 1,
                        'desc' => $this->l('Save a log with details of data modified everytime customer data is modified')
                    ),
                )
            );
        }
        return $this->general;
    }

    private $notice = array();

    public function getNotice()
    {
        if (!$this->notice) {
            $this->notice = array(
                'form' => array(
                    'legend' => array(
                        'title' => Tools::getValue('id_ets_gdpr_notice') ? $this->l('Edit GDPR notice') : $this->l('Add GDPR notice'),
                        'icon' => 'icon-bell-o'
                    ),
                    'input' => array(),
                    'submit' => array(
                        'title' => $this->l('Save'),
                    ),
                    'buttons' => array(
                        'back' => array(
                            'href' => '#',
                            'title' => $this->l('Back to list'),
                            'icon' => 'process-icon-back',
                        ),
                        'save_and_stay' => array(
                            'type' => 'submit',
                            'name' => 'saveAndStayNotice',
                            'title' => $this->l('Save and stay'),
                            'icon' => 'process-icon-save',
                            'class' => 'pull-right',
                        ),
                    ),
                    'name' => 'notice',
                ),
                'configs' => array(
                    'id_shop' => array(
                        'label' => $this->l('ID shop'),
                        'type' => 'hidden',
                        'default' => (int)$this->context->shop->id,
                        'validate' => 'isUnsignedInt',
                    ),
                    'title' => array(
                        'label' => $this->l('Title'),
                        'type' => 'text',
                        'lang' => true,
                        'required' => true,
                        'validate' => 'isCleanHtml'
                    ),
                    'description' => array(
                        'label' => $this->l('Description'),
                        'type' => 'textarea',
                        'cols' => 30,
                        'rows' => 5,
                        'lang' => true,
                        'required' => true,
                        'autoload_rte' => true,
                        'validate' => 'isCleanHtml',
                    ),
                    'display_to' => array(
                        'label' => $this->l('Display to'),
                        'type' => 'gdpr_group',
                        'values' => $this->getGroups(),
                        'validate' => 'isString',
                        'default' => 'ALL',
                        'desc' => $this->l('You can specify the customer groups that you want to display this GDPR notice to'),
                    ),
                    'enabled' => array(
                        'label' => $this->l('Enabled'),
                        'type' => 'switch',
                        'default' => 1,
                        'validate' => 'isBool',
                    ),
                    'position' => array(
                        'label' => $this->l('Position'),
                        'type' => 'hidden',
                        'default' => '0',
                        'validate' => 'isUnsignedInt',
                    ),
                )
            );
        }
        return $this->notice;
    }

    private $sendEmail = array();

    public function getSendEmail()
    {
        if (!$this->sendEmail) {
            $this->sendEmail = array(
                'form' => array(
                    'legend' => array(
                        'title' => $this->l('Send an email to customer'),
                        'icon' => 'icon-envelope'
                    ),
                    'input' => array(),
                    'submit' => array(
                        'title' => $this->l('Send'),
                        'icon' => 'icon-envelope'
                    ),
                    'buttons' => array(
                        'back' => array(
                            'href' => '#',
                            'title' => $this->l('Cancel'),
                            'icon' => 'icon-remove',
                            'class' => 'gdpr_btn_cancel'
                        )
                    ),
                    'name' => 'send',
                ),
                'configs' => array(
                    'id_customer' => array(
                        'label' => $this->l('Customer ID'),
                        'type' => 'hidden',
                        'default' => 0,
                        'validate' => 'isUnsignedInt'
                    ),
                    'subject' => array(
                        'label' => $this->l('Subject'),
                        'type' => 'text',
                        'required' => true,
                        'validate' => 'isCleanHtml'
                    ),
                    'content' => array(
                        'label' => $this->l('Email content'),
                        'type' => 'textarea',
                        'cols' => 30,
                        'rows' => 5,
                        'required' => true,
                        'validate' => 'isCleanHtml'
                    ),
                )
            );
        }
        return $this->sendEmail;
    }

    private $customer_privileges = array();

    public function getCustomerPrivileges()
    {
        if (!$this->customer_privileges) {
            $this->customer_privileges = array(
                'form' => array(
                    'legend' => array(
                        'title' => $this->l('Customer privileges'),
                        'icon' => 'icon-AdminAdmin'
                    ),
                    'input' => array(),
                    'submit' => array(
                        'title' => $this->l('Save'),
                    ),
                    'name' => 'customer_privileges',
                ),
                'configs' => array(
                    'ETS_GDPR_ALLOW_VIEW' => array(
                        'label' => $this->l('View personal data that is being shared'),
                        'type' => 'switch',
                        'default' => 1,
                        'validate' => 'isBool',
                    ),
                    'ETS_GDPR_DATA_TO_VIEW' => array(
                        'label' => $this->l('Data to view'),
                        'type' => 'gdpr_checkbox',
                        'values' => array(
                                'ALL' => array(
                                    'name' => $this->l('All'),
                                    'id_option' => 'ALL',
                                ),
                                'GEN' => array(
                                    'name' => $this->l('General information'),
                                    'id_option' => 'GEN',
                                )
                            ) + $this->getDataTypes(),
                        'default' => 'ALL',
                        'validate' => 'isCleanHtml',
                    ),
                    'ETS_GDPR_ALLOW_UDP_NEWSLETTER' => array(
                        'label' => $this->l('Change subscription status'),
                        'type' => 'switch',
                        'default' => 1,
                        'validate' => 'isBool',
                    ),
                    'ETS_GDPR_ALLOW_DOWNLOAD' => array(
                        'label' => $this->l('Download personal data'),
                        'type' => 'switch',
                        'default' => 1,
                        'validate' => 'isBool',
                    ),
                    'ETS_GDPR_ALLOW_DELETE' => array(
                        'label' => $this->l('Delete personal data'),
                        'type' => 'switch',
                        'default' => 1,
                        'validate' => 'isBool',
                    ),
                    'ETS_GDPR_CAN_DELETE' => array(
                        'label' => $this->l('Data can delete'),
                        'type' => 'gdpr_checkbox',
                        'values' => array(
                                'ALL' => array(
                                    'name' => $this->l('Entire account data'),
                                    'id_option' => 'ALL',
                                ),
                            ) + $this->getDataTypes(),
                        'required' => true,
                        'default' => 'ALL',
                        'validate' => 'isCleanHtml',
                    ),
                )
            );
        }
        return $this->customer_privileges;
    }

    private $design = array();

    public function getDesign()
    {
        if (!$this->design) {
            $this->design = array(
                'form' => array(
                    'legend' => array(
                        'title' => '',
                        'icon' => 'icon-AdminAdmin'
                    ),
                    'input' => array(),
                    'submit' => array(
                        'title' => $this->l('Save'),
                    ),
                    'name' => 'design',
                ),
                'configs' => array(
                    'ETS_GDPR_WELCOME_MESSAGE' => array(
                        'label' => $this->l('Welcome message'),
                        'type' => 'textarea',
                        'cols' => 30,
                        'rows' => 5,
                        'lang' => true,
                        'required' => true,
                        'default' => 'By continuing to browse this website, You’re agreeing to our use of cookie and your personal data according to EU GDPR.',
                        'validate' => 'isCleanHtml',
                    ),
                    'ETS_GDPR_ACCEPT_BUTTON_LABEL' => array(
                        'label' => $this->l('Acceptance button label'),
                        'type' => 'text',
                        'lang' => true,
                        'required' => true,
                        'default' => 'I accept',
                        'validate' => 'isCleanHtml',
                    ),
                    'ETS_GDPR_BUTTON_BESIDE_MESSAGE' => array(
                        'label' => $this->l('Display acceptance button beside welcome message'),
                        'type' => 'switch',
                        'default' => 1,
                        'validate' => 'isBool',
                    ),
                    'ETS_GDPR_NOT_ACCEPT_LABEL' => array(
                        'label' => $this->l('Do not accept label'),
                        'type' => 'text',
                        'lang' => true,
                        'default' => 'No, I do not accept',
                        'validate' => 'isCleanHtml',
                    ),
                    'ETS_GDPR_VIEW_MORE_LABEL' => array(
                        'label' => $this->l('View more info label'),
                        'type' => 'text',
                        'lang' => true,
                        'required' => true,
                        'default' => 'View more details',
                        'desc' => $this->l('When customer click on this, a popup presenting GDPR notice will display to them'),
                        'validate' => 'isCleanHtml',
                    ),
                    'ETS_GDPR_VIEW_MORE_COLOR' => array(
                        'label' => $this->l('View more info text color'),
                        'type' => 'color',
                        'default' => '#2fb5d2',
                        'validate' => 'isColor'
                    ),
                    'ETS_GDPR_CONFIRMATION_LABEL' => array(
                        'label' => $this->l('Confirmation checkbox label on registration page'),
                        'type' => 'text',
                        'lang' => true,
                        'default' => 'I agree with the use of cookie and personal data according to EU GDPR',
                        'validate' => 'isCleanHtml',
                    ),
                    'ETS_GDPR_NOTICE_TITLE' => array(
                        'label' => $this->l('GDPR notice title'),
                        'type' => 'text',
                        'lang' => true,
                        'default' => 'Our privacy policy',
                        'validate' => 'isCleanHtml',
                    ),
                    'ETS_GDPR_NOTICE_TOP' => array(
                        'label' => $this->l('General notice (Top)'),
                        'type' => 'textarea',
                        'cols' => 30,
                        'rows' => 5,
                        'lang' => true,
                        'autoload_rte' => true,
                        'default' => $this->module->displaySmarty('NOTICE_TOP'),
                        'desc' => $this->l('Display before GDPR notices'),
                        'validate' => 'isCleanHtml',
                    ),
                    'ETS_GDPR_NOTICE_BOTTOM' => array(
                        'label' => $this->l('General notice (Bottom)'),
                        'type' => 'textarea',
                        'cols' => 30,
                        'rows' => 5,
                        'lang' => true,
                        'autoload_rte' => true,
                        'default' => $this->module->displaySmarty('NOTICE_BOTTOM'),
                        'desc' => $this->l('Display after GDPR notices'),
                        'validate' => 'isCleanHtml',
                    ),
                    'ETS_GDPR_BUTTON_BG_COLOR' => array(
                        'label' => $this->l('Acceptance button background color'),
                        'type' => 'color',
                        'default' => '#2fb5d2',
                        'validate' => 'isColor',
                    ),
                    'ETS_GDPR_BUTTON_BG_HOVER_COLOR' => array(
                        'label' => $this->l('Acceptance button background hover color'),
                        'type' => 'color',
                        'default' => '#ffffff',
                        'validate' => 'isColor',
                    ),
                    'ETS_GDPR_BOTTON_TEXT_COLOR' => array(
                        'label' => $this->l('Acceptance button text color'),
                        'type' => 'color',
                        'default' => '#ffffff',
                        'validate' => 'isColor',
                    ),
                    'ETS_GDPR_NUMBER_NOTICES' => array(
                        'label' => $this->l('Number GDPR notices'),
                        'type' => 'switch',
                        'default' => 1,
                    ),
                    'ETS_GDPR_NUMBER_BG_COLOR' => array(
                        'label' => $this->l('Background color for the numbers'),
                        'type' => 'color',
                        'default' => '#2fb5d2',
                        'validate' => 'isColor',
                    ),
                    'ETS_GDPR_NUMBER_TEXT_COLOR' => array(
                        'label' => $this->l('Text color for numbers'),
                        'type' => 'color',
                        'default' => '#ffffff',
                        'validate' => 'isColor',
                    ),
                    'ETS_GDPR_MESSAGE_BG_COLOR' => array(
                        'label' => $this->l('Welcome message background color'),
                        'type' => 'color',
                        'default' => '#000000',
                        'validate' => 'isColor',
                    ),
                    'ETS_GDPR_MESSAGE_TEXT_COLOR' => array(
                        'label' => $this->l('Welcome message text color'),
                        'type' => 'color',
                        'default' => '#ffffff',
                        'validate' => 'isColor',
                    ),
                    'ETS_GDPR_MESSAGE_BG_OPACITY' => array(
                        'label' => $this->l('Welcome message background opacity'),
                        'type' => 'range',
                        'col' => 2,
                        'default' => 8,
                        'validate' => 'isUnsignedInt',
                    ),
                    'ETS_GDPR_WELCOME_TEMPLATE' => array(
                        'label' => $this->l('Welcome message type'),
                        'type' => 'gdpr_radio',
                        'values' => array(
                            array(
                                'id_option' => 'top',
                                'name' => $this->l('Full-width bar at website top'),
                            ),
                            array(
                                'id_option' => 'bottom',
                                'name' => $this->l('Full-width bar at website bottom'),
                            ),
                            array(
                                'id_option' => 'bottom_left',
                                'name' => $this->l('Rectangle notification box at bottom left'),
                            ),
                            array(
                                'id_option' => 'bottom_right',
                                'name' => $this->l('Rectangle notification box at bottom right'),
                            ),
                        ),
                        'default' => 'bottom',
                        'validate' => 'isCleanHtml',
                    ),
                    'ETS_GDPR_WELCOME_BOX_WIDTH' => array(
                        'label' => $this->l('Notification box max-width'),
                        'type' => 'text',
                        'col' => 3,
                        'suffix' => $this->l('px'),
                        'default' => '400',
                        'validate' => 'isUnsignedInt',
                    ),
                    'ETS_GDPR_WARNING_PERSONAL_PAGE' => array(
                        'label' => $this->l('Warning message on personal data page'),
                        'type' => 'textarea',
                        'cols' => 30,
                        'rows' => 5,
                        'lang' => true,
                        'default' => 'This page displays all your personal data that you are sharing with us. We guarantee to only use your personal data to serve you better and improve our customer service. Your data is fully protected by our security system, it will never be shared with any third party for any purpose',
                        'validate' => 'isCleanHtml',
                    ),
                    'ETS_GDPR_WARNING_DELETION_PAGE' => array(
                        'label' => $this->l('Warning message on data deletion page'),
                        'type' => 'textarea',
                        'cols' => 30,
                        'rows' => 5,
                        'lang' => true,
                        'default' => 'In an compliance with GDPR (general data protection regular), we give you an ability to delete a part or all your data from our system. However, please be careful, this action can’t be undone! Delete personal data is not recommended, you will be no longer able to access the deleted data anymore. If you decide to delete "entire account data", you will not be able to access the website using your old account (as it has been completely deleted)',
                        'validate' => 'isCleanHtml',
                    ),
                    'ETS_GDPR_REDIRECT_URL' => array(
                        'label' => $this->l('Redirect customer to this URL if they decline to accept the GDPR notices'),
                        'type' => 'text',
                        'lang' => true,
                        'desc' => $this->l('Leave blank to keep customer stays on current page'),
                        'validate' => 'isCleanHtml',
                    ),
                )
            );
        }
        return $this->design;
    }

    private $not_email = array();

    public function getNotEmail()
    {
        if (!$this->not_email) {
            $this->not_email = array(
                'form' => array(
                    'legend' => array(
                        'title' => $this->l('Email alerts'),
                        'icon' => 'icon-AdminAdmin'
                    ),
                    'input' => array(),
                    'submit' => array(
                        'title' => $this->l('Save'),
                    ),
                    'name' => 'not_email',
                ),
                'configs' => array(
                    'ETS_GDPR_EMAIL_WHEN_MODIFIED' => array(
                        'label' => $this->l('Send email to customer when their account is modified'),
                        'type' => 'gdpr_checkbox',
                        'values' => array(
                            array(
                                'id_option' => 'admin',
                                'name' => $this->l('By Admin'),
                            ),
                            array(
                                'id_option' => 'customer',
                                'name' => $this->l('By customer'),
                            ),
                        ),
                        'default' => 'customer',
                    ),
                    'ETS_GDPR_EMAIL_WHEN_LOGGED_IN' => array(
                        'label' => $this->l('Send email to customer when their account is logged in'),
                        'type' => 'switch',
                        'default' => 0,
                        'validate' => 'isBool'
                    ),
                    'ETS_GDPR_EMAIL_WHEN_REQUIRE' => array(
                        'label' => $this->l('Send an email to admin when customer submits a deletion request'),
                        'type' => 'switch',
                        'default' => 1,
                        'rel' => Configuration::get('ETS_GDPR_REQUIRE_APPROVAL') ? 1 : 0,
                        'validate' => 'isBool'
                    ),
                    'ETS_GDPR_EMAIL_WHEN_DELETE' => array(
                        'label' => $this->l('Send notification email to customer when delete data'),
                        'type' => 'switch',
                        'default' => 1,
                        'validate' => 'isBool'
                    ),
                    'ETS_GDPR_DELETION_SUBJECT' => array(
                        'label' => $this->l('Deletion email subject'),
                        'type' => 'text',
                        'lang' => true,
                        'default' => 'Your data was deleted from our website',
                        'validate' => 'isCleanHtml',
                    ),
                    'ETS_GDPR_DELETION_EMAIL' => array(
                        'label' => $this->l('Deletion email content'),
                        'type' => 'textarea',
                        'cols' => 30,
                        'rows' => 3,
                        'lang' => true,
                        'autoload_rte' => true,
                        'default' => $this->module->displaySmarty('ETS_GDPR_DELETION_EMAIL'),
                        'desc' => $this->module->displaySmarty('DESC_TAG'),
                        'validate' => 'isCleanHtml',
                    ),
                    'ETS_GDPR_EMAIL_WHEN_DECLINE' => array(
                        'label' => $this->l('Send notification email to customer when decline an data deletion request'),
                        'type' => 'switch',
                        'default' => 1,
                    ),
                    'ETS_GDPR_REFUSAL_SUBJECT' => array(
                        'label' => $this->l('Refusal email subject'),
                        'type' => 'text',
                        'lang' => true,
                        'default' => 'Your data deletion request is declined',
                        'validate' => 'isCleanHtml',
                    ),
                    'ETS_GDPR_REFUSAL_EMAIL' => array(
                        'label' => $this->l('Refusal email content'),
                        'type' => 'textarea',
                        'cols' => 30,
                        'rows' => 3,
                        'lang' => true,
                        'autoload_rte' => true,
                        'default' => $this->module->displaySmarty('ETS_GDPR_REFUSAL_EMAIL'),
                        'desc' => $this->module->displaySmarty('DESC_TAG'),
                        'validate' => 'isCleanHtml',
                    ),
                )
            );
        }
        return $this->not_email;
    }

    private $policy = array();

    public function getPolicy()
    {
        if (!$this->policy) {
            $this->policy = array(
                'form' => array(
                    'legend' => array(
                        'title' => '',
                        'icon' => 'icon-legal'
                    ),
                    'input' => array(),
                    'submit' => array(
                        'title' => $this->l('Save'),
                    ),
                    'name' => 'policy',
                ),
                'configs' => array(
                    'ETS_GDPR_POLICY_ENABLED' => array(
                        'label' => $this->l('Enabled'),
                        'type' => 'switch',
                        'default' => 1,
                        'validate' => 'isBool',
                    ),
                    'ETS_GDPR_POLICY_BTN_TITLE' => array(
                        'label' => $this->l('Button title'),
                        'type' => 'text',
                        'lang' => true,
                        'required' => true,
                        'default' => 'Data usage policy',
                        'validate' => 'isCleanHtml',
                    ),
                    'ETS_GDPR_POLICY_BY_POPUP' => array(
                        'label' => $this->l('Open data usage policy by popup?'),
                        'type' => 'switch',
                        'default' => 1,
                        'validate' => 'isBool',
                    ),
                    'ETS_GDPR_POLICY_PAGE_URL' => array(
                        'label' => $this->l('Data usage policy page URL'),
                        'type' => 'text',
                        'lang' => true,
                        'required' => true,
                        'validate' => 'isCleanHtml',
                    ),
                )
            );
        }
        return $this->policy;
    }

    private $message = array();

    public function getMessage()
    {
        if (!$this->message) {
            $this->message = array(
                'form' => array(
                    'legend' => array(
                        'title' => '',
                        'icon' => 'icon-legal'
                    ),
                    'input' => array(),
                    'submit' => array(
                        'title' => $this->l('Save'),
                    ),
                    'name' => 'message',
                ),
                'configs' => array(
                    'ETS_GDPR_MES_REGISTER' => array(
                        'label' => $this->l('Customer register new account (on customer registration page) '),
                        'type' => 'textarea',
                        'lang' => true,
                        'required' => true,
                        'placeholder' => $this->l('Message'),
                        'default' => 'I agree to the Terms of Use and Privacy Policy',
                        'validate' => 'isCleanHtml',
                    ),
                    'ETS_GDPR_MES_REGISTER_MORE' => array(
                        'label' => $this->l('Enabled "View more button"'),
                        'type' => 'switch',
                        'default' => 1,
                        'validate' => 'isBool',
                    ),
                    'ETS_GDPR_MES_LOGIN' => array(
                        'label' => $this->l('Log into account (on account login page)'),
                        'type' => 'textarea',
                        'lang' => true,
                        'required' => true,
                        'placeholder' => $this->l('Message'),
                        'default' => 'I agree to the Terms of Use and Privacy Policy',
                        'validate' => 'isCleanHtml',
                    ),
                    'ETS_GDPR_MES_LOGIN_MORE' => array(
                        'label' => $this->l('Enabled "View more button"'),
                        'type' => 'switch',
                        'default' => 1,
                        'validate' => 'isBool',
                    ),
                    'ETS_GDPR_MES_SUBSCRIBE' => array(
                        'label' => $this->l('Customer subscribe to newsletter (on newsletter subscription form)'),
                        'type' => 'textarea',
                        'lang' => true,
                        'required' => true,
                        'placeholder' => $this->l('Message'),
                        'default' => 'I agree to the Terms of Use and Privacy Policy',
                        'validate' => 'isCleanHtml',
                    ),
                    'ETS_GDPR_MES_SUBSCRIBE_MORE' => array(
                        'label' => $this->l('Enabled "View more button"'),
                        'type' => 'switch',
                        'default' => 1,
                        'validate' => 'isBool',
                    ),
                    'ETS_GDPR_MES_CONTACT' => array(
                        'label' => $this->l('Customer send a contact message (on contact page)'),
                        'type' => 'textarea',
                        'lang' => true,
                        'required' => true,
                        'placeholder' => $this->l('Message'),
                        'default' => 'I agree to the Terms of Use and Privacy Policy',
                        'validate' => 'isCleanHtml',
                    ),
                    'ETS_GDPR_MES_CONTACT_MORE' => array(
                        'label' => $this->l('Enabled "View more button"'),
                        'type' => 'switch',
                        'default' => 1,
                        'validate' => 'isBool',
                    ),
                    'ETS_GDPR_MES_RECEIVE' => array(
                        'label' => $this->l('Register to receive in-stock notification (on product details page) '),
                        'type' => 'textarea',
                        'lang' => true,
                        'required' => true,
                        'placeholder' => $this->l('Message'),
                        'default' => 'I agree to the Terms of Use and Privacy Policy',
                        'validate' => 'isCleanHtml',
                    ),
                    'ETS_GDPR_MES_RECEIVE_MORE' => array(
                        'label' => $this->l('Enabled "View more button"'),
                        'type' => 'switch',
                        'default' => 1,
                        'validate' => 'isBool',
                    ),
                )
            );
        }
        return $this->message;
    }

    private $configTabs = array();

    public function getConfigTabs()
    {
        if (!$this->configTabs) {
            $this->configTabs = array(
                'del' => array(
                    'name' => '',
                    'label' => $this->l('Deletion requests'),
                    'subTabs' => array(
                        'pen' => array(
                            'name' => 'pending',
                            'label' => $this->l('Pending requests'),
                            'render' => 'list',
                        ),
                        'app' => array(
                            'name' => 'approved',
                            'label' => $this->l('Approved requests'),
                            'render' => 'list',
                        ),
                        'dec' => array(
                            'name' => 'declined',
                            'label' => $this->l('Declined requests'),
                            'render' => 'list',
                        ),
                        'wit' => array(
                            'name' => 'withdraw',
                            'label' => $this->l('Withdrawal requests'),
                            'render' => 'list',
                        )
                    ),
                ),
                'gdp' => array(
                    'name' => 'gdpr_design',
                    'label' => $this->l('GDPR notification'),
                    'subTabs' => array(
                        'des' => array(
                            'name' => 'design',
                            'label' => $this->l('Popup settings'),
                            'is_conf' => true,
                            'render' => 'form',
                        ),
                        'not' => array(
                            'name' => 'notice',
                            'label' => $this->l('GDPR notices'),
                            'render' => 'list',
                        ),
                        'pol' => array(
                            'name' => 'policy',
                            'label' => $this->l('Data usage policy button'),
                            'is_conf' => true,
                            'render' => 'form',
                        ),
                        'mes' => array(
                            'name' => 'message',
                            'label' => $this->l('Messages'),
                            'is_conf' => true,
                            'render' => 'form',
                        ),
                    ),
                ),
                'cus' => array(
                    'name' => 'customer_privileges',
                    'label' => $this->l('Customer privileges'),
                    'is_conf' => true,
                    'render' => 'form',
                ),
                'log' => array(
                    'name' => 'logs',
                    'label' => $this->l('Access logs'),
                    'subTabs' => array(
                        'mlo' => array(
                            'name' => 'mlog',
                            'label' => $this->l('Modification log'),
                            'render' => 'list',
                        ),
                        'llo' => array(
                            'name' => 'llog',
                            'label' => $this->l('Login log'),
                            'render' => 'list',
                        ),
                    ),
                ),
                'not' => array(
                    'name' => 'not_email',
                    'label' => $this->l('Email alerts'),
                    'is_conf' => true,
                    'render' => 'form',
                ),
                'gen' => array(
                    'name' => 'general',
                    'label' => $this->l('General settings'),
                    'is_conf' => true,
                    'render' => 'form',
                ),
                'sta' => array(
                    'name' => 'statistic',
                    'label' => $this->l('Statistics'),
                    'subTabs' => array(
                        'cha' => array(
                            'name' => 'chart',
                            'label' => $this->l('Chart'),
                            'render' => 'chart',
                        ),
                        'log' => array(
                            'name' => 'log',
                            'label' => $this->l('Logs'),
                            'render' => 'list',
                        ),
                    ),
                ),
            );
        }
        return $this->configTabs;
    }

    private $configFrontTabs = array();

    public function getConfigFrontTabs($tab = null)
    {
        if (!$this->configFrontTabs) {
            $this->configFrontTabs = array(
                'per' => array(
                    'name' => 'personal',
                    'label' => $this->l('My personal data'),
                ),
                'mod' => array(
                    'name' => 'mod_log',
                    'label' => $this->l('Data modification log'),
                ),
                'log' => array(
                    'name' => 'login_log',
                    'label' => $this->l('Login log'),
                ),
                'del' => array(
                    'name' => 'del_data',
                    'label' => $this->l('Delete my data'),
                ),
            );
        }
        return $tab !== null ? (isset($this->configFrontTabs[$tab]) ? $this->configFrontTabs[$tab] : []) : $this->configFrontTabs;
    }

    private $typeCharts = array();

    public function getTypeCharts()
    {
        if (!$this->typeCharts) {
            $this->typeCharts = array(
                'acc' => array(
                    'id' => 'accept',
                    'label' => $this->l('Accept Privacy policy'),
                    'val' => 1,
                ),
                'not' => array(
                    'id' => 'not_accept',
                    'label' => $this->l('Do not accept Privacy policy'),
                    'val' => 0,
                ),
            );
        }
        return $this->typeCharts;
    }

    private $notice_fields = array();

    public function getNoticeFields()
    {
        if (!$this->notice_fields) {
            $this->notice_fields = array(
                'list' => array(
                    'title' => $this->l('GDPR notices'),
                    'icon' => 'icon-bell-o',
                    'actions' => array('add', 'edit', 'delete'),
                    'orderBy' => 'position',
                    'orderWay' => 'ASC',
                    'nb' => 'getNotices',
                    'list' => 'getNotices',
                    'no_link' => false,
                    'bulk_actions' => array(
                        'enableSelection' => array(
                            'text' => $this->l('Enable'),
                            'icon' => 'icon-power-off text-success'
                        ),
                        'disableSelection' => array(
                            'text' => $this->l('Disable'),
                            'icon' => 'icon-power-off text-danger'
                        ),
                        'divider' => array(
                            'text' => 'divider'
                        ),
                        'delete' => array(
                            'text' => $this->l('Delete'),
                            'icon' => 'icon-trash',
                            'confirm' => $this->l('Do you want to delete selected items?')
                        )
                    ),
                    'list_id' => 'ets_gdpr_notice',
                    'alias' => 'n',
                ),
                'fields' => array(
                    'id_ets_gdpr_notice' => array(
                        'title' => $this->l('ID'),
                        'align' => 'center',
                        'type' => 'int',
                        'class' => 'fixed-width-xs',
                    ),
                    'title' => array(
                        'title' => $this->l('Title'),
                        'type' => 'text',
                        'filter_key' => 'nl!title'
                    ),
                    'display_to' => array(
                        'title' => $this->l('Display to'),
                        'type' => 'text',
                        'orderby' => false,
                        'search' => false,
                    ),
                    'enabled' => array(
                        'title' => $this->l('Active'),
                        'type' => 'bool',
                        'active' => 'status',
                        'orderby' => false,
                        'class' => 'fixed-width-xs',
                        'align' => 'center',
                    ),
                    'position' => array(
                        'title' => $this->l('Sort order'),
                        'type' => 'int',
                        'position' => 'position',
                        'class' => 'fixed-width-xs',
                        'align' => 'center',
                    ),
                ),
            );
        }
        return $this->notice_fields;
    }

    private $pending_fields = array();

    public function getPendingFields()
    {
        if (!$this->pending_fields) {
            $this->pending_fields = array(
                'list' => array(
                    'title' => null,
                    'actions' => array('pending'),
                    'orderBy' => 'requested_date_time',
                    'orderWay' => 'DESC',
                    'nb' => 'getDeletions',
                    'list' => 'getDeletions',
                    'no_link' => true,
                    'bulk_actions' => array(
                        'approveSelection' => array(
                            'text' => $this->l('Approve'),
                            'icon' => 'icon-check',
                            'confirm' => $this->l('Do you want to approve selected items?')
                        ),
                        'declineSelection' => array(
                            'text' => $this->l('Decline'),
                            'icon' => 'icon-remove',
                            'confirm' => $this->l('Do you want to decline selected items?')
                        ),
                    ),
                    'list_id' => 'ets_gdpr_deletion',
                    'alias' => 'd',
                    'status' => Gdpr_defines::PENDING
                ),
                'fields' => array(
                    'id_customer' => array(
                        'title' => $this->l('Customer ID'),
                        'align' => 'center',
                        'type' => 'int',
                        'class' => 'fixed-width-xs',
                        'orderby' => false,
                        'search' => false,
                    ),
                    'customer_name' => array(
                        'title' => $this->l('Customer name'),
                        'type' => 'text',
                        'class' => 'gdpr-customer-name-form',
                        'orderby' => false,
                        'search' => false,
                    ),
                    'email' => array(
                        'title' => $this->l('Email'),
                        'type' => 'text',
                        'class' => 'gdpr-email-form',
                        'orderby' => false,
                        'search' => false,
                    ),
                    'data_to_delete' => array(
                        'title' => $this->l('Data want to delete'),
                        'type' => 'text',
                        'callback' => 'displayFieldDataType',
                        'callback_object' => $this->module,
                        'orderby' => false,
                        'search' => false,
                    ),
                    'requested_date_time' => array(
                        'title' => $this->l('Date of request'),
                        'type' => 'datetime',
                        'align' => 'center',
                        'orderby' => false,
                        'search' => false,
                    ),
                ),
            );
        }
        return $this->pending_fields;
    }

    private $approved_fields = array();

    public function getApprovedFields()
    {
        if (!$this->approved_fields) {
            $this->approved_fields = array(
                'list' => array(
                    'title' => null,
                    'actions' => array('viewcustomer'),
                    'orderBy' => 'action_taken_date_time',
                    'orderWay' => 'DESC',
                    'nb' => 'getDeletions',
                    'list' => 'getDeletions',
                    'no_link' => true,
                    'bulk_actions' => array(
                        'deleteApproved' => array(
                            'text' => $this->l('Delete'),
                            'icon' => 'icon-trash',
                            'confirm' => $this->l('Do you want to delete selected items?')
                        ),
                    ),
                    'list_id' => 'ets_gdpr_deletion',
                    'alias' => 'd',
                    'status' => Gdpr_defines::APPROVED
                ),
                'fields' => array(
                    'id_customer' => array(
                        'title' => $this->l('Customer ID'),
                        'align' => 'center',
                        'type' => 'int',
                        'class' => 'fixed-width-xs',
                        'orderby' => false,
                        'search' => false,
                    ),
                    'data_to_delete' => array(
                        'title' => $this->l('Data want to delete'),
                        'type' => 'text',
                        'callback' => 'displayFieldDataType',
                        'callback_object' => $this->module,
                        'orderby' => false,
                        'search' => false,
                    ),
                    'requested_date_time' => array(
                        'title' => $this->l('Date of request'),
                        'type' => 'datetime',
                        'align' => 'center',
                        'orderby' => false,
                        'search' => false,
                    ),
                    'action_taken_date_time' => array(
                        'title' => $this->l('Date of approval'),
                        'type' => 'datetime',
                        'align' => 'center',
                        'orderby' => false,
                        'search' => false,
                    ),
                ),
            );
        }
        return $this->approved_fields;
    }

    private $declined_fields = array();

    public function getDeclinedFields()
    {
        if (!$this->declined_fields) {

            $this->declined_fields = array(
                'list' => array(
                    'title' => $this->l('Declined requests'),
                    'actions' => array('viewcustomer'),
                    'orderBy' => 'action_taken_date_time',
                    'orderWay' => 'DESC',
                    'nb' => 'getDeletions',
                    'list' => 'getDeletions',
                    'no_link' => true,
                    'bulk_actions' => array(
                        'deleteDeclined' => array(
                            'text' => $this->l('Delete'),
                            'icon' => 'icon-trash',
                            'confirm' => $this->l('Do you want to delete selected items?')
                        ),
                    ),
                    'list_id' => 'ets_gdpr_deletion',
                    'alias' => 'd',
                    'status' => Gdpr_defines::DECLINED
                ),
                'fields' => array(
                    'id_customer' => array(
                        'title' => $this->l('Customer ID'),
                        'align' => 'center',
                        'type' => 'int',
                        'class' => 'fixed-width-xs',
                        'orderby' => false,
                        'search' => false,
                    ),
                    'data_to_delete' => array(
                        'title' => $this->l('Data want to delete'),
                        'type' => 'text',
                        'callback' => 'displayFieldDataType',
                        'callback_object' => $this->module,
                        'orderby' => false,
                        'search' => false,
                    ),
                    'requested_date_time' => array(
                        'title' => $this->l('Date of request'),
                        'type' => 'datetime',
                        'align' => 'center',
                        'orderby' => false,
                        'search' => false,
                    ),
                    'action_taken_date_time' => array(
                        'title' => $this->l('Date of refusal'),
                        'type' => 'datetime',
                        'align' => 'center',
                        'orderby' => false,
                        'search' => false,
                    ),
                ),
            );
        }
        return $this->declined_fields;
    }

    private $withdraw_fields = array();

    public function getWithdrawFields()
    {
        if (!$this->withdraw_fields) {
            $this->withdraw_fields = array(
                'list' => array(
                    'title' => $this->l('Declined requests'),
                    'actions' => array('viewcustomer'),
                    'orderBy' => 'action_taken_date_time',
                    'orderWay' => 'DESC',
                    'nb' => 'getDeletions',
                    'list' => 'getDeletions',
                    'no_link' => true,
                    'bulk_actions' => array(
                        'deleteWithDraw' => array(
                            'text' => $this->l('Delete'),
                            'icon' => 'icon-trash',
                            'confirm' => $this->l('Do you want to delete selected items?')
                        ),
                    ),
                    'list_id' => 'ets_gdpr_deletion',
                    'alias' => 'd',
                    'status' => Gdpr_defines::WITHDRAW
                ),
                'fields' => array(
                    'id_customer' => array(
                        'title' => $this->l('Customer ID'),
                        'align' => 'center',
                        'type' => 'int',
                        'class' => 'fixed-width-xs',
                        'orderby' => false,
                        'search' => false,
                    ),
                    'data_to_delete' => array(
                        'title' => $this->l('Data want to delete'),
                        'type' => 'text',
                        'callback' => 'displayFieldDataType',
                        'callback_object' => $this->module,
                        'orderby' => false,
                        'search' => false,
                    ),
                    'requested_date_time' => array(
                        'title' => $this->l('Date of request'),
                        'type' => 'datetime',
                        'align' => 'center',
                        'orderby' => false,
                        'search' => false,
                    ),
                    'action_taken_date_time' => array(
                        'title' => $this->l('Date of withdrawal'),
                        'type' => 'datetime',
                        'align' => 'center',
                        'orderby' => false,
                        'search' => false,
                    ),
                ),
            );
        }
        return $this->withdraw_fields;
    }

    private $mlog_fields = array();

    public function getModifiedLogFields()
    {
        if (!$this->mlog_fields) {
            $this->mlog_fields = array(
                'list' => array(
                    'title' => Tools::getValue('control', false) == 'myaccount' || Tools::getIsset('previewdata') ? $this->l('Data modification log') : null,
                    'actions' => array('details'),
                    'orderBy' => 'modified_date_time',
                    'orderWay' => 'DESC',
                    'nb' => 'getMLogs',
                    'list' => 'getMLogs',
                    'no_link' => true,
                    'show_action' => false,
                    'bulk_actions' => array(
                        'deleteMLog' => array(
                            'text' => $this->l('Delete'),
                            'icon' => 'icon-trash',
                            'confirm' => $this->l('Do you want to delete selected items?')
                        ),
                    ),
                    'list_id' => 'ets_gdpr_modification',
                    'alias' => 'm',
                ),
                'fields' => array(
                    'id_customer' => array(
                        'title' => $this->l('Customer ID'),
                        'align' => 'center',
                        'type' => 'int',
                        'class' => 'fixed-width-xs',
                        'orderby' => false,
                        'search' => false,
                        'frontend' => false,
                    ),
                    'modified_date_time' => array(
                        'title' => $this->l('Date'),
                        'type' => 'datetime',
                        'orderby' => false,
                        'search' => false,
                    ),
                    'data_modified' => array(
                        'title' => $this->l('Data type'),
                        'type' => 'text',
                        'callback' => 'displayFieldDataType',
                        'callback_object' => $this->module,
                        'strip_tag' => false,
                        'orderby' => false,
                        'search' => false,
                    ),
                    'modified_by' => array(
                        'title' => $this->l('Modified by'),
                        'type' => 'text',
                        'orderby' => false,
                        'search' => false,
                    ),
                ),
                'export_fields' => array(
                    'modified_date_time' => array(
                        'title' => $this->l('Date'),
                        'type' => 'datetime',
                    ),
                    'data_modified' => array(
                        'title' => $this->l('Data type'),
                        'type' => 'text',
                        'strip_tags' => true,
                    ),
                    'modified_by' => array(
                        'title' => $this->l('Modified by'),
                        'type' => 'text',
                    ),
                    'details' => array(
                        'title' => $this->l('Information'),
                        'type' => 'text',
                        'strip_tags' => true,
                    ),
                ),
            );
        }
        return $this->mlog_fields;
    }

    private $llog_fields = array();

    public function getLoginLogFields()
    {
        if (!$this->llog_fields) {
            $this->llog_fields = array(
                'list' => array(
                    'title' => Tools::getValue('control', false) == 'myaccount' || Tools::getIsset('previewdata') ? $this->l('Login log') : null,
                    'actions' => array(),
                    'orderBy' => 'login_date_time',
                    'orderWay' => 'DESC',
                    'nb' => 'getLLogs',
                    'list' => 'getLLogs',
                    'no_link' => true,
                    'show_action' => true,
                    'bulk_actions' => array(
                        'deleteLLog' => array(
                            'text' => $this->l('Delete'),
                            'icon' => 'icon-trash',
                            'confirm' => $this->l('Do you want to delete selected items?')
                        ),
                    ),
                    'list_id' => 'ets_gdpr_login',
                    'alias' => 'l',
                ),
                'fields' => array(
                    'id_customer' => array(
                        'title' => $this->l('Customer ID'),
                        'align' => 'center',
                        'type' => 'int',
                        'class' => 'fixed-width-xs',
                        'orderby' => false,
                        'search' => false,
                        'frontend' => false,
                    ),
                    'login_date_time' => array(
                        'title' => $this->l('Date'),
                        'type' => 'datetime',
                        'orderby' => false,
                        'search' => false,
                    ),
                    'ip' => array(
                        'title' => $this->l('IP'),
                        'type' => 'text',
                        'orderby' => false,
                        'search' => false,
                    ),
                    'device' => array(
                        'title' => $this->l('Device'),
                        'type' => 'text',
                        'orderby' => false,
                        'search' => false,
                    ),
                    'location' => array(
                        'title' => $this->l('Location'),
                        'type' => 'text',
                        'callback' => 'displayLocationField',
                        'callback_object' => $this->module,
                        'strip_tag' => false,
                        'orderby' => false,
                        'search' => false,
                    ),
                ),
                'export_fields' => array(
                    'login_date_time' => array(
                        'title' => $this->l('Date'),
                        'type' => 'datetime',
                    ),
                    'ip' => array(
                        'title' => $this->l('IP'),
                        'type' => 'text',
                    ),
                    'device' => array(
                        'title' => $this->l('Device'),
                        'type' => 'text',
                    ),
                ),
            );
        }
        return $this->llog_fields;
    }

    private $mes_fields = array();

    public function getContactMessageFields()
    {
        if (!$this->mes_fields) {
            $this->mes_fields = array(
                'list' => array(
                    'title' => $this->l('Contact messages'),
                    'actions' => array('details'),
                    'orderBy' => 'date_upd',
                    'orderWay' => 'DESC',
                    'nb' => 'getContactMessages',
                    'list' => 'getContactMessages',
                    'no_link' => true,
                    'bulk_actions' => false,
                    'alias' => 'cm',
                    'list_id' => 'customer_message',
                ),
                'fields' => array(
                    'date_add' => array(
                        'title' => $this->l('Date send'),
                        'type' => 'datetime',
                        'class' => 'fixed-width-xs',
                        'orderby' => false,
                        'search' => false,
                    ),
                    'name' => array(
                        'title' => $this->l('Subject'),
                        'type' => 'text',
                        'orderby' => false,
                        'search' => false,
                    ),
                    'message' => array(
                        'title' => $this->l('Message'),
                        'type' => 'text',
                        'orderby' => false,
                        'search' => false,
                    ),
                ),
            );
        }
        return $this->mes_fields;
    }

    private $order_fields = array();

    public function getOrderFields()
    {
        if (!$this->order_fields) {
            $this->order_fields = array(
                'list' => array(
                    'title' => $this->l('Orders'),
                    'actions' => array('details'),
                    'orderBy' => 'id_order',
                    'orderWay' => 'DESC',
                    'nb' => 'getOrders',
                    'list' => 'getOrders',
                    'no_link' => true,
                    'bulk_actions' => false,
                    'alias' => 'o',
                    'list_id' => 'order',
                ),
                'fields' => array(
                    'reference' => array(
                        'title' => $this->l('Reference'),
                        'align' => 'center',
                        'type' => 'text',
                        'class' => 'fixed-width-xs',
                        'orderby' => false,
                        'search' => false,
                    ),
                    'date_upd' => array(
                        'title' => $this->l('Date'),
                        'type' => 'datetime',
                        'orderby' => false,
                        'search' => false,
                    ),
                    'total' => array(
                        'title' => $this->l('Total'),
                        'align' => 'text-right',
                        'type' => 'price',
                        'currency' => true,
                        'badge_success' => true,
                        'callback' => 'setOrderCurrency',
                        'callback_object' => $this->module,
                        'orderby' => false,
                        'search' => false,
                    ),
                    'payment' => array(
                        'title' => $this->l('Payment'),
                        'type' => 'text',
                        'orderby' => false,
                        'search' => false,
                    ),
                    'osname' => array(
                        'title' => $this->l('Status'),
                        'type' => 'text',
                        'color' => 'color',
                        'orderby' => false,
                        'search' => false,
                    ),
                ),
            );
        }
        return $this->order_fields;
    }

    private $reviews_fields = array();

    public function getReviewsFields()
    {
        if (!$this->reviews_fields) {
            $this->reviews_fields = array(
                'list' => array(
                    'title' => $this->l('Product reviews'),
                    'actions' => array('details'),
                    'orderBy' => 'date_add',
                    'orderWay' => 'DESC',
                    'nb' => 'getReviews',
                    'list' => 'getReviews',
                    'no_link' => true,
                    'bulk_actions' => false,
                    'alias' => 'pc',
                    'list_id' => 'product_comment',
                ),
                'fields' => array(
                    'date_add' => array(
                        'title' => $this->l('Date'),
                        'type' => 'datetime',
                        'class' => 'fixed-width-xs',
                        'orderby' => false,
                        'search' => false,
                    ),
                    'grade' => array(
                        'title' => $this->l('Rating'),
                        'type' => 'text',
                        'float' => true,
                        'strip_tag' => false,
                        'orderby' => false,
                        'search' => false,
                    ),
                    'title' => array(
                        'title' => $this->l('Title'),
                        'type' => 'text',
                        'orderby' => false,
                        'search' => false,
                    ),
                    'content' => array(
                        'title' => $this->l('Comment'),
                        'type' => 'text',
                        'orderby' => false,
                        'search' => false,
                    ),
                ),
            );
        }
        return $this->reviews_fields;
    }

    private $log_fields = array();

    public function getLogFields()
    {
        if (!$this->log_fields) {
            $this->log_fields = array(
                'list' => array(
                    'title' => null,
                    'actions' => array(),
                    'orderBy' => 'datetime_added',
                    'orderWay' => 'DESC',
                    'nb' => 'getLogs',
                    'list' => 'getLogs',
                    'no_link' => true,
                    'show_action' => true,
                    'bulk_actions' => array(
                        'deleteLog' => array(
                            'text' => $this->l('Delete'),
                            'icon' => 'icon-trash',
                            'confirm' => $this->l('Do you want to delete selected items?')
                        ),
                    ),
                    'list_id' => 'ets_gdpr_log',
                    'alias' => 'l',
                ),
                'fields' => array(
                    'ip' => array(
                        'title' => $this->l('IP'),
                        'type' => 'text',
                        'orderby' => false,
                        'search' => false,
                    ),
                    'browser' => array(
                        'title' => $this->l('Browser'),
                        'type' => 'text',
                        'orderby' => false,
                        'search' => false,
                    ),
                    'customer_name' => array(
                        'title' => $this->l('Customer'),
                        'type' => 'text',
                        'callback' => 'displayCustomerField',
                        'callback_object' => $this->module,
                        'orderby' => false,
                        'search' => false,
                    ),
                    'accepted' => array(
                        'title' => $this->l('Accept privacy policy'),
                        'type' => 'bool',
                        'active' => 'status',
                        'orderby' => false,
                        'search' => false,
                        'class' => 'fixed-width-xs',
                        'align' => 'center',
                    ),
                    'datetime_added' => array(
                        'title' => $this->l('Date'),
                        'type' => 'datetime',
                        'orderby' => false,
                        'search' => false,
                    ),
                    'location' => array(
                        'title' => $this->l('Location'),
                        'type' => 'text',
                        'callback' => 'displayLocationField',
                        'callback_object' => $this->module,
                        'strip_tag' => false,
                        'orderby' => false,
                        'search' => false,
                    ),
                ),
            );
        }
        return $this->log_fields;
    }

    static $_INSTANCE = null;

    public static function getInstance()
    {
        if (self::$_INSTANCE == null) {
            self::$_INSTANCE = new Gdpr_defines();
        }
        return self::$_INSTANCE;
    }
}