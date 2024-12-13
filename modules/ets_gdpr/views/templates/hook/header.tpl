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
<!--Module: ets_socicallogin -->

<script type="text/javascript">
{if isset($ETS_GDPR_CONFIG) && $ETS_GDPR_CONFIG}
    {foreach from=$ETS_GDPR_CONFIG item='val' key='variableName'}
        {$variableName|escape:'html':'utf-8'} = {if $val.type=='string'}'{$val.value|escape:'html':'utf-8'}'{else}{$val.value|intval}{/if};
    {/foreach}
{/if}
{if isset($link_accept) && $link_accept}
var link_accept = '{$link_accept nofilter}';
{/if}
{if isset($declineUrl)}
var declineUrl = '{$declineUrl|escape:'html':'utf-8'}';
{/if}
{if isset($postLink)}
var postLink = '{$postLink|escape:'quotes'}';
{/if}
{if isset($successLabel)}
var successLabel = '{$successLabel|escape:'html':'utf-8'}';
{/if}
{if isset($successLabel)}
var errorLabel = '{$errorLabel|escape:'html':'utf-8'}';
{/if}
{if isset($productLink)}
var productLink = '{$productLink|escape:'html':'utf-8'}';
{/if}
</script>
{if isset($base_dir) && $base_dir}
    <script src="{$base_dir|escape:'quotes'}views/js/front.js"></script>
{/if}
<!--/Module: ets_socicallogin-->

