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
<div class="panel ets-gdpr-panel">
    {if !empty($title)}
        <div class="panel-heading">
            <span class="gdpr_title">{$title|escape:'html':'UTF-8'}</span>
            {if isset($totalRecords) && $totalRecords}<span class="badge">{$totalRecords|intval}</span>{/if}
            <span class="panel-heading-action">
                {if !isset($show_add_new) || isset($show_add_new) && $show_add_new}
                    <a class="list-toolbar-btn" href="{$currentIndex|escape:'html':'UTF-8'}">
                        <span data-placement="top" data-html="true" data-original-title="{l s='Add new' mod='ets_gdpr'}" class="label-tooltip" data-toggle="tooltip" title="">
                            <i class="process-icon-new"></i>
                        </span>
                    </a>
                {/if}
                {if isset($preview_link) && $preview_link}
                    <a class="list-toolbar-btn" href="{$preview_link|escape:'html':'UTF-8'}">
                        <span data-placement="top" data-html="true" data-original-title="{l s='Preview ' mod='ets_gdpr'} ({$title|escape:'html':'UTF-8'})" class="label-tooltip" data-toggle="tooltip" title="">
                            <i style="margin-left: 5px;" class="icon-search"></i>
                        </span>
                    </a>
                {/if}
            </span>
        </div>
    {/if}
    {if $fields_list}
        <div class="table-responsive clearfix">
            <form method="post" action="{$currentIndex|escape:'html':'UTF-8'}&amp;list=true">
                <table class="table configuration">
                    <thead>
                        <tr class="nodrag nodrop">
                            {foreach from=$fields_list item='field' key='index'}
                                <th class="{$index|escape:'html':'UTF-8'}">
                                    <span class="title_box">
                                        {$field.title|escape:'html':'UTF-8'}
                                        {if isset($field.orderby) && $field.orderby && !empty($filter_params)}
                                            <a href="{$currentIndex|escape:'html':'UTF-8'}&amp;sort={$index|escape:'html':'UTF-8'}&amp;sort_type=asc&amp;list=true{$filter_params nofilter}"><i class="icon-caret-down"></i></a>
                                            <a href="{$currentIndex|escape:'html':'UTF-8'}&amp;sort={$index|escape:'html':'UTF-8'}&amp;sort_type=desc&amp;list=true{$filter_params nofilter}"><i class="icon-caret-up"></i></a>
                                        {/if}
                                    </span>
                                </th>                            
                            {/foreach}
                            {if $show_action}
                                <th style="text-align: center;">{l s='Action' mod='ets_gdpr'}</th>
                            {/if}
                        </tr>
                        {if $show_toolbar}
                            <tr class="nodrag nodrop filter row_hover">
                                {foreach from=$fields_list item='field' key='index'}
                                    <th class="{$index|escape:'html':'UTF-8'}">
                                        {if isset($field.search) && $field.search}
                                            {if $field.type=='text'}
                                                <input class="filter" name="{$index|escape:'html':'UTF-8'}" type="text" {if isset($field.width)}style="width: {$field.width|intval}px;"{/if} {if isset($field.active)}value="{$field.active|escape:'html':'UTF-8'}"{/if}/>
                                            {/if}
                                            {if $field.type=='select' || $field.type=='active'}
                                                <select  {if isset($field.width)}style="width: {$field.width|intval}px;"{/if}  name="{$index|escape:'html':'UTF-8'}">
                                                    <option value=""> -- </option>
                                                    {if isset($field.filter_list.list) && $field.filter_list.list}
                                                        {assign var='id_option' value=$field.filter_list.id_option}
                                                        {assign var='value' value=$field.filter_list.value}
                                                        {foreach from=$field.filter_list.list item='option'}
                                                            <option {if $field.active!=='' && $field.active==$option.$id_option} selected="selected" {/if} value="{$option.$id_option|escape:'html':'UTF-8'}">{$option.$value|escape:'html':'UTF-8'}</option>
                                                        {/foreach}
                                                    {/if}
                                                </select>                                            
                                            {/if}
                                        {else}
                                           {l s=' -- ' mod='ets_gdpr'}
                                        {/if}
                                    </th>
                                {/foreach}
                                {if $show_action}
                                    <th class="actions">
                                        <span class="pull-right">
                                            <input type="hidden" name="post_filter" value="yes" />
                                            {if $show_reset}<a  class="btn btn-warning"  href="{$currentIndex|escape:'html':'UTF-8'}&amp;list=true"><i class="icon-eraser"></i> {l s='Reset' mod='ets_gdpr'}</a> &nbsp;{/if}
                                            <button class="btn btn-default" name="ets_gdpr_submit_{$name|escape:'html':'UTF-8'}" id="ets_gdpr_submit_{$name|escape:'html':'UTF-8'}" type="submit">
            									<i class="icon-search"></i> {l s='Filter' mod='ets_gdpr'}
            								</button>
                                        </span>
                                    </th>
                                {/if}
                            </tr>
                        {/if}
                    </thead>
                    <tbody>
                        {if !empty($field_values)}
                            {foreach from=$field_values item='row'}
                                <tr>
                                    {foreach from=$fields_list item='field' key='key'}
                                        <td class="pointer {$key|escape:'html':'UTF-8'}">
                                            {if isset($field.rating_field) && $field.rating_field}
                                                {if isset($row.$key) && $row.$key > 0}
                                                    {for $i=1 to $row.$key}
                                                        <div class="star star_on"></div>
                                                    {/for}
                                                    {if $row.$key < 5}
                                                        {for $i=$row.$key+1 to 5}
                                                            <div class="star"></div>
                                                        {/for}
                                                    {/if}
                                                {else}
                                                    {l s=' -- ' mod='ets_gdpr'}
                                                {/if}
                                            {elseif $field.type == 'date' || $field.type == 'datetime'}
                                                {if isset($row.$key) && $field.type != 'date'}{dateFormat date=$row.$key full=1}{else}{dateFormat date=$row.$key full=0}{/if}
                                            {elseif $field.type != 'active'}
                                                {if isset($row.$key) && !is_array($row.$key)}{if isset($field.strip_tag) && !$field.strip_tag}{$row.$key nofilter}{else}{$row.$key|strip_tags|truncate:120:'...'|escape:'html':'UTF-8'}{/if}{/if}
                                                {if isset($row.$key) && is_array($row.$key) && isset($row.$key.image_field) && $row.$key.image_field}
                                                    <a class="ets_gdpr_fancy" href="{$row.$key.img_url|escape:'html':'UTF-8'}"><img style="{if isset($row.$key.height) && $row.$key.height}max-height: {$row.$key.height|intval}px;{/if}{if isset($row.$key.width) && $row.$key.width}max-width: {$row.$key.width|intval}px;{/if}" src="{$row.$key.img_url|escape:'html':'UTF-8'}" /></a>
                                                {/if}
                                            {else}
                                                {if isset($row.$key) && $row.$key}
                                                    <a href="{$currentIndex|escape:'html':'UTF-8'}&amp;{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}&amp;change_enabled=0&amp;field={$key|escape:'html':'UTF-8'}" class="list-action field-{$key|escape:'html':'UTF-8'} list-action-enable action-enabled list-item-{$row.$identifier|escape:'html':'UTF-8'}" data-id="{$row.$identifier|escape:'html':'UTF-8'}"><i class="icon-check"></i></a>
                                                {else}
                                                    <a href="{$currentIndex|escape:'html':'UTF-8'}&amp;{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}&amp;change_enabled=1&amp;field={$key|escape:'html':'UTF-8'}" class="list-action field-{$key|escape:'html':'UTF-8'} list-action-enable action-disabled  list-item-{$row.$identifier|escape:'html':'UTF-8'}" data-id="{$row.$identifier|escape:'html':'UTF-8'}"><i class="icon-remove"></i></a>
                                                {/if}
                                            {/if}
                                        </td>
                                    {/foreach}
                                    {if $show_action}
                                        <td class="text-right">
                                                <div class="btn-group-action">
                                                    <div class="btn-group pull-right">
                                                        {if isset($row.child_view_url) && $row.child_view_url}
                                                            <a class="btn btn-default" href="{$row.child_view_url|escape:'html':'UTF-8'}"><i class="icon-search-plus"></i> {l s='View' mod='ets_gdpr'}</a>
                                                        {elseif is_array($actions) && in_array('edit', $actions)}
                                                            <a class="edit btn btn-default" href="{$currentIndex|escape:'html':'UTF-8'}&amp;{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}"><i class="icon-pencil"></i> {l s='Edit' mod='ets_gdpr'}</a>
                                                        {elseif is_array($actions) && in_array('details', $actions) && !(isset($row.view_url))}
                                                            <a class="view btn btn-primary" href="{$currentIndex|escape:'html':'UTF-8'}&amp;{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}"><i class="icon-search-plus"></i> {l s='View details' mod='ets_gdpr'}</a>
                                                        {/if}
                                                        {if (is_array($actions) && in_array('delete', $actions)) || (isset($row.view_url) && $row.view_url)}
                                                            {if $row.view_url && count($actions) <= 1 && in_array('details', $actions)}
                                                                <a class="view btn btn-primary" href="{$row.view_url|escape:'html':'UTF-8'}"><i class="icon-search-plus"></i> {if isset($row.view_text) && $row.view_text} {$row.view_text|escape:'html':'UTF-8'}{else} {l s='Preview' mod='ets_gdpr'}{/if}</a>
                                                            {else}
                                                                <button data-toggle="dropdown" class="btn btn-default dropdown-toggle">
                                                                    <i class="icon-caret-down"></i>&nbsp;
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    {if isset($row.child_view_url) && $row.child_view_url}
                                                                        <li><a class="edit" href="{$currentIndex|escape:'html':'UTF-8'}&amp;{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}"><i class="icon-pencil"></i> {l s='Edit' mod='ets_gdpr'}</a></li>
                                                                        <li class="divider"></li>
                                                                    {/if}
                                                                    {if isset($row.view_url) && $row.view_url}
                                                                        <li><a href="{$row.view_url|escape:'html':'UTF-8'}"><i class="icon-search-plus"></i> {if isset($row.view_text) && $row.view_text} {$row.view_text|escape:'html':'UTF-8'}{else} {l s='Preview' mod='ets_gdpr'}{/if}</a></li>
                                                                        <li class="divider"></li>
                                                                    {/if}

                                                                    {if is_array($actions) && in_array('delete', $actions)}
                                                                        <li><a onclick="return confirm('{l s='Do you want to delete this item?' mod='ets_gdpr'}');" href="{$currentIndex|escape:'html':'UTF-8'}&amp;{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}&del=yes"><i class="icon-trash"></i> {l s='Delete' mod='ets_gdpr'}</a></li>
                                                                    {/if}
                                                                </ul>
                                                            {/if}
                                                        {/if}
                                                    </div>
                                                </div>
                                         </td>
                                    {/if}
                                </tr>
                            {/foreach}
                        {else}
                            <tr>
                                <td class="list-empty" colspan="{count($fields_list)+1|intval}">
                                    <div class="list-empty-msg">
                                        <i class="icon-warning-sign list-empty-icon"></i>
                                        {l s='No records found' mod='ets_gdpr'}
                                    </div>
                                </td>
                            </tr>
                        {/if}
                    </tbody>
                </table>
                {if isset($nb_pages) && $nb_pages > 1 && $pagination}
                    <div class="ets_gdpr_pagination" style="margin-top: 10px;">
                        <div class="links">
                            {if $pagination.page > 1}
                                {if isset($pagination.first)}<a class="first" href="{$pagination.first|escape:'quotes':'utf-8'}">|&lt;</a>{/if} {if isset($pagination.prev)}<a class="prev" href="{$pagination.prev|escape:'quotes':'utf-8'}">&lt;</a>{/if}
                            {/if}
                            {if $pagination.num_pages > 1}
                                {if $pagination.start > 1}...{/if}
                                {foreach from = $pagination.output key='ik' item='output'}
                                    {if $pagination.page == $output}
                                        <b>{$ik|intval}</b>
                                    {else}
                                        <a href="{$output|escape:'quotes':'utf-8'}">{$ik|intval}</a>
                                    {/if}
                                {/foreach}
                                {if $pagination.end < $pagination.num_pages}...{/if}
                                {if $pagination.page < $pagination.num_pages}
                                    {if isset($pagination.next)}<a class="next" href="{$pagination.next|escape:'quotes':'utf-8'}">&gt;</a>{/if} {if isset($pagination.last)}<a class="last" href="{$pagination.last|escape:'quotes':'utf-8'}">&gt;|</a>{/if}
                                {/if}
                            {/if}
                        </div>
                        <div class="results">{$pagination.result|escape:'html':'utf-8'}</div>
                    </div>
                {/if}
            </form>
        </div>
    {/if}
