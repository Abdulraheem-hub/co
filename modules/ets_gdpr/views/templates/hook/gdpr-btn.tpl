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
<div class="gdpr_button">
    <a href="javascript:void(0)" class="gdpr_btn_accept btn" style="background-color: {$design.ETS_GDPR_BUTTON_BG_COLOR|escape:'quotes'}; color: {$design.ETS_GDPR_BOTTON_TEXT_COLOR|escape:'quotes'}" title="{$design.ETS_GDPR_ACCEPT_BUTTON_LABEL|escape:'html':'utf-8'}">{$design.ETS_GDPR_ACCEPT_BUTTON_LABEL|escape:'html':'utf-8'}</a>
    {if $allow_decline}
        <a href="javascript:void(0)" class="gdpr_btn_decline btn" title="{$design.ETS_GDPR_NOT_ACCEPT_LABEL|escape:'html':'utf-8'}">{$design.ETS_GDPR_NOT_ACCEPT_LABEL|escape:'html':'utf-8'}</a>
    {/if}
</div>
{if $allow_decline}<style>{literal}
        .gdpr_button .gdpr_btn_deline.btn:hover{
            background: {/literal}{$design.ETS_GDPR_BUTTON_BG_COLOR|escape:'quotes'}{literal};
            border-color: {/literal}{$design.ETS_GDPR_BUTTON_BG_COLOR|escape:'quotes'}{literal};
            color: {/literal}{$design.ETS_GDPR_BOTTON_TEXT_COLOR|escape:'quotes'}{literal};
        }
{/literal}</style>{/if}