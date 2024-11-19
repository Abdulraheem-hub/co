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
{if !empty($addresses)}
<div class="address-body">
    {foreach from=$addresses item='address'}
        <div class="address item{$address.id_address|intval}" data-id="{$address.id_address|intval}">
            <h3 class="address_title">{$address.alias|escape:'html':'utf-8'}</h3>
            <div class="address_item">{$address.formatted nofilter}</div>
        </div>
    {/foreach}
</div>
{else}
    <div class="gdpr_no_address {if isset($controller_type) && $controller_type == 'admin'}list-empty{/if}">
        {if isset($controller_type) && $controller_type == 'admin'}
            <div class="list-empty-msg">
                <i class="icon-warning-sign list-empty-icon"></i>
                {l s='No records found' mod='ets_gdpr'}
            </div>
        {else}
            <p class="list-empty-msg">{l s='No address' mod='ets_gdpr'}</p>
        {/if}
    </div>
{/if}