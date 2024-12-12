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

class Gdpr_pagination {
	public $total = 0;
	public $page = 1;
	public $limit = 20;
	public $num_links = 10;
	public $url = '';
	public $text = 'Showing {start} to {end} of {total} ({pages} Pages)';
	public $alias;
	public $friendly;
    public $style_links;
    public $style_results;

	public function __construct()
	{
		$this->alias = Configuration::get('YBC_BLOG_ALIAS');
		$this->friendly = (int)Configuration::get('YBC_BLOG_FRIENDLY_URL') && (int)Configuration::get('PS_REWRITING_SETTINGS') ? true : false;
	}

	public function render()
	{
		$first = $prev = $next = $last = 0;
		//total item.
		$total = $this->total;
		if ($total <= 1)
			return false;
		//current page
		if ($this->page < 1) {
			$page = 1;
		} else {
			$page = $this->page;
		}
		//per_page
		if (!(int)$this->limit) {
			$limit = 10;
		} else {
			$limit = $this->limit;
		}
		//show link
		$num_links = $this->num_links;
		// total page.
		$num_pages = ceil($total / $limit);
		//new
		$output = array();
		if ($page > 1) {
			$first = $this->replacePage(1);
			$prev = $this->replacePage($page - 1);
		}
		if ($num_pages > 1) {
			if ($num_pages <= $num_links) {
				$start = 1;
				$end = $num_pages;
			} else {
				$start = $page - floor($num_links / 2);
				$end = $page + floor($num_links / 2);
				if ($start < 1) {
					$end += abs($start) + 1;
					$start = 1;
				}
				if ($end > $num_pages) {
					$start -= ($end - $num_pages);
					$end = $num_pages;
				}
			}
			for ($i = $start; $i <= $end; $i++) {
				if ($page == $i) {
					$output[$i] = $i;
				} else {
					$output[$i] = $this->replacePage($i);
				}
			}
		}
		if ($page < $num_pages) {
			$next = $this->replacePage($page + 1);
			$last = $this->replacePage($num_pages);
		}
		$find = array('{start}', '{end}', '{total}', '{pages}');
		$replace = array(
			($total) ? (($page - 1) * $limit) + 1 : 0,
			((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit),
			$total,
			$num_pages
		);
		return array(
			'output' => $output,
			'result' => str_replace($find, $replace, $this->text),
			'start' => isset($start) ? $start : false,
			'end' => isset($end) ? $end : false,
			'first' => isset($first) ? $first : false,
			'prev' => isset($prev) ? $prev : false,
			'next' => isset($next) ? $next : false,
			'last' => isset($last) ? $last : false,
			'page' => $page,
			'num_pages' => $num_pages,
		);
	}

	public function replacePage($page)
	{
		if ($page > 1)
			return str_replace('_page_', $page, $this->url);
		elseif ($this->friendly && $this->alias && Tools::getValue('controller') != 'AdminModules')
			return str_replace('/_page_', '', $this->url);
		else
			return str_replace('_page_', $page, $this->url);
	}
}

?>