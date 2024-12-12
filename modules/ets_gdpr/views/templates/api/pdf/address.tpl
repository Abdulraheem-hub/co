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
{if isset($object) && $object}
    {$style_tab nofilter}
    <table id="address-tab" width="100%" cellpadding="0">
        {foreach from=$object item = 'row'}
            <tr>
                <td class="address left">
                    <h3 class="grey bold">{$row.alias|escape:'html':'utf-8'}</h3>
                    <p>
                        {$row.formatted nofilter}
                    </p>
                </td>
            </tr>
        {/foreach}
    </table>
{/if}