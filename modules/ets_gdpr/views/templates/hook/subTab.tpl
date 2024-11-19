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
{if isset($TABs) && $TABs}
    {if $pTAB && $pTAB.label}<h3 class="gdpr_admin_{if isset($pTAB.name) && $pTAB.name}{$pTAB.name|escape:'html':'utf-8'}{else}deletion_requests{/if} gdpr_block_title">{$pTAB.label|escape:'html':'utf-8'}</h3>{/if}
    <div class=gdpr_sub_tabs">
        <ul class="gdpr_sub_tab_ul">
            {assign var="ik" value=0}
            {foreach from=$TABs item='TAB'}
                {assign var="ik" value=$ik+1}
                <li class="gdpr_sub_tab_li {if $sTAB == $TAB.name}active{/if}">
                    <a class="gdpr_sub_{$TAB.name|escape:'html':'utf-8'}" href="{$parentLink|escape:'quotes'}{if $TAB.name != '' && $ik != 1}&sTAB={$TAB.name|escape:'html':'utf-8'}{/if}" title="{$TAB.label|escape:'html':'utf-8'}">
                        {$TAB.label|escape:'html':'utf-8'}{if isset($TAB.nb) && $TAB.nb}&nbsp;<span class="gdpr-total sub-tab badge badge-default">{$TAB.nb|intval}</span>{/if}
                    </a>
                </li>
            {/foreach}
        </ul>
    </div>
{/if}