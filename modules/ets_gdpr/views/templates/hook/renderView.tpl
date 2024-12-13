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
<div class="gdpr_wrapper_preview">
    {if isset($ets_gdpr_customer)}
        <div class="gdpr_customer panel">
            <h3 class="gdpr_title">{l s='General information' mod='ets_gdpr'}</h3>
            {include file="./personal-information.tpl"}
        </div>
    {/if}
    {if isset($addresses)}
        <div class="gdpr_order panel">
            <h3 class="gdpr_title">{l s='My address' mod='ets_gdpr'}</h3>
            {include file="./personal-address.tpl"}
        </div>
    {/if}
    {if isset($orders)}
        <div class="gdpr_orders">
            {include file="./personal-orders.tpl"}
        </div>
    {/if}
    {if isset($subscriptions)}
        <div class="gdpr_subscriptions panel">
            <h3 class="gdpr_title">{l s='My subscriptions' mod='ets_gdpr'}</h3>
            {include file="./personal-subscriptions.tpl"}
        </div>
    {/if}
    {if isset($reviews)}
        <div class="gdpr_reviews">
            {include file="./personal-reviews.tpl"}
        </div>
    {/if}
    {if isset($messages)}
        <div class="gdpr_messages">
            {include file="./personal-messages.tpl"}
        </div>
    {/if}
    {if isset($mod_log)}
        <div class="gdpr_modification_logs">
            {$mod_log nofilter}
        </div>
    {/if}
    {if isset($login_log)}
        <div class="gdpr_login_logs">
            {$login_log nofilter}
        </div>
    {/if}
    <div class="panel-footer">
        <a href="{$backLink|escape:'quotes'}" class="btn btn-default btn-left"><i class="process-icon-back"></i> {l s='Back to list' mod='ets_gdpr'}</a>
        <a href="{$backLink|escape:'quotes'}&declined_request&status=dec&id_ets_gdpr_deletion={$identity|intval}" class="gdpr-declined btn btn-default btn-right"><i class="icon-remove"></i> {l s='Declined' mod='ets_gdpr'}</a>
        <a href="{$backLink|escape:'quotes'}&approve_request&status=app&id_ets_gdpr_deletion={$identity|intval}" class="gdpr-approve btn btn-default btn-right"><i class="icon-check"></i> {l s='Approve' mod='ets_gdpr'}</a>
    </div>
</div>
