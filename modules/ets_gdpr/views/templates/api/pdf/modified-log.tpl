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
        <th class="header center" valign="center">{l s='Data type' mod='ets_gdpr' pdf='true'}</th>
        <th class="header left" valign="left">{l s='Modified by' mod='ets_gdpr' pdf='true'}</th>
    </tr>
    </thead>
    <tbody>
    {if isset($object) && $object}
        {foreach from=$object item='row'}
            <tr>
                <td class="center white">{dateFormat date=$row.modified_date_time full=1}</td>
                <td class="center white">{$row.data_modified nofilter}</td>
                <td class="left white">{$row.modified_by|escape:'html':'utf-8'}</td>
            </tr>
        {/foreach}
    {/if}
    </tbody>
</table>
