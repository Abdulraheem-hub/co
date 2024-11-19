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
{if isset($link) && $link}
  <li class="{if $is17}col-lg-4 col-md-6 col-sm-6 col-xs-12 {/if}ets_gdpr">
    <a id="gdpr-link" href="{$link|escape:'html':'utf-8'}">
      <span class="link-item">
        <i class="{if $is17}material-icons{else}fa fa-lock{/if}" {if !$is17}aria-hidden="true"{/if}>{if $is17}lock{/if}</i>
        {l s='Manage personal data' mod='ets_gdpr'}
      </span>
    </a>
  </li>
{/if}