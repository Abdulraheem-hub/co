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
        <th class="header center" valign="center">{l s='Date' mod='ets_gdpr' pdf='true'}</th>
        <th class="header center" valign="center">{l s='IP' mod='ets_gdpr' pdf='true'}</th>
        <th class="header left" valign="left">{l s='Device' mod='ets_gdpr' pdf='true'}</th>
    </tr>
    </thead>
    <tbody>
    {if isset($login_logs) && $login_logs}
        {foreach from=$login_logs item='row'}
            <tr>
                <td class="center white">{dateFormat date=$row.login_date_time full=1}</td>
                <td class="center white">{$row.ip|escape:'html':'utf-8'}</td>
                <td class="left white">{$row.device|escape:'html':'utf-8'}</td>
            </tr>
        {/foreach}
    {else}
        <tr>
            <td colspan="3">
                {l s='No records found' mod='ets_gdpr' pdf='true'}
            </td>
        </tr>
    {/if}
    </tbody>
</table>
