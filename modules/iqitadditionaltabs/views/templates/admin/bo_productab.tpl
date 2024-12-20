{*
* 2017 IQIT-COMMERCE.COM
*
* NOTICE OF LICENSE
*
* This file is licenced under the Software License Agreement.
* With the purchase or the installation of the software in your application
* you accept the licence agreement
*
* @author    IQIT-COMMERCE.COM <support@iqit-commerce.com>
* @copyright 2017 IQIT-COMMERCE.COM
* @license   Commercial license (You can not resell or redistribute this software.)
*
*}

<fieldset class="form-group" style="margin-bottom: 5px;">
    <label class="form-control-label">{l s='Hidden' mod='iqitadditionaltabs'}</label>
    <label for="iqitadditionaltabs_active"><input data-toggle="switch" id="iqitadditionaltabs_active"
            class="js-iqitadditionaltabs-field small" data-inverse="true" type="checkbox"
            name="iqitadditionaltabs[active]" checked>
        {l s='Visible' mod='iqitadditionaltabs'}</label>
</fieldset>
<fieldset class="form-group">
    <label class="form-control-label">{l s='Title' mod='iqitadditionaltabs'}</label>

    <div class="input-group locale-input-group js-locale-input-group d-flex" id="iqitadditionaltabs_title" tabindex="1">

        {foreach from=$languages item=language}
            {if $language.active}
                <div data-lang-id="{$language.id_lang}"
                    class="serp-default-title js-locale-input js-locale-{$language.iso_code} {if $id_language != $language.id_lang} d-none{/if} "
                    style="flex-grow: 1;">
                    <input type="text" id="iqitadditionaltabs_title_{$language.id_lang}"
                        name="iqitadditionaltabs[title_{$language.id_lang}]" class="js-iqitadditionaltabs-field form-control">
                </div>
            {/if}
        {/foreach}
        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle js-locale-btn" type="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false" id="iqitadditionaltabs_title_dropdown">
                {foreach from=$languages item=language}
                    {if $id_language == $language.id_lang} {$language.iso_code} {break}{/if}
                {/foreach}
            </button>

            <div class="dropdown-menu dropdown-menu-right locale-dropdown-menu"
                aria-labelledby="iqitadditionaltabs_title_dropdown">

                {foreach from=$languages item=language}
                    <span class="dropdown-item js-locale-item"
                        data-locale="{$language.iso_code}">{$language.iso_code}</span>
                {/foreach}
            </div>
        </div>
    </div>
</fieldset>




<fieldset class="form-group">
    <label class="form-control-label">{l s='Content' mod='iqitadditionaltabs'}</label>
    <div class="translations tabbable" id="iqitadditionaltabs_description">

        <ul class="translationsLocales nav nav-pills">
            {foreach from=$languages item=language}
                <li class="nav-item">
                    <a href="#" data-locale="{$language.iso_code}"
                        class="{if $id_language == $language.id_lang} active{/if} nav-link" data-toggle="tab"
                        data-target=".translationsFields-iqitadditionaltabs_description_{$language.id_lang}">
                        {$language.iso_code}
                    </a>
                </li>
            {/foreach}
        </ul>

        <div class="translationsFields tab-content ">
            {foreach from=$languages item=language}
                {if $language.active}
                    <div data-locale="{$language.iso_code}"
                        class="iqitadditionaltabs_description translationsFields-iqitadditionaltabs_description_{$language.id_lang} tab-pane panel panel-default {if $id_language == $language.id_lang} show active{/if} translation-field  translation-label-{$language.iso_code}"
                        style="border: 1px solid #bbcdd2;">
                        <textarea id="iqitadditionaltabs_description_{$language.id_lang}"
                            name="iqitadditionaltabs[description_{$language.id_lang}]"
                            class="autoload_rte form-control js-iqitadditionaltabs-field"></textarea>
                    </div>
                {/if}
            {/foreach}
        </div>
    </div>
</fieldset>























<input type="hidden" id="iqitadditionaltabs_id_iqitadditionaltab" name="iqitadditionaltabs[id_iqitadditionaltab]"
    class="js-iqitadditionaltabs-field" value="" />

