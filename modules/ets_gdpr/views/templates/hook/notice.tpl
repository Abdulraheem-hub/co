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
{if isset($notices) && $notices}
    <div class="gdpr_notice">
        <div class="gdpr_notice_wap">
            <h3 class="gdpr_title">{$design.ETS_GDPR_NOTICE_TITLE|escape:'html':'utf-8'}</h3>
            <div class="gdpr_content">
                <div class="gdpr_general_top">
                    {$design.ETS_GDPR_NOTICE_TOP nofilter}
                </div>
                <ul class="gdpr_general_notice">
                    {assign var='ik' value=0}
                    {foreach from=$notices item='notice'}
                        {assign var='ik' value=$ik+1}
                        <li class="gdpr_notice_item">
                            <h3 class="gdpr_title item">
                                {if $design.ETS_GDPR_NUMBER_NOTICES}<span class="gdpr_number" style="background-color: {$design.ETS_GDPR_NUMBER_BG_COLOR|escape:'html':'utf-8'};color: {$design.ETS_GDPR_NUMBER_TEXT_COLOR|escape:'html':'utf-8'}">{$ik|intval}</span>&nbsp;{/if}{$notice.title|escape:'html':'utf-8'}
                            </h3>
                            <span class="gdpr_desc item">{$notice.description nofilter}</span>
                        </li>
                    {/foreach}
                </ul>
                <div class="gdpr_general_bottom">
                    {$design.ETS_GDPR_NOTICE_BOTTOM nofilter}
                </div>
            </div>
            {assign var="allow_decline" value = true}
            {include file='./gdpr-btn.tpl'}
        </div>
    </div>
{/if}