{*
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
*}
<table id="summary-tab" width="100%">
	<tr>
		<th class="header small" valign="middle">{l s='Invoice Date' mod='ets_gdpr' pdf='true'}</th>
		<th class="header small" valign="middle">{l s='Order Reference' mod='ets_gdpr' pdf='true'}</th>
		<th class="header small" valign="middle">{l s='Order date' mod='ets_gdpr' pdf='true'}</th>
		{if $addresses.invoice->vat_number}
			<th class="header small" valign="middle">{l s='VAT Number' mod='ets_gdpr' pdf='true'}</th>
		{/if}
	</tr>
	<tr>
		<td class="center small white">{dateFormat date=$order->invoice_date full=0}</td>
		<td class="center small white">{$order->getUniqReference()|escape:'html':'utf-8'}</td>
		<td class="center small white">{dateFormat date=$order->date_add full=0}</td>
		{if $addresses.invoice->vat_number}
			<td class="center small white">
				{$addresses.invoice->vat_number|escape:'html':'utf-8'}
			</td>
		{/if}
	</tr>
</table>
