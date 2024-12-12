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
{if $type == 'img'}
    <a {if $tr.profile_url}target="_blank"{/if} class="{if $tr.profile_url}ets_gdpr_link{/if}" href="{if $tr.profile_url}{$tr.profile_url|escape:'quotes':'UTF-8'}{else}javascript:void(0){/if}" title="{if $tr.profile_url}{l s='Open profile page' mod='ets_gdpr'}{else}{l s='No profile page URL available' mod='ets_gdpr'}{/if}">
        <img width="80" src="{if $tr.network == 'Tumblr'}https://api.tumblr.com/v2/blog/{preg_replace('#^https?://#', '', $tr.profile_url)|escape:'quotes':'UTF-8'}avatar{else}{if $tr.profile_img}{$tr.profile_img|escape:'quotes':'UTF-8'}{else}{$img_base_dir nofilter}views/img/profile.jpg{/if}{/if}" alt="{$tr.network|escape:'html':'utf-8'}" />
    </a>
{elseif $type == 'viewcustomer'}
    {if !empty($badge)}
        <h4 class="badge badge-info">{l s='Customer deleted' mod='ets_gdpr'}</h4>
    {else}
        <div class="btn-group-action">
            <a href="{$link|escape:'quotes':'UTF-8'}" class="btn btn-default" title="{l s='View' mod='ets_gdpr'}">
                <i class="icon-search-plus"></i> {l s='View customer' mod='ets_gdpr'}
            </a>
        </div>
    {/if}
{elseif $type == 'badge'}
    <span class="badge badge-{$badgeType|escape:'quotes':'UTF-8'}">{$value|escape:'html':'utf-8'}</span>
{elseif $type == 'html'}
    {$value nofilter}
{elseif $type == 'location'}
    <a target="_blank" class="gdpr-link" href="https://db-ip.com/{$tr.ip|escape:'quotes':'UTF-8'}" >{l s='View location' mod='ets_gdpr'}</a>
{elseif $type == 'link_customer'}
    {if $value != '--'}{if $link}<a class="gdpr-link_customer" href="{$link|escape:'quotes':'UTF-8'}" title="{$value|escape:'html':'utf-8'}" >{$value|escape:'html':'utf-8'}</a>{else}{$value|escape:'html':'utf-8'}{/if}{else}--{/if}
{/if}