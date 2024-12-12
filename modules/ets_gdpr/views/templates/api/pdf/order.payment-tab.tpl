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
<table id="payment-tab" width="100%">
    <tr>
        <td class="payment center small grey bold" width="44%">{l s='Payment Method' mod='ets_gdpr' pdf='true'}</td>
        <td class="payment left white" width="56%">
            <table width="100%" border="0">
                {if isset($payments) && is_array($payments) && $payments|count > 0}
                    {foreach from=$payments item=payment}
                        <tr>
                            <td class="right small">{$payment->payment_method|escape:'html'}</td>
                            <td class="right small">{displayPrice currency=$payment->id_currency price=$payment->amount}</td>
                        </tr>
                    {/foreach}
                {elseif isset($order->payment) && $order->payment}
                    <tr>
                        <td class="right small">{$order->payment|escape:'html'}</td>
                    </tr>
                {/if}
            </table>
        </td>
    </tr>
</table>
