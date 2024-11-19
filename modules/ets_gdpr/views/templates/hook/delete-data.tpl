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
{if isset($confirm) && $confirm}
    <script type="text/javascript">
        var gdpr_warning_delete = '{$confirm|escape:'html':'utf-8'}';
        var gdpr_msg_detele_data_error = '{l s='Data to delete is required' mod='ets_gdpr'}';
        var gdpr_msg_password_error = '{l s='Password is required' mod='ets_gdpr'}';
        var gdpr_warning_withdraw = '{l s='Do you want withdraw deletion request?' mod='ets_gdpr'}';
    </script>
{/if}
<div class="gdpr_delete_my_data">
    <h3 class="gdpr_title">{l s='Delete my data' mod='ets_gdpr'}</h3>
    {if isset($DATA_REQUEST) && $DATA_REQUEST}
        <p class="alert alert-info">
            {l s='Your last data deletion request is pending for a review from admin. You can send other data deletion request when the last request has been processed.' mod='ets_gdpr'}
        </p>
        {if $ETS_GDPR_WITHDRAW_REQUEST}
            <a id="withdraw_request" class="btn btn-primary" href="{$mLink|escape:'quotes'}&withdraw=1" title="{l s='Withdraw deletion request' mod='ets_gdpr'}">{l s='Withdraw deletion request' mod='ets_gdpr'}</a>
        {/if}
    {else}
        {if isset($ETS_GDPR_WARNING_DELETION_PAGE) && $ETS_GDPR_WARNING_DELETION_PAGE}
            <div class="gdpr_warning">
                <p class="alert alert-warning">{$ETS_GDPR_WARNING_DELETION_PAGE nofilter}</p>
            </div>
        {/if}
        <div class="gdpr_content">
            <form action="{$mLink|escape:'quotes'}" method="post" enctype="multipart/form-data">
                <section class="form-fields">
                    <div class="form-group row">
                        <label class="col-md-12 form-control-label">{l s='Which kinds of data do you want to delete?' mod='ets_gdpr'}</label>
                        <div class="col-md-12">
                            {if isset($dataTypes) && $dataTypes}
                                <ul class="gdpr_datatype">
                                    {foreach from=$dataTypes item='dataType'}
                                        {if !empty($DDs) && ($DDs == 'ALL' || ( $DDs != 'ALL' && in_array($dataType.id_option, $DDs)))}
                                            <li class="gdpr_datatype_item">
                                                <label for="dataType_{$dataType.id_option|escape:'quotes'}">
                                                    <input id="dataType_{$dataType.id_option|escape:'quotes'}" name="dataType[]" title="" value="{$dataType.id_option|escape:'quotes'}" type="checkbox"/>
                                                    {$dataType.name|escape:'html':'utf-8'}
                                                </label>
                                            </li>
                                        {/if}
                                    {/foreach}
                                </ul>
                            {/if}
                        </div>
                    </div>
                    <div class="form-group row">
                        <span class="col-md-12 text-del">{l s='Please enter your password to confirm the deletion of data' mod='ets_gdpr'}</span>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="input-group js-parent-focus form_password">
                                <input class="form-control js-child-focus js-visible-password" name="password" type="password" value="" pattern=".{literal}{5,}{/literal}" required="" placeholder="{l s='Your password' mod='ets_gdpr'}">
                                {if isset($is17) && $is17}
                                    <span class="input-group-btn">
                                      <button class="btn" type="button" data-action="show-password" data-text-show="Show" data-text-hide="Hide">
                                        {l s='Show' mod='ets_gdpr'}
                                      </button>
                                    </span>
                                {/if}
                            </div>
                        </div>
                    </div>
                </section>
                <footer class="form-footer text-left">
                    <button class="btn btn-primary" name="submitDelData" value="1" type="submit">{if isset($ETS_GDPR_REQUIRE_APPROVAL) && $ETS_GDPR_REQUIRE_APPROVAL}{l s='Send data deletion request' mod='ets_gdpr'}{else}{l s='Delete data' mod='ets_gdpr'}{/if}</button>
                </footer>
            </form>
        </div>
    {/if}
</div>