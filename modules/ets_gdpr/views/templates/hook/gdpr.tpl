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
{if !empty($hook) && !empty($design)}
    {assign var='position' value=($hook != 'footer' && $hook != 'top')}
    <div class="gdpr_hook {if $position}{if $hook == 'register'}form-group row{/if}{else}gdpr_wrapper{/if} {$hook|escape:'html':'utf-8'} {if $design.ETS_GDPR_WELCOME_TEMPLATE != 'bottom' && $design.ETS_GDPR_WELCOME_TEMPLATE != 'top'}{$design.ETS_GDPR_WELCOME_TEMPLATE|escape:'html':'utf-8'}{/if}" {if !$position}{if $design.ETS_GDPR_WELCOME_TEMPLATE != 'bottom' && $design.ETS_GDPR_WELCOME_TEMPLATE != 'top'}style="width: {$design.ETS_GDPR_WELCOME_BOX_WIDTH|intval}px;"{/if}{/if}>
        {if $position}
            {if $is17 && $hook=='register'}<label class="col-md-3 form-control-label required"></label>{/if}
        {else}
            <div class="gdpr_overlay" {if !$position }style="{if isset($design.ETS_GDPR_MESSAGE_BG_COLOR) && $design.ETS_GDPR_MESSAGE_BG_COLOR}background-color: {$design.ETS_GDPR_MESSAGE_BG_COLOR|escape:'html':'utf-8'};{/if}{if isset($design.ETS_GDPR_MESSAGE_BG_OPACITY) && $design.ETS_GDPR_MESSAGE_BG_OPACITY}opacity: {(0.1*$design.ETS_GDPR_MESSAGE_BG_OPACITY)|floatval};{/if}"{/if} ></div>
        {/if}
        <div class="{if !$position}gpdr_content{else}{if $is17 && $hook=='register'}col-md-6{/if}{if $is17 && $hook=='subscribe'}col-xs-12{/if}{/if}">
            {if $position}
                <span class="custom-checkbox">
                    {assign var="rand" value=rand(0, 100)}
                    <label for="gdpr_accept_{$rand|intval}" >
                        {if $is17}
                            <input id="gdpr_accept_{$rand|intval}" class="gdpr_accept {$hook|escape:'quotes'}" name="gdpr_accept" type="checkbox" value="1" required>
                            <span><i class="material-icons rtl-no-flip checkbox-checked">&#xE5CA;</i></span>
                        {/if}
                        {if !$is17}<input id="gdpr_accept_{$rand|intval}" class="gdpr_accept {$hook|escape:'quotes'}" name="gdpr_accept" type="checkbox" value="1" required>{/if}{$message.ETS_GDPR_MES_HOOK nofilter}
                        {if isset($notices) && $notices && $message.ETS_GDPR_MES_VIEW_MORE}<a class="gdpr_viewmore" {if isset($design.ETS_GDPR_VIEW_MORE_COLOR) && $design.ETS_GDPR_VIEW_MORE_COLOR}style="color: {$design.ETS_GDPR_VIEW_MORE_COLOR|escape:'html':'utf-8'}"{/if} href="#" title="{$design.ETS_GDPR_VIEW_MORE_LABEL|escape:'html':'utf-8'}">{$design.ETS_GDPR_VIEW_MORE_LABEL|escape:'html':'utf-8'}</a>{/if}
                    </label>
                </span>
            {/if}
            {if !$position}
                <div class="gdpr_welcome">
                    <div class="gdpr_title">
                        <span {if isset($design.ETS_GDPR_MESSAGE_TEXT_COLOR) && $design.ETS_GDPR_MESSAGE_TEXT_COLOR}style="color: {$design.ETS_GDPR_MESSAGE_TEXT_COLOR|escape:'html':'utf-8'}"{/if}>{$design.ETS_GDPR_WELCOME_MESSAGE nofilter}</span>
                        {if isset($notices) && $notices}
                            <a class="gdpr_viewmore" {if isset($design.ETS_GDPR_VIEW_MORE_COLOR) && $design.ETS_GDPR_VIEW_MORE_COLOR}style="color: {$design.ETS_GDPR_VIEW_MORE_COLOR|escape:'html':'utf-8'}"{/if} href="#" title="{$design.ETS_GDPR_VIEW_MORE_LABEL|escape:'html':'utf-8'}">{$design.ETS_GDPR_VIEW_MORE_LABEL|escape:'html':'utf-8'}</a>
                        {/if}
                        {if $design.ETS_GDPR_BUTTON_BESIDE_MESSAGE}
                            {assign var="allow_decline" value = false}
                            {include file='./gdpr-btn.tpl'}
                        {/if}
                    </div>
                </div>
            {/if}
        </div>
        {if $hook == 'register' }<div class="col-md-3 form-control-comment"></div>{/if}
        {if $is17 && $page == 'order' && isset($noticeHtml) && $noticeHtml}{$noticeHtml nofilter}{/if}
    </div>
{/if}