</div>
{if isset($contactLink) && $contactLink && isset($name) && $name == 'ets_gdpr_login'}
    <div class="gdpr_note">
        <span>{l s='Does it look good?' mod='ets_gdpr'}&nbsp;<a href="{$contactLink|escape:'html':'utf8'}">{l s='Contact us' mod='ets_gdpr'}</a>&nbsp;{l s='if you feel your account was accessed illegally' mod='ets_gdpr'}</span>
    </div>
{/if}
{if isset($totalRecords) && $totalRecords && !empty($field_values) && isset($name) && isset($btnLink) && $btnLink}
    {if $name == 'ets_gdpr_modification'}
        <div class="gdpr_actions">
            <a class="btn btn-primary btn-left" href="{$btnLink|escape:'quotes'}&download&type=modlog" title="{l s='Download' mod='ets_gdpr'}">
                {l s='Download' mod='ets_gdpr'}
            </a>
        </div>
    {elseif $name == 'ets_gdpr_login'}
        <div class="gdpr_actions">
            <a class="btn btn-primary btn-left" href="{$btnLink|escape:'quotes'}&download&type=loginlog" title="{l s='Download' mod='ets_gdpr'}">
                {l s='Download' mod='ets_gdpr'}
            </a>
        </div>
    {/if}
{/if}