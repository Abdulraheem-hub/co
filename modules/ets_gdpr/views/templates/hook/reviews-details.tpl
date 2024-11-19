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
{if isset($reviews) && $reviews}
    <div class="gdpr_contact_message">
        <h2 class="gdpr_title">{l s='Product review details' mod='ets_gdpr'}</h2>
        <div class="gdpr_content">
            <div class="form_group">
                <label class="">{l s='Date: ' mod='ets_gdpr'}</label>
                <span>{dateFormat date=$reviews.date_add full=1}</span>
            </div>
            <div class="form_group">
                <label class="">{l s='Rating: ' mod='ets_gdpr'}</label>
                <span>{$reviews.grade nofilter}</span>
            </div>
            <div class="form_group">
                <label class="">{l s='Title: ' mod='ets_gdpr'}</label>
                <span>{$reviews.title|escape:'html':'utf-8'}</span>
            </div>
            <div class="form_group">
                <label class="">{l s='Comment: ' mod='ets_gdpr'}</label>
                <span>{$reviews.content|escape:'html':'utf-8'}</span>
            </div>
        </div>
        <a class="back_to_list" href="{$mLink|escape:'quotes'}" title="{l s='Back to list' mod='ets_gdpr'}">{l s='Back to list' mod='ets_gdpr'}</a>
    </div>
{/if}