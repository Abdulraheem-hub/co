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
{if isset($dataTypes) && isset($data_to_delete)}
    {if isset($isLog) && $isLog}
        {$dataTypes[$data_to_delete].name|escape:'html':'utf-8'}
    {else}
        {if $data_to_delete != 'ALL'}
            {assign var="dataTo" value=explode(',', $data_to_delete)}
        {/if}
        <ul class="gpdr_data_type" style="list-style: none;">
            {if $data_to_delete == 'ALL'}
                <li class="gdpr_data_type_item">
                    <span>{l s='Entire account data' mod='ets_gdpr'}</span>
                </li>
            {/if}
            {foreach from=$dataTypes key='idType' item='dataType'}
                {if $data_to_delete == 'ALL' || (!empty($dataTo) && in_array($idType, $dataTo))}
                    <li class="gdpr_data_type_item">
                        <span>- {$dataType.name|escape:'html':'utf-8'}</span>
                    </li>
                {/if}
            {/foreach}
        </ul>
    {/if}
{/if}