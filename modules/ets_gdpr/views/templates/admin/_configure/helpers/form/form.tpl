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
{extends file="helpers/form/form.tpl"}
{block name="legend"}
    {if $field.title}
        {$smarty.block.parent}
        {if $table == 'send'}
            <span class="gdpr_close_form">{l s='Close' mod='ets_gdpr'}</span>
        {/if}
    {/if}
{/block}
{block name="label"}
    {if $is15}
        <div class="form-group-wrapper row_{$input.name|lower|escape:'html':'UTF-8'}">
    {/if}
    {$smarty.block.parent}
{/block}
{block name="field"}
    {$smarty.block.parent}
    {if $is15}
        </div>
    {/if}
{/block}
{block name="input"}
    {if $input.type == 'gdpr_group'}
        {assign var=groups value=$input.values}
        {if !empty($groups)}
            <div class="row">
                <div class="col-lg-6">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="fixed-width-xs">
                                <span class="title_box">
                                    <input type="checkbox" name="{$input.name|escape:'html':'utf-8'}[]" id="checkme" onclick="checkDelBoxes(this.form, '{$input.name|escape:'html':'utf-8'}[]', this.checked)" value="ALL" {if !empty($fields_value[$input.name]) && $fields_value[$input.name] == 'ALL'}checked{/if}/>
                                </span>
                            </th>
                            <th><span class="title_box">{l s='All' mod='ets_gdpr'}</span></th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach $groups as $key => $group}
                            {if isset($group.id_option) && $group.id_option}
                                {assign var='id_group' value=$group.id_option}
                            {else}
                                {assign var='id_group' value=$group.id_group}
                            {/if}
                            {if isset($id_group) && $id_group}
                            <tr>
                                <td>
                                    <input type="checkbox" name="{$input.name|escape:'html':'utf-8'}[]" class="groupBox" id="{$input.name|escape:'html':'utf-8'}_{$id_group|escape:'quotes'}" value="{$id_group|escape:'quotes'}" {if !empty($fields_value[$input.name]) && ( $fields_value[$input.name] == 'ALL' || (is_array($fields_value[$input.name]) && in_array($id_group, $fields_value[$input.name])) )}checked="checked"{/if} {if !empty($fields_value[$input.name]) && $fields_value[$input.name] == 'ALL'}disabled{/if}/>
                                </td>
                                <td>
                                    <label for="{$id_group|escape:'quotes'}">{$group.name|escape:'html':'utf-8'}</label>
                                </td>
                            </tr>
                            {/if}
                        {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        {else}
            <p>
                {l s='No group created' mod='ets_gdpr'}
            </p>
        {/if}
    {elseif $input.type == 'gdpr_radio'}
        {if isset($input.values) && $input.values}
            <ul class="ets_gdpr_options">
                {foreach from=$input.values item='option'}
                    <li class="ets_gdpr_item">
                        <div class="radio">
                            <label for="{$input.name|escape:'html':'utf-8'}_{$option.id_option|escape:'quotes'}">
                                <input type="radio" id="{$input.name|escape:'html':'utf-8'}_{$option.id_option|escape:'quotes'}" name="{$input.name|escape:'html':'utf-8'}" value="{$option.id_option|escape:'quotes'}" {if !empty($fields_value[$input.name]) && $fields_value[$input.name] == $option.id_option}checked{/if}>
                                {$option.name nofilter}
                            </label>
                        </div>
                    </li>
                {/foreach}
            </ul>
        {/if}
    {elseif $input.type == 'gdpr_checkbox'}
        {if isset($input.values) && $input.values}
            <ul class="ets_gdpr_options">
                {foreach from=$input.values item='option'}
                    <li class="ets_gdpr_item">
                        <div class="checkbox">
                            <label for="{$input.name|escape:'html':'utf-8'}_{$option.id_option|escape:'quotes'}">
                                <input type="checkbox" id="{$input.name|escape:'html':'utf-8'}_{$option.id_option|escape:'quotes'}" name="{$input.name|escape:'html':'utf-8'}[]" value="{$option.id_option|escape:'quotes'}" {if !empty($fields_value[$input.name]) && ( $fields_value[$input.name] == 'ALL' || (is_array($fields_value[$input.name]) && in_array($option.id_option, $fields_value[$input.name])) )}checked{/if}>
                                {$option.name nofilter}
                            </label>
                        </div>
                    </li>
                {/foreach}
            </ul>
            {if $input.name=='ETS_GDPR_NOTIFY_WHEN'}
                <p class="help-block">
                    <a href="{$modLink|escape:'quotes'}&pTAB=gdpr_design&sTAB=message">{l s='You can edit GDPR notification for each position above in "GDPR Notification / Messages" tab' mod='ets_gdpr'}</a>
                </p>
            {/if}
        {/if}
    {elseif $input.type == 'range'}
        <input id="{$input.name|escape:'html':'utf-8'}" name="{$input.name|escape:'html':'utf-8'}" type="range" min="0" max="10" value="{$fields_value[$input.name]|intval}">
        <label id="{$input.name|escape:'html':'utf-8'}_VAL" for="{$input.name|escape:'html':'utf-8'}">{$fields_value[$input.name]/10|round:1|floatval}</label>
    {elseif $input.type == 'switch'}
        {if $is15}
            <span class="switch prestashop-switch fixed-width-lg">
                {foreach $input.values as $value}
                    <input type="radio" name="{$input.name|escape:'quotes'}"{if $value.value == 1} id="{$input.name|escape:'quotes'}_on"{else} id="{$input.name|escape:'quotes'}_off"{/if} value="{$value.value|intval}"{if $fields_value[$input.name] == $value.value} checked="checked"{/if}/>
                    {strip}
                    <label {if $value.value == 1} for="{$input.name|escape:'quotes'}_on"{else} for="{$input.name|escape:'quotes'}_off"{/if}>
                        {if $value.value == 1}
                            {l s='Yes' mod='ets_gdpr'}
                        {else}
                            {l s='No' mod='ets_gdpr'}
                        {/if}
                    </label>
                {/strip}
                {/foreach}
                <a class="slide-button btn"></a>
            </span>
        {else}
            {$smarty.block.parent}
        {/if}
    {else}
        {$smarty.block.parent}
    {/if}
{/block}
{block name="input_row"}
    <div class="form-group-wrapper row_{$input.name|lower|escape:'html':'UTF-8'} {if isset($input.rel) && $input.rel}rel{/if}">
        {$smarty.block.parent}
    </div>
{/block}
{block name="footer"}
    {$smarty.block.parent}
{/block}
