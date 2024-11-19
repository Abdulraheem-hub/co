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
{if isset($modificationLog) && $modificationLog}
    <div class="gdpr_modification_log">
        {$modificationLog nofilter}
    </div>
{else}
    <div class="gdpr_modification_log_detail panel">
        <h3 class="gdpr_title">
            {l s='Modification log' mod='ets_gdpr'}: #{$mLog.id_ets_gdpr_modification|intval}
        </h3>
        <div class="gdpr_content_detail">
            <div class="form_group">
                <label class="">{l s='Data type: ' mod='ets_gdpr'}</label>
                <strong>{$mLog.data_modified|strip_tags|escape:'html':'utf-8'}</strong>
            </div>
            {if isset($mLog.link_order) && $mLog.link_order}
                <div class="form_group">
                    <label class="">{l s='Order Id: ' mod='ets_gdpr'}</label>
                    <strong>#{$mLog.details|intval}</strong>
                </div>
            {/if}
            <div class="form_group">
                <label class="">{l s='Modified by: ' mod='ets_gdpr'}</label>
                <strong>{$mLog.modified_by|escape:'html':'utf-8'}</strong>
            </div>
            <div class="form_group">
                <label class="">{l s='Modified date: ' mod='ets_gdpr'}</label>
                <strong>{dateFormat date=$mLog.modified_date_time full=1}</strong>
            </div>
            <div class="form_group">
                <div class="gdpr_data_modified_group">
                    <label class="gdpr_mod_info">{l s='Modified information: ' mod='ets_gdpr'}</label>
                    {if isset($mLog.link_order) && $mLog.link_order}
                        <p>{l s='General order information was updated' mod='ets_gdpr'}</p>
                        <a class="gdpr-vieworder btn btn-primary" href="{$mLog.link_order|escape:'quotes'}" title="{l s='View order' mod='ets_gdpr'}">{l s='View order' mod='ets_gdpr'}</a>
                    {else}
                        {$mLog.details nofilter}
                    {/if}
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <a class="back_to_list {if isset($admin) && $admin}btn btn-default{/if}" href="{$mLink|escape:'quotes'}" title="{l s='Back to list' mod='ets_gdpr'}">{if isset($admin) && $admin}<i class="process-icon-back"></i>{/if} {l s='Back to list' mod='ets_gdpr'}</a>
        </div>
    </div>
{/if}