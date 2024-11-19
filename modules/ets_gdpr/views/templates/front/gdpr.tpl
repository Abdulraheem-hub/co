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
{extends file='page.tpl'}
{block name='breadcrumb'}
    {$smarty.block.parent}
{/block}
{block name="page_content"}
    <div class="gdpr_account_data">
        <div id="left-column" class="col-xs-12 col-sm-4 col-md-3">
            {hook h='displayGdprMenu'}
        </div>
        <div id="content-wrapper" class="left-column right-column col-sm-8 col-md-9">
            {if !empty($_errors)}{$_errors nofilter}{/if}
            {if !empty($msg_success)}
                {hook h='displayGpdrBlock' template = 'msg-success'}
            {/if}
            {if !empty($tpl)}
                {hook h='displayGpdrBlock' template = $tpl}
            {/if}
        </div>
    </div>
{/block}
{block name="right_column"}{/block}
