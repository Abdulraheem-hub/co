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
<table id="addresses-tab" cellspacing="0" cellpadding="0">
	<tr>
		<td width="50%">{if $delivery_address}<span class="bold">{l s='Delivery Address' mod='ets_gdpr' pdf='true'}</span><br/><br/>
				{$delivery_address nofilter}
			{/if}
		</td>
		<td width="50%"><span class="bold">{l s='Billing Address' mod='ets_gdpr' pdf='true'}</span><br/><br/>
				{$invoice_address nofilter}
		</td>
	</tr>
</table>
