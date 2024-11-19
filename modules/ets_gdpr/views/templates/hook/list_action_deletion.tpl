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
{if isset($actions) && $actions}
    <ul class="gdpr_action_deletion">
        {foreach from=$actions item = 'action'}
            <li class="gdpr_action_item {if !empty($action.class)}{$action.class|escape:'quotes'}{/if} {$action.icon|escape:'html':'utf-8'}" data-id="{$id_customer|intval}">
                <a href="{$action.href|escape:'html':'UTF-8'}" class="btn-default" title="{$action.action|escape:'html':'utf-8'}" >
                    <i class="icon-{$action.icon|escape:'html':'utf-8'}"></i> {$action.action|escape:'html':'utf-8'}
                </a>
            </li>
        {/foreach}
    </ul>
{/if}