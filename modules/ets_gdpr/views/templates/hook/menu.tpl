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

{if !empty($menus)}
    <div class="block-gdpr-menu hidden-sm-down">
        <ul class="gdpr_menu">
            {foreach from=$menus item='menu'}
                <li class="gdpr item {if $currentTab == $menu.name}actived{/if}">
                    <a href="{$link|cat:'&curTab='|cat:$menu.name|escape:'quotes'}" title="{$menu.label|escape:'html':'utf-8'}">{$menu.label|escape:'html':'utf-8'}</a>
                </li>
            {/foreach}
        </ul>
    </div>
{/if}