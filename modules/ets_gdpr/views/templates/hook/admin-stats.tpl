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
<div class="gdpr_admin_statistic">
    <div class="gdpr_admin_chart">
        <svg style="width:100%; height: 500px;"></svg>
    </div>
    <div class="gdpr_admin_filter">
        <form id="gdpr_admin_filter_chart" class="defaultForm form-horizontal" action="{$action|escape:'quotes'}" enctype="multipart/form-data" method="POST">
            <div class="gdpr_admin_filter_chart_settings">
                    <div class="gdpr_admin_filter_date">
                        <label>{l s='Month' mod='ets_gdpr'}</label>
                        <select id="months" name="months" class="form-control">
                            <option value="" {if !$sl_month} selected="selected"{/if}>{l s='All' mod='ets_gdpr'}</option>
                            {foreach from=$months key=k item=month}
                                <option value="{$k|intval}"{if $sl_month == $k} selected="selected"{/if}>{l s=$month mod='ets_gdpr'}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="gdpr_admin_filter_date">
                        <label>{l s='Year' mod='ets_gdpr'}</label>
                        <select id="years" name="years" class="form-control">
                            <option value="" {if !$sl_month} selected="selected"{/if}>{l s='All' mod='ets_gdpr'}</option>
                            {foreach from=$years item=year}
                                <option value="{$year|intval}" {if $sl_year == $year} selected="selected"{/if}>{$year|intval}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="gdpr_admin_filter_button">
                        <button name="submitFilterChart" class="btn btn-default" type="submit">{l s='Filter' mod='ets_gdpr'}</button>
                        <button name="submitResetChart" class="btn btn-default" type="submit">{l s='Reset' mod='ets_gdpr'}</button>
                    </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    var gdpr_x_days = '{l s='Day' mod='ets_gdpr'}';
    var gdpr_x_months = '{l s='Month' mod='ets_gdpr'}';
    var gdpr_x_years = '{l s='Year' mod='ets_gdpr'}';
    var gdpr_y_label = '{l s='Count' mod='ets_gdpr'}';
    var gdpr_chart = {$chart|json_encode}
</script>