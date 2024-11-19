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
{if isset($message) && $message}
<div class="gdpr_contact_message">
    <h2 class="gdpr_title">{l s='Contact message details' mod='ets_gdpr'}</h2>
    <div class="gdpr_content">
        <div class="form_group">
            <label class="">{l s='Email: ' mod='ets_gdpr'}</label>
            <span>{$message.email|escape:'html':'utf-8'}</span>
        </div>
        <div class="form_group">
            <label class="">{l s='To: ' mod='ets_gdpr'}</label>
            <span>{$message.name|escape:'html':'utf-8'}</span>
        </div>
        <div class="form_group">
            <label class="">{l s='Message: ' mod='ets_gdpr'}</label>
            <span>{$message.message|escape:'html':'utf-8'}</span>
        </div>
        <div class="form_group">
            <label class="">{l s='Date added: ' mod='ets_gdpr'}</label>
            <span>{$message.date_add|escape:'html':'utf-8'}</span>
        </div>
        <div class="form_group">
            <label class="">{l s='Date updated: ' mod='ets_gdpr'}</label>
            <span>{$message.date_upd|escape:'html':'utf-8'}</span>
        </div>
    </div>
    <a class="back_to_list" href="{$mLink|escape:'quotes'}" title="{l s='Back to list' mod='ets_gdpr'}">{l s='Back to list' mod='ets_gdpr'}</a>
</div>
{/if}