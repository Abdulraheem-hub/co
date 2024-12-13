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

class Gdpr_presenter extends Gdpr_translate
{
    static $_INSTANCE = null;

    public static function getInstance()
    {
        if (self::$_INSTANCE == null) {
            self::$_INSTANCE = new Gdpr_presenter();
        }
        return self::$_INSTANCE;
    }

    public function get($prop, $params = array())
    {
        if (!$prop || !Validate::isConfigName($prop)) {
            return false;
        }
        switch ($prop) {
            case 'getOrders':
                return $this->getOrders($params);
            case 'getContactMessages':
                return $this->getContactMessages($params);
            case 'getReviews':
                return $this->getReviews($params);
            case 'getMLogs':
                return $this->getMLogs($params);
            case 'getLLogs':
                return $this->getLLogs($params);
            case 'getNotices':
                return $this->getNotices($params);
            case 'getDeletions':
                return $this->getDeletions($params);
            default:
                return false;
        }
    }

    public function getOrders($params)
    {
        $export = isset($params['export']) && $params['export'] ? true : false;
        $nb = isset($params['nb']) && $params['nb'] ? true : false;

        $sql = 'SELECT ' . ($export ? 'o.*' : ($nb ? 'COUNT(o.id_order) `nb`' : 'o.id_order, o.reference, o.date_upd, o.id_currency, o.total_paid_tax_incl `total`, osl.`name` AS `osname`, os.`color`, IF(o.valid, 1, 0) badge_success, o.payment')) . '
		FROM `' . _DB_PREFIX_ . 'orders` o 
		LEFT JOIN `' . _DB_PREFIX_ . 'customer` c ON (c.`id_customer` = o.`id_customer`)
		' . ($export ? '' : 'LEFT JOIN `' . _DB_PREFIX_ . 'order_state` os ON (os.`id_order_state` = o.`current_state`)
		LEFT JOIN `' . _DB_PREFIX_ . 'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = ' . (int)$this->context->language->id . ')') . '
		WHERE 1 ' . (isset($params['id_customer']) && $params['id_customer'] ? ' AND c.id_customer = ' . (int)$params['id_customer'] : '')
            . (isset($params['id_order']) && $params['id_order'] ? ' AND o.id_order = ' . (int)$params['id_order'] : '')
            . Shop::addSqlRestriction(Shop::SHARE_ORDER, 'o');
        if (isset($params['nb']) && $params['nb'])
            return (int)Db::getInstance()->getValue($sql);
        if (isset($params['id_order']) && $params['id_order'])
            return Db::getInstance()->getRow($sql);

        $sql .= 'ORDER BY ' . (isset($params['sort']) && $params['sort'] ? $params['sort'] : 'o.id_order DESC')
            . (isset($params['start']) && isset($params['limit']) ? ' LIMIT ' . (int)$params['start'] . ', ' . (int)$params['limit'] : '');

        $orders = Db::getInstance()->executeS($sql);

        if ($orders && Gdpr_tools::getControllerType() != 'admin' && !(isset($params['export']))) {
            foreach ($orders as &$order) {
                $order['total'] = Tools::displayPrice($order['total'], (int)$order['id_currency']);
                $order['view_url'] = $this->context->link->getPageLink('order-detail', true) . '&id_order=' . (int)$order['id_order'];
                $order['view_text'] = $this->l('View details');
            }
        }

        return isset($params['export']) && $params['export'] ? ObjectModel::hydrateCollection('Order', $orders) : $orders;
    }

    public function getContactMessages($params = array())
    {
        $sql = 'SELECT ' . (isset($params['nb']) && $params['nb'] ? 'COUNT(cm.id_customer_message) `nb`' : (isset($params['itemId']) && $params['itemId'] ? 'ct.*, cm.*, cl.name' : 'cm.id_customer_message, cl.name, cm.message, cm.date_add')) . '
				FROM `' . _DB_PREFIX_ . 'customer_thread` ct
				RIGHT JOIN `' . _DB_PREFIX_ . 'customer_message` cm ON (cm.id_customer_thread = ct.id_customer_thread AND ct.id_lang = ' . (int)$this->context->language->id . ')
				LEFT JOIN `' . _DB_PREFIX_ . 'contact_lang` cl ON (ct.id_contact = cl.id_contact AND cl.id_lang = ' . (int)$this->context->language->id . ')
				WHERE ct.id_contact IS NOT NULL ' . (isset($params['id_customer']) && $params['id_customer'] ? ' AND ct.id_customer = ' . (int)$params['id_customer'] : '') . '  AND ct.id_contact <> 0 
					' . Shop::addSqlRestriction()
            . (isset($params['itemId']) && $params['itemId'] ? ' AND cm.id_customer_message = ' . (int)$params['itemId'] : '');
        if (isset($params['nb']) && $params['nb'])
            return (int)Db::getInstance()->getValue($sql);
        $sql .= ' GROUP BY cm.id_customer_message ';
        if (isset($params['itemId']) && $params['itemId'])
            return Db::getInstance()->getRow($sql);
        $sql .= 'ORDER BY ' . (isset($params['sort']) && $params['sort'] ? $params['sort'] : 'cm.date_upd DESC')
            . (isset($params['start']) && isset($params['limit']) ? ' LIMIT ' . (int)$params['start'] . ', ' . (int)$params['limit'] : '');

        return Db::getInstance()->executeS($sql);
    }

    public function getReviews($params = array())
    {
        $sql = 'SELECT ' . (isset($params['nb']) && $params['nb'] ? 'COUNT(pc.id_product_comment) `nb`' : 'pc.*') . '
				FROM `' . _DB_PREFIX_ . 'product_comment` pc
				WHERE 1 ' . (isset($params['id_customer']) && $params['id_customer'] ? ' AND pc.id_customer = ' . (int)$params['id_customer'] : '')
            . (isset($params['itemId']) && $params['itemId'] ? ' AND pc.id_product_comment = ' . (int)$params['itemId'] : '');
        if (isset($params['nb']) && $params['nb'])
            return (int)Db::getInstance()->getValue($sql);
        $sql .= ' GROUP BY pc.id_product_comment ';
        if (isset($params['itemId']) && $params['itemId']) {
            $result = Db::getInstance()->getRow($sql);
            $result['grade'] = $this->module->displayRatings(array('grade' => $result['grade'], 'export' => false));
            return $result;
        }
        $sql .= 'ORDER BY ' . (isset($params['sort']) && $params['sort'] ? $params['sort'] : 'pc.date_add DESC')
            . (isset($params['start']) && isset($params['limit']) ? ' LIMIT ' . (int)$params['start'] . ', ' . (int)$params['limit'] : '');
        $results = Db::getInstance()->executeS($sql);
        if ($results)
            foreach ($results as &$result) {
                $result['grade'] = $this->module->displayRatings(array(
                    'grade' => $result['grade'],
                    'export' => isset($params['export']) && $params['export'] ? true : false,
                ));
            }
        return $results;
    }

    public function getMLogs($params = array())
    {
        $nb = isset($params['nb']) && $params['nb'] ? true : false;
        $sql = 'SELECT ' . ($nb ? 'COUNT(m.id_ets_gdpr_modification)' : ' m.id_ets_gdpr_modification, m.id_customer, IF(m.id_customer > 0, CONCAT(LEFT(c.`firstname`, 1), ". ", c.`lastname`), "--") `customer_name`, IF(m.id_customer > 0, c.email, "--") `email`, m.modified_date_time, m.data_modified, m.modified_by' . ((isset($params['itemId']) || isset($params['export'])) ? ', m.details' : '')) . ' 
			FROM `' . _DB_PREFIX_ . 'ets_gdpr_modification` m 
			LEFT JOIN `' . _DB_PREFIX_ . 'customer` c ON (c.`id_customer` = m.`id_customer`) 
            WHERE m.id_shop = ' . (int)$this->context->shop->id
            . (isset($params['filter']) && $params['filter'] ? pSQL($params['filter']) : '')
            . (isset($params['itemId']) && $params['itemId'] ? ' AND m.id_ets_gdpr_modification = ' . (int)$params['itemId'] : '')
            . (isset($params['id_customer']) && $params['id_customer'] ? ' AND m.id_customer = ' . (int)$params['id_customer'] : '')
            . ($nb ? '' : ' GROUP BY m.id_ets_gdpr_modification ') . '
		       HAVING 1 ' . (isset($params['having']) && $params['having'] ? pSQL($params['having']) : '');
        if ($nb)
            return (int)Db::getInstance()->getValue($sql);
        if (!(isset($params['itemId']))) {
            $sql .= ' ORDER BY ' . (isset($params['sort']) && $params['sort'] ? pSQL($params['sort']) : ' m.modified_date_time DESC')
                . ((isset($params['start']) && $params['start'] !== false) && (isset($params['limit']) && $params['limit']) ? " LIMIT " . (int)$params['start'] . ", " . (int)$params['limit'] : '');
        }
        $mLogs = Db::getInstance()->executeS($sql);
        if ($mLogs)
            foreach ($mLogs as &$mLog) {
                if ($mLog['data_modified'] != 'Undefined') {
                    $mLog['data_modified'] = $this->module->displayDataType(array(
                        'dataTypes' => $mLog['data_modified'],
                        'export' => (isset($params['export']) && $params['export'] ? true : false),
                        'isLog' => true,
                    ));
                }
                if (isset($mLog['details']) && is_numeric($mLog['details'])) {
                    $mLog['link_order'] = (Gdpr_tools::getControllerType() != 'admin' ? $this->context->link->getPageLink('order-detail') : $this->context->link->getAdminLink('AdminOrders') . '&vieworder') . '&id_order=' . (int)$mLog['details'];
                }
            }
        return !(isset($params['itemId'])) ? $mLogs : (isset($mLogs[0]) ? $mLogs[0] : array());
    }

    public function getLLogs($params = array())
    {
        $nb = isset($params['nb']) && $params['nb'] ? true : false;
        $sql = 'SELECT ' . ($nb ? 'COUNT(l.id_ets_gdpr_login) ' : 'l.*, IF(l.id_customer > 0, CONCAT(LEFT(c.`firstname`, 1), ". ", c.`lastname`), "--") `customer_name`, IF(l.id_customer > 0, c.email, "--") `email`') . '
			FROM `' . _DB_PREFIX_ . 'ets_gdpr_login` l
			LEFT JOIN `' . _DB_PREFIX_ . 'customer` c ON (c.`id_customer` = l.`id_customer`) 
            WHERE l.id_shop = ' . (int)$this->context->shop->id
            . (isset($params['filter']) && $params['filter'] ? pSQL($params['filter']) : '')
            . (isset($params['id_customer']) && $params['id_customer'] ? ' AND l.id_customer = ' . (int)$params['id_customer'] : '')
            . ($nb ? '' : ' GROUP BY l.id_ets_gdpr_login ') . '
		       HAVING 1 ' . (isset($params['having']) && $params['having'] ? pSQL($params['having']) : '');
        if ($nb)
            return (int)Db::getInstance()->getValue($sql);

        $sql .= ' ORDER BY ' . (isset($params['sort']) && $params['sort'] ? pSQL($params['sort']) : ' l.login_date_time DESC')
            . ((isset($params['start']) && $params['start'] !== false) && (isset($params['limit']) && $params['limit']) ? " LIMIT " . (int)$params['start'] . ", " . (int)$params['limit'] : '');

        $lLogs = Db::getInstance()->executeS($sql);
        if ($lLogs)
            foreach ($lLogs as &$log) {
                $log['location'] = ($log['ip'] != 'Undefined' ? $this->module->displaySmarty('location', $log['ip']) : 'Undefined');
            }
        return $lLogs;
    }

    public function getLogs($params = array())
    {
        $context = isset($params['context']) && $params['context'] ? $params['context'] : Context::getContext();
        $nb = isset($params['nb']) && $params['nb'] ? true : false;
        $sql = 'SELECT ' . ($nb ? 'COUNT(l.id_ets_gdpr_log) ' : 'l.*, IF(l.id_customer > 0, CONCAT(LEFT(c.`firstname`, 1), ". ", c.`lastname`), "--") `customer_name`, IF(l.id_customer > 0, c.email, "--") `email`') . '
			FROM `' . _DB_PREFIX_ . 'ets_gdpr_log` l
			LEFT JOIN `' . _DB_PREFIX_ . 'customer` c ON (c.`id_customer` = l.`id_customer`)
            WHERE l.id_shop = ' . (int)$context->shop->id
            . (isset($params['filter']) && $params['filter'] ? pSQL($params['filter']) : '')
            . (isset($params['id_customer']) && $params['id_customer'] ? ' AND l.id_customer = ' . (int)$params['id_customer'] : '')
            . ($nb ? '' : ' GROUP BY l.id_ets_gdpr_log ') . '
		       HAVING 1 ' . (isset($params['having']) && $params['having'] ? pSQL($params['having']) : '');
        if ($nb)
            return (int)Db::getInstance()->getValue($sql);

        $sql .= ' ORDER BY ' . (isset($params['sort']) && $params['sort'] ? $params['sort'] : ' l.datetime_added DESC')
            . ((isset($params['start']) && $params['start'] !== false) && (isset($params['limit']) && $params['limit']) ? " LIMIT " . (int)$params['start'] . ", " . (int)$params['limit'] : '');

        $logs = Db::getInstance()->executeS($sql);
        if ($logs)
            foreach ($logs as &$log) {
                $log['location'] = ($log['ip'] != 'Undefined' ? $this->module->displaySmarty('location', $log['ip']) : 'Undefined');
            }
        return $logs;
    }

    public function getNotices($params)
    {
        $isFront = isset($params['isFront']) && $params['isFront'] ? true : false;
        $sql = 'SELECT n.*,nl.title' . ($isFront ? ',nl.description' : ', IF(n.display_to != "ALL", (SELECT GROUP_CONCAT(gl.name SEPARATOR ", ") FROM `' . _DB_PREFIX_ . 'group_lang` gl 
			WHERE FIND_IN_SET(gl.id_group, n.display_to) AND gl.id_lang = ' . (int)$this->context->language->id . '), "' . bqSQL($this->l('All')) . '") `display_to`') . ' 
			FROM `' . _DB_PREFIX_ . 'ets_gdpr_notice` n 
			LEFT JOIN `' . _DB_PREFIX_ . 'ets_gdpr_notice_lang` nl ON (nl.id_ets_gdpr_notice = n.id_ets_gdpr_notice) 
            WHERE n.id_shop = ' . (int)$this->context->shop->id . ' AND nl.id_lang = ' . (int)$this->context->language->id
            . ($isFront ? ' AND n.enabled = 1 AND IF(n.display_to != "ALL", FIND_IN_SET(' . (int)Group::getCurrent()->id . ', n.display_to), 1) ' : '')
            . (isset($params['filter']) && $params['filter'] ? pSQL($params['filter']) : '')
            . ' GROUP BY n.id_ets_gdpr_notice 
		       HAVING 1 ' . (isset($params['having']) && $params['having'] ? pSQL($params['having']) : '');
        if (isset($params['nb']) && $params['nb'])
            return ($nb = Db::getInstance()->executeS($sql)) ? count($nb) : 0;

        $sql .= ' ORDER BY ' . (isset($params['sort']) && $params['sort'] ? pSQL($params['sort']) : 'position')
            . ((isset($params['start']) && $params['start'] !== false) && (isset($params['limit']) && $params['limit']) ? " LIMIT " . (int)$params['start'] . ", " . (int)$params['limit'] : '');

        $notices = Db::getInstance()->executeS($sql);
        return $notices;
    }

    public function getDeletions($params)
    {
        $nb = isset($params['nb']) && $params['nb'] ? true : false;
        $status = isset($params['status']) && $params['status'] ? $params['status'] : false;

        $sql = 'SELECT ' . ($nb ? 'COUNT(d.id_ets_gdpr_deletion)' : 'd.id_ets_gdpr_deletion, d.id_customer, '
                . ($status != Gdpr_defines::PENDING ? 'd.action_taken_date_time,' : 'CONCAT(c.firstname," ", c.lastname) `customer_name`, c.email,') . ' d.data_to_delete, IF(d.status = "' . pSQL(Gdpr_defines::AUTO) . '", CONCAT(d.requested_date_time, "<'.'br'.'/>", "' . bqSQL($this->l('(Auto approve)')) . '"), d.requested_date_time) `requested_date_time` ') . ' 
			FROM `' . _DB_PREFIX_ . 'ets_gdpr_deletion` d 
			' . ($status != Gdpr_defines::PENDING ? '' : 'LEFT JOIN `' . _DB_PREFIX_ . 'customer` c ON (c.id_customer = d.id_customer)') . ' 
            WHERE d.id_shop = ' . (int)$this->context->shop->id
            . ($status ? ' AND d.status = "' . pSQL($status) . '" ' . ($status != Gdpr_defines::APPROVED ? '' : ' OR d.status = "' . pSQL(Gdpr_defines::AUTO) . '" ') : '')
            . (isset($params['itemId']) && $params['itemId'] ? ' AND d.id_ets_gdpr_deletion = ' . (int)$params['itemId'] : '')
            . (isset($params['filter']) && $params['filter'] ? pSQL($params['filter']) : '')
            . ($nb ? '' : ' GROUP BY d.id_ets_gdpr_deletion ') . ' 
		       HAVING 1 ' . (isset($params['having']) && $params['having'] ? pSQL($params['having']) : '');
        if ($nb)
            return (int)Db::getInstance()->getValue($sql);
        $sql .= ' ORDER BY ' . (isset($params['sort']) && $params['sort'] ? pSQL($params['sort']) : ' d.requested_date_time DESC')
            . ((isset($params['start']) && $params['start'] !== false) && (isset($params['limit']) && $params['limit']) ? " LIMIT " . (int)$params['start'] . ", " . (int)$params['limit'] : '');
        $deletions = Db::getInstance()->executeS($sql);
        if ($deletions)
            foreach ($deletions as &$deletion) {
                $deletion['data_to_delete'] = $this->module->displayDataType(array(
                    'dataTypes' => $deletion['data_to_delete'],
                    'export' => (isset($params['export']) && $params['export'] ? true : false),
                ));
            }
        return (!empty($params['itemId']) ? $deletions[0] : $deletions);
    }
}