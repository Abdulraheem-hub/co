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
<div class="gdpr_form_subscription">
    <table class="table mysubscription">
        <thead>
            <tr class="nodrag nodrop">
                <th>{l s='Subscription' mod='ets_gdpr'}</th>
                <th>{l s='Status' mod='ets_gdpr'}</th>
                {if isset($privileges.ETS_GDPR_ALLOW_UDP_NEWSLETTER) && $privileges.ETS_GDPR_ALLOW_UDP_NEWSLETTER && isset($mLink) && $mLink}
                <th class="text-align: center;">{l s='Action' mod='ets_gdpr'}</th>
                {/if}
            </tr>
        </thead>
        <tbody>
            {if isset($subscriptions.newsletter)}
            <tr class="newsletter">
                <td>{l s='Newsletter' mod='ets_gdpr'}</td>
                <td>
                    <span>{if $subscriptions.newsletter}<i class="fa fa-check"></i>&nbsp;{l s='Subscribed' mod='ets_gdpr'}{else}<i class="fa fa-times"></i>&nbsp;{l s='Not subscribed' mod='ets_gdpr'}{/if}</span>
                </td>
                {if isset($privileges.ETS_GDPR_ALLOW_UDP_NEWSLETTER) && $privileges.ETS_GDPR_ALLOW_UDP_NEWSLETTER && isset($mLink) && $mLink}
                <td>
                    <a class="btn btn-{if $subscriptions.newsletter}unsubscribe{else}primary{/if}" href="{$mLink|escape:'quotes'}&subscribe=newsletter&newsletter={if $subscriptions.newsletter}0{else}1{/if}">{if $subscriptions.newsletter}{l s='UnSubscribe' mod='ets_gdpr'}{else}{l s='Subscribe now' mod='ets_gdpr'}{/if}</a>
                </td>
                {/if}
            </tr>
            {/if}
            {if isset($subscriptions.partner)}
            <tr class="partner">
                <td>{l s='Partner offers' mod='ets_gdpr'}</td>
                <td>
                    <span>{if $subscriptions.partner}<i class="fa fa-check"></i>&nbsp;{l s='Subscribed' mod='ets_gdpr'}{else}<i class="fa fa-times"></i>&nbsp;{l s='Not subscribed' mod='ets_gdpr'}{/if}</span>
                </td>
                {if isset($privileges.ETS_GDPR_ALLOW_UDP_NEWSLETTER) && $privileges.ETS_GDPR_ALLOW_UDP_NEWSLETTER && isset($mLink) && $mLink}
                <td>
                    <a class="btn btn-{if $subscriptions.partner}unsubscribe{else}primary{/if}" href="{$mLink|escape:'quotes'}&subscribe=optin&optin={if $subscriptions.partner}0{else}1{/if}">{if $subscriptions.partner}{l s='UnSubscribe' mod='ets_gdpr'}{else}{l s='Subscribe now' mod='ets_gdpr'}{/if}</a>
                </td>
                {/if}
            </tr>
            {/if}
        </tbody>
    </table>
</div>