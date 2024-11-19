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
{if isset($object) && $object}
{$style_tab nofilter}

<table id="customer-tab" width="100%" cellspacing="0">
    <tr>
        <td class="customer right grey bold" width="30%">{l s='First name: ' mod='ets_gdpr' pdf='true'}</td>
        <td class="customer left white" width="70%">{$object->firstname|escape:'html':'utf-8'}</td>
    </tr>
    <tr>
        <td class="customer right grey bold" width="30%">{l s='Last name: ' mod='ets_gdpr' pdf='true'}</td>
        <td class="customer left white" width="70%">{$object->lastname|escape:'html':'utf-8'}</td>
    </tr>
    <tr>
        <td class="customer right grey bold" width="30%">{l s='Email: ' mod='ets_gdpr' pdf='true'}</td>
        <td class="customer left white" width="70%">{$object->email|escape:'html':'utf-8'}</td>
    </tr>
    <tr>
        <td class="customer right grey bold" width="30%">{l s='Gender: ' mod='ets_gdpr' pdf='true'}</td>
        <td class="customer left white" width="70%">
            {if $object->id_gender == 1}
                {l s='Male' mod='ets_gdpr' pdf='true'}
            {elseif $object->id_gender == 2}
                {l s='Female' mod='ets_gdpr' pdf='true'}
            {else}
                {l s='Unknown' mod='ets_gdpr' pdf='true'}
            {/if}
        </td>
    </tr>
    <tr>
        <td class="customer right grey bold" width="30%">{l s='Birthday: ' mod='ets_gdpr' pdf='true'}</td>
        <td class="customer left white" width="70%">
            {if $object->birthday != '0000-00-00'}{dateFormat date=$object->birthday full=0}{else}{l s='Unknown' mod='ets_gdpr' pdf='true'}{/if}
        </td>
    </tr>
</table>
{/if}