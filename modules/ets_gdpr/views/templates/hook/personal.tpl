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
<div class="gdpr_personal">
    {if isset($ETS_GDPR_WARNING_PERSONAL_PAGE) && $ETS_GDPR_WARNING_PERSONAL_PAGE}
        <div class="gdpr_info">
            <p class="alert alert-info">
                {$ETS_GDPR_WARNING_PERSONAL_PAGE nofilter}
            </p>
        </div>
    {/if}
    {foreach from=$dataTypes item='dataType'}
        {if isset($DVs) && $dataType !='MOD' && $dataType != 'LOG' && (($DVs != 'ALL' && in_array($dataType.id_option, $DVs)) || $DVs == 'ALL') && isset($dataType.tmp) && $dataType.tmp}
            {assign var="tmpfile" value='./personal-'|cat:$dataType.tmp|cat:'.tpl'}
            <div class="gdpr_{$dataType.tmp|escape:'html':'utf-8'}">
                {if !in_array($dataType.id_option, array('MES', 'ORD', 'REV'))}
                    <h3 class="gdpr_title">{$dataType.label|escape:'html':'utf-8'}</h3>
                {/if}
                {include file=$tmpfile}
            </div>
        {/if}
    {/foreach}
    <div class="actions">
        {if isset($privileges.ETS_GDPR_ALLOW_DOWNLOAD) && $privileges.ETS_GDPR_ALLOW_DOWNLOAD && isset($privileges.ETS_GDPR_DATA_TO_VIEW) && $privileges.ETS_GDPR_DATA_TO_VIEW}
            <form id="gdpr_personal_form" class="gdpr_form form-horizontal" action="{$mLink|escape:'quotes'}&downloadpdf&type=personal" method="post" enctype="multipart/form-data" novalidate="">
                <input name="data_to_view" value="{$privileges.ETS_GDPR_DATA_TO_VIEW|escape:'html':'utf-8'}" type="hidden">
                <button class="btn btn-primary btn-left" type="submit">
                    {l s='Download my personal data' mod='ets_gdpr'}
                </button>
            </form>
        {/if}
    </div>
</div>