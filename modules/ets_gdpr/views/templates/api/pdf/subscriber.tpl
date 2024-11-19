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
{$style_tab nofilter}
<table id="summary-tab" width="100%">
    <thead>
    <tr>
        <th class="header center" valign="center">{l s='Subscription' mod='ets_gdpr' pdf='true'}</th>
        <th class="header center" valign="center">{l s='Status' mod='ets_gdpr' pdf='true'}</th>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td class="center white">{l s='Newsletter' mod='ets_gdpr' pdf='true'}</td>
            <td class="center white">{if isset($object.newsletter) && $object.newsletter}{l s='Subscribed' mod='ets_gdpr' pdf='true'}{else}{l s='Not subscribed' mod='ets_gdpr' pdf='true'}{/if}</td>
        </tr>
        <tr>
            <td class="center white">{l s='Partner offers' mod='ets_gdpr' pdf='true'}</td>
            <td class="center white">{if isset($object.optin) && $object.optin}{l s='Subscribed' mod='ets_gdpr' pdf='true'}{else}{l s='Not subscribed' mod='ets_gdpr' pdf='true'}{/if}</td>
        </tr>
    </tbody>
</table>
