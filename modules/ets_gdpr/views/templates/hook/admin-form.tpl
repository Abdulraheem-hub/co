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
<script type="text/javascript">
    var gdpr_base_admin_url = '{$base_admin_url|escape:'quotes':'UTF-8'}';
    var gdpr_confirm_approve = '{l s='Do you want approve the deletion request?' mod='ets_gdpr'}';
    var gdpr_confirm_declined = '{l s='Do you want declined the deletion request?' mod='ets_gdpr'}';
    var gdpr_no_records_found = '{l s='No records found' mod='ets_gdpr'}';
</script>
<script type="text/javascript" src="{$base_dir|escape:'quotes'}views/js/admin.js"></script>
<div class="gdpr_admin_wrapper">
    {if isset($configTabs) && $configTabs}
    <div class="gdpr_admin_tabs">
        <ul class="gdpr_admin_ul">
            {foreach from=$configTabs item='config'}
            <li class="gdpr_admin_li">
                <a class="gdpr_admin_{$config.name|escape:'html':'utf-8'} {if $tabActive == $config.name}active{/if}" href="{$base_admin_url|escape:'quotes'}{if $config.name != ''}&pTAB={$config.name|escape:'html':'utf-8'}{/if}" title="{$config.label|escape:'html':'utf-8'}">
                    {$config.label|escape:'html':'utf-8'}
                </a>
            </li>
            {/foreach}
        </ul>
    </div>
    {/if}
    <div class="gdpr_admin_content {if !$tabActive}deletion_requests{else}{$tabActive|escape:'html':'utf-8'}{/if}">
        {$html nofilter}
    </div>
</div>