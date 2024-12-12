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
{if !empty($ets_gdpr_customer)}
<div class="customer_information">
    <span>{l s='First name: ' mod='ets_gdpr'}{$ets_gdpr_customer->firstname|escape:'html':'utf-8'}</span><br/>
    <span>{l s='Last name: ' mod='ets_gdpr'}{$ets_gdpr_customer->lastname|escape:'html':'utf-8'}</span><br/>
    <span>{l s='Email: ' mod='ets_gdpr'}{$ets_gdpr_customer->email|escape:'html':'utf-8'}</span><br/>
    <span>{l s='Gender: ' mod='ets_gdpr'}
        {if $ets_gdpr_customer->id_gender == 1}
            {l s='Male' mod='ets_gdpr'}
        {elseif $ets_gdpr_customer->id_gender == 2}
            {l s='Female' mod='ets_gdpr'}
        {else}
            {l s='Unknown' mod='ets_gdpr'}
        {/if}
    </span><br/>
    <span>{l s='Birthday: ' mod='ets_gdpr'}{if $ets_gdpr_customer->birthday != '0000-00-00'}{dateFormat date=$ets_gdpr_customer->birthday full=0}{else}{l s='Unknown' mod='ets_gdpr'}{/if}</span><br/>
</div>
{/if}