<div class="form-group clearfix">
    <div class="float-right">
        <button type="button" class="btn btn-primary" id="iqitadditionaltabs_add" data-product="{$idProduct}">
            <i class="material-icons">add</i> {l s='Add new' mod='iqitadditionaltabs'}
        </button>

        <button type="button" class="btn btn-primary hide" id="iqitadditionaltabs_edit" data-product="{$idProduct}">
            <i class="material-icons">save</i> {l s='Save changes' mod='iqitadditionaltabs'}
        </button>

        <button type="button" class="btn btn-danger-outline hide" id="iqitadditionaltabs_cancel">
            <i class="material-icons">cancel</i> {l s='Cancel' mod='iqitadditionaltabs'}
        </button>
    </div>
</div>

<div class="form-group">
    <h2>{l s='Tabs list' mod='iqitadditionaltabs'}</h2>

    <div class="list-group" id="iqitadditionaltab-list" data-product="{$idProduct}">
        {foreach from=$tabs item=tab}
            <div class="list-group-item" id="iqitadditionaltabs_{$tab.id_iqitadditionaltab}">

                <div class="row">
                    <div class="col-12">
                        <div class="float-left">
                            <span><i class="material-icons">reorder</i></span>
                            #{$tab.id_iqitadditionaltab} -
                            <div class="translations tabbable d-inline-block">
                                <div class="translationsFields tab-content">
                                    {foreach from=$languages item=language}
                                        {if $language.active}
                                            <div data-locale="{$language.iso_code}"
                                                class="translationsFields-iqitadditionaltabs_title_p_{$tab.id_iqitadditionaltab}_{$language.id_lang} {if $id_language == $language.id_lang} show active{/if}   translation-field  translation-label-{$language.iso_code}">
                                                {$tab.title[$language.id_lang]}</div>
                                        {/if}
                                    {/foreach}
                                </div>
                            </div>
                            {if $tab.is_shared}
                                <div>
                                    <span class="label color_field float-left"
                                        style="background-color:#108510;color:white;margin-top:5px;">
                                        {l s='Shared tab' mod='iqitadditionaltabs'}
                                    </span>
                                </div>
                            {/if}
                        </div>
                        <div class="btn-group-action float-right">
                            <button type="button" class="js-iqitadditionaltabs-edit btn btn-default"
                                data-tab="{$tab.id_iqitadditionaltab}">
                                <i class="material-icons">edit</i>
                                {l s='Edit' mod='iqitadditionaltabs'}
                            </button>
                            <button type="button" class="js-iqitadditionaltabs-remove btn btn-danger"
                                data-tab="{$tab.id_iqitadditionaltab}">
                                <i class="material-icons">delete</i> {l s='Delete' mod='iqitadditionaltabs'}
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        {/foreach}
    </div>
</div>

<div id="tmpl-iqitadditionaltab-list-item" class="d-none">
    <div class="list-group-item" id="iqitadditionaltabs_::tabId::">

        <div class="row">
            <div class="col-12">
                <div class="float-left">
                    <span><i class="material-icons">reorder</i></span>
                    # ::tabId:: -
                    <div class="translations tabbable d-inline-block">
                        <div class="translationsFields tab-content">
                            {foreach from=$languages item=language}
                                {if $language.active}
                                    <div data-locale="{$language.iso_code}"
                                        class="translationsFields-iqitadditionaltabs_title_p_{$language.id_lang} {if $id_language == $language.id_lang} show active{/if}   translation-field  translation-label-{$language.iso_code}">
                                        ::tabTitle{$language.id_lang}::</div>
                                {/if}
                            {/foreach}
                        </div>
                    </div>
                </div>
                <div class="btn-group-action float-right">
                    <button type="button" class="js-iqitadditionaltabs-edit btn btn-default" data-tab="::tabId::">
                        <i class="material-icons">edit</i>
                        {l s='Edit' mod='iqitadditionaltabs'}
                    </button>
                    <button type="button" class="js-iqitadditionaltabs-remove btn btn-danger" data-tab="::tabId::">
                        <i class="material-icons">delete</i> {l s='Delete' mod='iqitadditionaltabs'}
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>


<script type="text/javascript" src="{$path}views/js/admin_tab.js"></script>
<script>
    var iqitadditionaltabs_languages = {$languages|@json_encode};
</script>