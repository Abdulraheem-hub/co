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
<div id="_desktop_policy_link">
    <div id="policy-link">
        <a class="{if $policy.ETS_GDPR_POLICY_BY_POPUP}gdpr_viewmore{/if}" href="{if $policy.ETS_GDPR_POLICY_BY_POPUP}javascript:void(0){else}{$policy.ETS_GDPR_POLICY_PAGE_URL|escape:'quotes'}{/if}">{$policy.ETS_GDPR_POLICY_BTN_TITLE|escape:'html':'utf-8'}</a>
    </div>
</div>
