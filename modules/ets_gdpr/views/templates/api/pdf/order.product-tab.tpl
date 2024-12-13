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
<table class="product" width="100%" cellpadding="4" cellspacing="0">

	<thead>
	<tr>
		<th class="product header small" width="{$layout.reference.width|intval}%">{l s='Reference' mod='ets_gdpr' pdf='true'}</th>
		<th class="product header small" width="{$layout.product.width|intval}%">{l s='Product' mod='ets_gdpr' pdf='true'}</th>
		<th class="product header small" width="{$layout.tax_code.width|intval}%">{l s='Tax Rate' mod='ets_gdpr' pdf='true'}</th>
		{if isset($layout.before_discount)}
			<th class="product header small" width="{$layout.unit_price_tax_excl.width|intval}%">{l s='Base price' mod='ets_gdpr' pdf='true'} <br /> {l s='(Tax excl.)' mod='ets_gdpr' pdf='true'}</th>
		{/if}

		<th class="product header-right small" width="{$layout.unit_price_tax_excl.width|intval}%">{l s='Unit Price' mod='ets_gdpr' pdf='true'} <br /> {l s='(Tax excl.)' mod='ets_gdpr' pdf='true'}</th>
		<th class="product header small" width="{$layout.quantity.width|intval}%">{l s='Qty' mod='ets_gdpr' pdf='true'}</th>
		<th class="product header-right small" width="{$layout.total_tax_excl.width|intval}%">{l s='Total' mod='ets_gdpr' pdf='true'} <br /> {l s='(Tax excl.)' mod='ets_gdpr' pdf='true'}</th>
	</tr>
	</thead>

	<tbody>

	<!-- PRODUCTS -->
	{foreach $order_details as $order_detail}
		{cycle values=["color_line_even", "color_line_odd"] assign=bgcolor_class}
		<tr class="product {$bgcolor_class|escape:'html':'utf-8'}">

			<td class="product center">
				{$order_detail.product_reference|escape:'html':'utf-8'}
			</td>
			<td class="product left">
				{if $display_product_images}
					<table width="100%">
						<tr>
							<td width="15%">
								{if isset($order_detail.image) && $order_detail.image->id}
									{$order_detail.image_tag|escape:'html':'utf-8'}
								{/if}
							</td>
							<td width="5%">&nbsp;</td>
							<td width="80%">
								{$order_detail.product_name|escape:'html':'utf-8'}
							</td>
						</tr>
					</table>
				{else}
					{$order_detail.product_name|escape:'html':'utf-8'}
				{/if}

			</td>
			<td class="product center">

			</td>
			{if isset($layout.before_discount)}
				<td class="product center">
					{if isset($order_detail.unit_price_tax_excl_before_specific_price)}
						{displayPrice currency=$order->id_currency price=$order_detail.unit_price_tax_excl_before_specific_price}
					{else}
						--
					{/if}
				</td>
			{/if}

			<td class="product right">
				{displayPrice currency=$order->id_currency price=$order_detail.unit_price_tax_excl}
			</td>
			<td class="product center">
				{$order_detail.product_quantity|intval}
			</td>
			<td  class="product right">
				{displayPrice currency=$order->id_currency price=$order_detail.total_price_tax_excl}
			</td>
		</tr>

		{foreach $order_detail.customizedDatas as $customizationPerAddress}
			{foreach $customizationPerAddress as $customizationId => $customization}
				<tr class="customization_data {$bgcolor_class|escape:'html':'utf-8'}">
					<td class="center"> &nbsp;</td>

					<td>
						{if isset($customization.datas[$smarty.const._CUSTOMIZE_TEXTFIELD_]) && count($customization.datas[$smarty.const._CUSTOMIZE_TEXTFIELD_]) > 0}
							<table style="width: 100%;">
								{foreach $customization.datas[$smarty.const._CUSTOMIZE_TEXTFIELD_] as $customization_infos}
									<tr>
										<td style="width: 30%;">
											{$customization_infos.name|string_format:{l s='%s:' mod='ets_gdpr' pdf='true'}|escape:'html':'utf-8'}
										</td>
										<td>{if (int)$customization_infos.id_module}{$customization_infos.value nofilter}{else}{$customization_infos.value nofilter}{/if}</td>
									</tr>
								{/foreach}
							</table>
						{/if}

						{if isset($customization.datas[$smarty.const._CUSTOMIZE_FILE_]) && count($customization.datas[$smarty.const._CUSTOMIZE_FILE_]) > 0}
							<table style="width: 100%;">
								<tr>
									<td style="width: 70%;">{l s='image(s):' mod='ets_gdpr' pdf='true'}</td>
									<td>{count($customization.datas[$smarty.const._CUSTOMIZE_FILE_])|intval}</td>
								</tr>
							</table>
						{/if}
					</td>

					<td class="center">
						({if $customization.quantity == 0}1{else}{$customization.quantity|intval}{/if})
					</td>

					{assign var=end value=($layout._colCount-3)}
					{for $var=0 to $end}
						<td class="center">
							--
						</td>
					{/for}

				</tr>
				<!--if !$smarty.foreach.custo_foreach.last-->
			{/foreach}
		{/foreach}
	{/foreach}
	<!-- END PRODUCTS -->

	<!-- CART RULES -->

	{assign var="shipping_discount_tax_incl" value="0"}
	{foreach from=$cart_rules item=cart_rule name="cart_rules_loop"}
		{if $smarty.foreach.cart_rules_loop.first}
			<tr class="discount">
				<th class="header" colspan="{$layout._colCount|intval}">
					{l s='Discounts' mod='ets_gdpr' pdf='true'}
				</th>
			</tr>
		{/if}
		<tr class="discount">
			<td class="white right" colspan="{$layout._colCount - 1|intval}">
				{$cart_rule.name|escape:'html':'utf-8'}
			</td>
			<td class="right white">
				- {displayPrice currency=$order->id_currency price=$cart_rule.value_tax_excl}
			</td>
		</tr>
	{/foreach}

	</tbody>

</table>
