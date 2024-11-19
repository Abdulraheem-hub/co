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
<!--  TAX DETAILS -->
{if $tax_exempt}

	{l s='Exempt of VAT according to section 259B of the General Tax Code.' mod='ets_gdpr' pdf='true'}

{elseif (isset($tax_breakdowns) && $tax_breakdowns)}
	<table id="tax-tab" width="100%">
		<thead>
			<tr>
				<th class="header small">{l s='Tax Detail' mod='ets_gdpr' pdf='true'}</th>
				<th class="header small">{l s='Tax Rate' mod='ets_gdpr' pdf='true'}</th>
				{if $display_tax_bases_in_breakdowns}
					<th class="header small">{l s='Base price' mod='ets_gdpr' pdf='true'}</th>
				{/if}
				<th class="header-right small">{l s='Total Tax' mod='ets_gdpr' pdf='true'}</th>
			</tr>
		</thead>
		<tbody>
		{assign var=has_line value=false}

		{foreach $tax_breakdowns as $label => $bd}
			{assign var=label_printed value=false}

			{foreach $bd as $line}
				{if $line.rate == 0}
					{continue}
				{/if}
				{assign var=has_line value=true}
				<tr>
					<td class="white">
						{if !$label_printed}
							{if $label == 'product_tax'}
								{l s='Products' mod='ets_gdpr' pdf='true'}
							{elseif $label == 'shipping_tax'}
								{l s='Shipping' mod='ets_gdpr' pdf='true'}
							{elseif $label == 'ecotax_tax'}
								{l s='Ecotax' mod='ets_gdpr' pdf='true'}
							{elseif $label == 'wrapping_tax'}
								{l s='Wrapping' mod='ets_gdpr' pdf='true'}
							{/if}
							{assign var=label_printed value=true}
						{/if}
					</td>

					<td class="center white">
						{$line.rate|floatval} %
					</td>

					{if $display_tax_bases_in_breakdowns}
						<td class="right white">
							{if isset($is_order_slip) && $is_order_slip}- {/if}
							{displayPrice currency=$order->id_currency price=$line.total_tax_excl}
						</td>
					{/if}

					<td class="right white">
						{if isset($is_order_slip) && $is_order_slip}- {/if}
						{displayPrice currency=$order->id_currency price=$line.total_amount}
					</td>
				</tr>
			{/foreach}
		{/foreach}

		{if !$has_line}
		<tr>
			<td class="white center" colspan="{if $display_tax_bases_in_breakdowns}4{else}3{/if}">
				{l s='No taxes' mod='ets_gdpr' pdf='true'}
			</td>
		</tr>
		{/if}

		</tbody>
	</table>

{/if}
<!--  / TAX DETAILS -->
